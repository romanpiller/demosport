<?php

declare(strict_types=1);

namespace App\Model;

use Nette,
	Nette\Database\Table\ActiveRow,
	Nette\Database\Table\Selection;

final class Brand
{

	use Nette\SmartObject;


	public function __construct(
			private Nette\Database\Explorer $database,
	){}

	
	/**
	 * Return record by id
	 * @param int $id
	 * @return ActiveRow|null
	 */
	public function getId(int $id): ActiveRow|null
	{
		return $this->database->table('brand')
				->get($id);
	}

	
	/**
	 * Return all record ordered by name asc
	 * @return Selection|null
	 */
	public function getAll(): Selection|null
	{
		return $this->database->table('brand')
			->where('deleted', false)
			->order('name ASC');
	}
	
	
	/**
	 * Return number $rowPerPage rows from page $page sorted by $sort
	 * @param int $page
	 * @param int $rowsPerPage
	 * @param bool $sort
	 * @return Selection|null
	 */
	public function getPage	(int $page = 1, int $rowsPerPage = 1, 
        bool $sort = true): Selection|null
	{
		return $sort ? 
			$this->database->table('brand')
				->order('name ASC')
				->limit($rowsPerPage, $rowsPerPage * ($page - 1))
				->where('deleted', false) :
			$this->database->table('brand')
				->order('name DESC')
				->limit($rowsPerPage, $rowsPerPage * ($page - 1))
				->where('deleted', false);
	}
	
	
	/**
	 * Insert record to database
	 * @param string $brand
	 * @return ActiveRow|bool
     * @throws Nette\Database\UniqueConstraintViolationException
	 */
	public function createBrand(string $brand): ActiveRow|int|bool
	{
		return $this->database->table('brand')
			->insert([
			'name' => $brand,
		]);
	}
	
	
	/**
	 * Update record in database by id
	 * @param int $id
	 * @param string $brand
	 * @return int number of updated rows
     * @throws Nette\Database\UniqueConstraintViolationException
	 */
	public function updateBrand(int $id, string $brand): int
	{
		return $this->database->table('brand')
			->where('id', $id)
			->update([
				'name' => $brand,
		]);
	}
	

	/**
	 * Delete record .. deactivate record by set deleted ==1
	 * @param int $id
	 * @return int numter of deactivated
	 */
	public function deleteBrand(int $id): int
	{
		return $this->database->table('brand')
			->where('id', $id)
			->update([
				'deleted' => true,
		]);
	}
	
	/**
	 * Return number of not deleted record in table
	 * @return int
	 */
	public function getCount(): int
	{
		return $this->database->table('brand')
				->where('deleted', false)
				->count('id');
	}
}
