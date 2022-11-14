<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette,
	Nette\Application\UI\Form;
use App\Model\Brand;
use App\Components\Grid\{GridControl, GridControlFactory};


final class BrandPresenter extends Nette\Application\UI\Presenter
{

    
    public function __construct(
        private Brand $brand,
		private GridControlFactory $gridControlFactory,
		){}
    
    /**
     * Create component, call factory defined in service.neon
     * @return GridControl
     */
	public function createComponentGrid(): GridControl
	{
		return $this->gridControlFactory->create($this->brand);
	}
}
