<?php

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Utils\Rector\UnderscoreToCamelCaseVariableNameRector;

return static function (ContainerConfigurator $containerConfigurator): void {
	$parameters = $containerConfigurator->parameters();

	// paths to refactor; solid alternative to CLI arguments
	$parameters->set(Option::PATHS, [__DIR__ . '/app', __DIR__ . '/system']);

	// is there a file you need to skip?
	$parameters->set(Option::EXCLUDE_PATHS, [
		__DIR__ . '/app/Views',
		__DIR__ . '/system/Debug/Toolbar/Views/toolbar.tpl.php',
		__DIR__ . '/system/ThirdParty',
	]);

	// Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
	$parameters->set(Option::AUTOLOAD_PATHS, [
		// autoload specific file
		__DIR__ . '/system/Test/bootstrap.php',
	]);

	$services = $containerConfigurator->services();
	$services->set(UnderscoreToCamelCaseVariableNameRector::class);

	$parameters->set(Option::SKIP, [
		// skipped for UnderscoreToCamelCaseVariableNameRector rule
		// as the underscored variable in system/Autoloader/Autoloader.php removed in 4.1 branch
		UnderscoreToCamelCaseVariableNameRector::class => [__DIR__ . '/system/Autoloader/Autoloader.php'],
	]);
};
