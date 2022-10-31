<?php 

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

function test(string $testname, \Closure $function): void
{
	$function();
}
