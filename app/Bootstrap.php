<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;
use Tester\Environment;


class Bootstrap
{
	protected static string $appDir;
	
	protected static function _boot(): Configurator
	{
		$configurator = new Configurator;
		self::$appDir = dirname(__DIR__);


		$configurator->setTimeZone('Europe/Bratislava');
		$configurator->setTempDirectory(self::$appDir . '/temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();
		$configurator->addConfig(self::$appDir . '/config/common.neon');
		$configurator->addConfig(self::$appDir . '/config/services.neon');
		
		return $configurator;
	}


	public static function boot(): Configurator
	{
		$configurator = self::_boot();
		
		//$configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
		$configurator->setDebugMode(false);
		$configurator->enableTracy(self::$appDir . '/log');

		$configurator->addConfig(self::$appDir . '/config/prod.neon');

		return $configurator;
	}
	
	public static function bootForTest(): Configurator
	{
		$configurator = self::_boot();
	
		Environment::setup();
		
		$configurator->addConfig(self::$appDir . '/config/test.neon');

		return $configurator;
	}
}
