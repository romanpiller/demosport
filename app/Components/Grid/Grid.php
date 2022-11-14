<?php

declare(strict_types=1);

namespace App\Components\Grid;

use Nette,
	Nette\Application\UI\Control,
	Nette\Application\UI\Form;
use App\Model\Brand;


final class GridControl extends Control
{
	
	public int $page = 1;
	
	/** @persistent */
	public int $rowsPerPageIndex = 1;
	
	/** @persistent */
	public bool $sort = true; // zoradene 0/1 desc/asc
    
    private int $lastPage = 1;
	
	/** @var array<int> */
	private array $rowsPerPageDefined = [3,6,9,12,15,0];
	
	private string $action = 'view';	// default action
	
	public function __construct(
		private Brand $brand ,
	){}


	/**
	 * returns the number of lines per page
	 * @return int 
	 */
	private function getRowsPerPage(): int
	{
		$return = $this->rowsPerPageDefined[$this->rowsPerPageIndex];
		if ($return == 0)
		{
			// show all , page == 1 , rowsPerPage == count
			$return  = $this->brand->getCount();
			$this->page =1;
		}
		return 	$return;
	}


	/**
	 * Calculate the last page
	 * @param int $count number of databse record
	 * @return int
	 */
	private function getLastPage(): int 
	{
		$count = $this->brand->getCount();
		return (int) ceil($count / 
				($this->rowsPerPageDefined[$this->rowsPerPageIndex] ?: $count) );
	}


	// ******************  Signal ***********************

	/**
	 * Create form for create record
	 * @return void
	 */
    public function handleCreate(): void
    {
		$this->action = 'create';
        $form = $this->getComponent('createForm');
        $form->onSuccess[] = [$this, 'createFormSucceeded'];
    }


	/**
	 * Create form for edit record
	 * @param int $id record
	 * @return void
	 */
    public function handleEdit(int $id): void
    {
		$this->action = 'edit';
		$brand = $this->brand->getId($id);

        if (!$brand) {
            $this->flashMessage('Značka neexistuje...');
            $this->error();
        }
        
        $form = $this->getComponent('editForm');
        $form->setDefaults($brand->toArray());
        $form->onSuccess[] = [$this, 'editFormSucceeded2'];
	}


	/**
	 * Delete record from table
	 * @param int $id
	 * @return void
	 */
    public function handleDelete(int $id): void
    {
        $rows = $this->brand->deleteBrand($id);
		if ($rows !== 0) {
			$this->flashMessage('Značka zmazaná', 'success');
		}
    }


	/**
	 * View action
	 * @return void
	 */
	public function handleView(): void
	{
		$this->action = 'view';
	}


	/**
	 * Change page
	 * @param int $page actual page
	 * @param bool $sort sorting direction
	 * @return void
	 */
	public function handleChangePage(int $page, bool $sort): void
	{
		$this->sort = $sort;
        $this->lastPage = $this->getLastPage();
		$this->page = min(max($page,1), $this->lastPage);
		$this->redrawControl('tableContainer');
	}
	
	
	/**
	 * Sortinh table
	 * @param bool $sort sorting direction
	 * @return void
	 */
	public function handleSort(bool $sort): void
	{
		$this->sort = !$sort;
		$this->template->sort = $this->sort;
		$this->template->brands
				= $this->brand->getPage($this->page, 
					$this->getRowsPerPage(), $this->sort);
		$this->redrawControl('tableContainer');
	}
	

	// ******************  Form ***********************

	/**
	 * Definiton Create Fomr
	 * @return Form
	 */
	protected function createComponentCreateForm(): Form
    {
        $form = new Form;
		
        $form->addHidden('id');
        $form->addText('name', 'Značka:')
				->setRequired('Zadajte prosím značku')
				->setHtmlAttribute('placeholder', 'názov značky')
				->addRule($form::MAX_LENGTH, 'Maximálna dĺžka %d znakov', 32) // db varlen(32)
				->addRule($form::MIN_LENGTH, 'Minimálna dĺžka %d znakov', 2);
	
        $form->addSubmit('send', 'Ulož');
        $form->onSuccess[] = [$this, 'createFormSucceeded'];		
        return $form;
    }
	
	
	/**
	 * Definition Edit Form
	 * @return Form
	 */
	protected function createComponentEditForm(): Form
    {
        $form = new Form;
		
        $form->addHidden('id');
        $form->addText('name', 'Značka:')
				->setRequired()
				->addRule($form::MAX_LENGTH, 'Maximálna dĺžka %d znakov', 32) // db varlen(32)
				->addRule($form::MIN_LENGTH, 'Minimálna dĺžka %d znakov', 2);
		
        $form->addSubmit('send', 'Ulož');
		$form->onSuccess[] = [$this, 'editFormSucceeded'];
        return $form;
    }
	
	
	/**
	 * Definition combobox number row per page
	 * @return Form
	 */
	protected function createComponentChangeRowsPerPageForm(): Form
	{
		$i = 0;
		$select = [];
		foreach ($this->rowsPerPageDefined as $item) {
			if ($item == 0) {
				$select[$i++] = 'All';
			} else {
				$select[$i++] = $item;
			}
		}

		$form = new Form;
		
		$form->setHtmlAttribute('id','chrppId');
		$form->addSelect('rowsPerPageIndex', null, $select)
				->setDefaultValue($this->rowsPerPageIndex)
				->setHtmlAttribute('onchange','document.getElementById("chrppId").submit()')
				->setHtmlAttribute('class', 'browser-default small col');
		
		$form->onSuccess[] = [$this, 'changeRowsPerPageFormSucceeded'];
		return $form;
	}

	
	// ******************  Events *********************
		
	/**
	 * Event change number rows per page
	 * @param Form $form
	 * @param array<int> $data
	 * @return void
	 */
	public function changeRowsPerPageFormSucceeded (Form $form, array $data): void
	{
		$this->rowsPerPageIndex = $data['rowsPerPageIndex'];
		$this->page = 1;
	}
	
	
	/**
	 * Event create record
	 * @param Form $form
	 * @param array<string> $data
	 * @return void
	 */
    public function createFormSucceeded(Form $form, array $data): void
    {
		try {
			$row = $this->brand->createBrand($data['name']);
			if ($row !== false) {
				$this->flashMessage('Značka založená', 'success');
			}
		} catch (Nette\Database\UniqueConstraintViolationException)
		{
			$this->flashMessage('Značka už existuje alebo je neaktívna/zmazaná - nutné aktivovať', 'warning');
		}
    }

    
	/**
	 * Event edit record
	 * @param Form $form
	 * @param array<string> $data
	 * @return void
	 */
   public function editFormSucceeded(Form $form, array $data): void
   {
	   $id = intval($data['id'] ?? 0);
	   try {
			$rows = $this->brand->updateBrand($id, $data['name']);
			if ($rows !== 0) {
				$this->flashMessage('Značka bola aktualizovana', 'success');
			}
			else {
				$this->flashMessage('Značka nebola  aktualizovana', 'warning');
			}	
		} catch (Nette\Database\UniqueConstraintViolationException)
		{
			$this->flashMessage('Značka už existuje alebo je neaktívna/zmazaná - nutné aktivovať', 'warning');
		}
   }
   
   
   	// ******************  Render ***********************

   /**
    * Render componente
    * @return void
    */
	public function render(): void
	{
		// pocet zaznamov v tabulke celkom

		$rowsPerPage = $this->getRowsPerPage();
        $this->lastPage = $this->getLastPage();

		// smer zoradenia
		
		// ak je brands fill tak ho vyplnil jeden z handle - opakovene query
		if (!isset($this->template->brands)) {
			$this->template->brands = $this->brand->getPage($this->page,
					$rowsPerPage, $this->sort);
		}

		// hranice stranok pre paginator 5 stranok
		$lowPageRange = max($this->page - 2, 1);
        $highPageRange = min($lowPageRange + 4, 
								max($this->lastPage - 1,1));  
		$pageRange = range($lowPageRange, $highPageRange);
						

		$this->template->action = $this->action;
		$this->template->sort = $this->sort;
		$this->template->page = $this->page;
		$this->template->lastPage = $this->lastPage;
		$this->template->pageRange = $pageRange; 
		$this->template->rowsPerPage = $rowsPerPage;
		
		$this->template->render(__DIR__ . '/grid.latte');
	}
}
