<?php

declare(strict_types=1);

namespace App\Components\Grid;

use App\Model\Brand;

interface GridControlFactory
{
	public function create(Brand $brand): GridControl;
}
