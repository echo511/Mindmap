<?php

namespace Echo511\App\Mindmap\Convertor;

class ConvertorManager extends \Nette\Object
{

	/**
	 * @var IInput[]
	 */
	private $inputConverters = array();

	/**
	 * @var IOutput[] 
	 */
	private $outputConverters = array();

	public function __construct(\Nette\DI\Container $container)
	{
		foreach ($container->findByTag('Echo511AppMindmap.inputConvertor') as $name => $bool) {
			$this->addInputConverter($container->getService($name));
		}

		foreach ($container->findByTag('Echo511AppMindmap.outputConvertor') as $name => $bool) {
			$this->addOutputConverter($container->getService($name));
		}
	}



	public function addInputConverter(IInput $input)
	{
		$this->inputConverters[$input->getName()] = $input;
	}



	public function addOutputConverter(IOutput $output)
	{
		$this->outputConverters[$output->getName()] = $output;
	}



	public function getInputConverter($name)
	{
		return $this->inputConverters[$name];
	}



	public function getOutputConverter($name)
	{
		return $this->outputConverters[$name];
	}



	public function convert($input, $inputConverter, $outputConverter)
	{
		$inputConverter = $this->inputConverters[$inputConverter];
		$outputConverter = $this->outputConverters[$outputConverter];

		return $outputConverter->convert($inputConverter->convert($input));
	}



}