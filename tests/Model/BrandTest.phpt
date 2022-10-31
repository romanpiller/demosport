<?php

declare(strict_types=1);

require_once __DIR__ . '/../Bootstrap.php';

use Tester\Assert;
use Tester\TestCase;

use App\Model\Brand;


/**
 * @testCase
 */
class BrandText extends TestCase
{
	private Nette\Database\Explorer $database;
	private Nette\Database\Connection $db;
	private Brand $brand;
		
	public function __construct()
	{
	    $configurator = App\Bootstrap::bootForTest();
		$container = $configurator->createContainer();
		$this->database = $container->getByType(Nette\Database\Explorer::class);
		$this->db = $container->getByType(Nette\Database\Connection::class);
		
		$this->brand = new Brand($this->database);
	}
	
	
	protected function setUp(): void
	{
		Tester\Environment::lock('database');
		$this->db->query('TRUNCATE TABLE brand');
		$this->database->table('brand')->insert([
			['name' => 'a05x'],
			['name' => 'a07x'],
			['name' => 'a03x'],
			['name' => 'a09x'],
			['name' => 'a04x'],
			['name' => 'a06x'],
			['name' => 'a02x'],
			['name' => 'b01y'],
			['name' => 'b02y'],
			]);
		
		// id 3 and 5 deactivate -- delete
		$this->database->table('brand')
			->where('id = ? OR id = ?', 3,5)
			->update([
					'deleted' => true
			]);
	}


	public function testGetId(): void
	{
		$row = $this->brand->getId(2)->toArray();
		Assert::same([2, 'a07x'],[$row['id'], $row['name']], 'getId');
	}	
	
	
	public function testGetAll(): void
	{
		Assert::same(7,$this->brand->getAll()->count(), 'getAll');
	}
	
	
	public function testGetPage(): void
	{
		$table = $this->brand->getPage(2, 3, true)->fetchAll();
		foreach ($table as $row) {
			$record = $row->toArray();
			 $ret[] = [$record['id'], $record['name']];
		}
		Assert::same([
			[2, 'a07x'],[4, 'a09x'],[8, 'b01y'],
			],[ 
				[$ret[0][0], $ret[0][1]],
				[$ret[1][0], $ret[1][1]],
				[$ret[2][0], $ret[2][1]],
			], 'getPage'
		);
	}

	
	public function testCreateBrand(): void
	{
		$row = $this->brand->createBrand('bbbb'); // fithr record
		$insrow = $row->toArray();
		$readrow = $this->brand->getId(10)->toArray();

		Assert::equal($insrow, $readrow, 
				'createBrand - inserter rows, check read it');
	}


	public function testCreateBrandEx(): void
	{
		$row = $this->brand->createBrand('bbbb'); // fithr record
		
		Assert::exception(function() {
			$this->brand->createBrand('bbbb');		// duplicate
		}, Nette\Database\UniqueConstraintViolationException::class, 
				"SQLSTATE[23000]: Integrity constraint violation: 1062"
				. " Duplicate entry 'bbbb' for key 'name'");
	}
	

	public function testUpdateBrand(): void
	{
		$count = $this->brand->updateBrand(4, 'a99x');

		Assert::same(1, $count, 'updateBrand - number of updated rows');
		
		$row = $this->brand->getId(4)->toArray();
		Assert::same([4, 'a99x'],[$row['id'], $row['name']], 'updatedBrand - read changed record');		
		
		$count = $this->brand->updateBrand(99, 'a99x');
		Assert::same(0, $count, 'updateBrand - 0 - id not exist');
	}
	

	public function testUpdateBrandEx(): void
	{
		
		Assert::exception(function() {
			$this->brand->updateBrand(6,'a09x'); // the same like id 4
		}, Nette\Database\UniqueConstraintViolationException::class, 
				"SQLSTATE[23000]: Integrity constraint violation: 1062"
				. " Duplicate entry 'a09x' for key 'name'");
	}

	
	public function testdeleteBrand(): void
	{
		$count = $this->brand->deleteBrand(4);
		Assert::same(1, $count, 'deleteBrand - number of deleted rows');
		
		$count = $this->brand->getCount();
		Assert::same(6, $count, 
				'deleteBrand - number of remaining rows');
		
		$count = $this->brand->deleteBrand(99);
		Assert::same(0, $count, 'deleteBrand - 0 id not exists');
	}
		

	public function testGetCount(): void
	{
		$count = $this->brand->getCount();
		Assert::same(7, $count, 'getCount - number of rows');
	}
}

(new BrandText())->run();
