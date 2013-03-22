<?php

namespace Echo511\App\Mindmap;

class Package extends \Echo511\PackageSystem\BasePackage
{

	public function getName()
	{
		return 'Echo511AppMindmap';
	}



	public function getCompilerConfig()
	{
		return __DIR__ . '/resources/config/config.neon';
	}



	public function getRouters($prefix)
	{
		$router = new \Nette\Application\Routers\Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}



	public function configureConsole(\Symfony\Component\Console\Application $application, \Nette\DI\Container $container)
	{
		$convert = $container->createInstance('Echo511\App\Mindmap\Console\Command\Convert');

		$application->add($convert);
	}



}