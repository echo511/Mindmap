<?php

namespace Echo511\App\Mindmap\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Convert extends \Symfony\Component\Console\Command\Command
{

	private $manager;

	public function __construct(\Echo511\App\Mindmap\Convertor\ConvertorManager $manager)
	{
		parent::__construct();
		$this->manager = $manager;
	}



	protected function configure()
	{
		$this->setName('mindmap:convert')
			->setDescription('Convert file or folder from one mindmap format to another.')
			->addArgument('from', InputArgument::REQUIRED, 'What you want to convert?')
			->addArgument('to', InputArgument::OPTIONAL, 'What you want to convert?')
			->addOption('input', null, InputOption::VALUE_REQUIRED, 'Convert from what format?', 'structuredTxt')
			->addOption('output', null, InputOption::VALUE_REQUIRED, 'Convert to what format?', 'mindmup');
	}



	private $inputInterface;
	private $outputInterface;

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->inputInterface = $input;
		$this->outputInterface = $output;

		$inputFormat = $input->getOption('input');
		$outputFormat = $input->getOption('output');

		$from = $input->getArgument('from');

		if (is_file($from)) {
			if ($input->getArgument('to')) {
				$to = $input->getArgument('to');
			} else {
				$dir = dirname($from);
				$pathinfo = pathinfo($from);
				$filename = $pathinfo['filename'];
				$extension = $this->manager->getOutputConverter($outputFormat)->getExtension();
				$to = $dir . '/' . $filename . '.' . $extension;
			}
			$this->convertFile($from, $to, $inputFormat, $outputFormat);
		}

		if (is_dir($from)) {
			$extension = $this->manager->getInputConverter($inputFormat)->getExtension();
			foreach (\Nette\Utils\Finder::findFiles('*.' . $extension)->in($from) as $spl => $from) {
				$dir = dirname($from);
				$pathinfo = pathinfo($from);
				$filename = $pathinfo['filename'];
				$extension = $this->manager->getOutputConverter($outputFormat)->getExtension();
				$to = $dir . '/' . $filename . '.' . $extension;

				$this->convertFile($from, $to, $inputFormat, $outputFormat);
			}
		}
	}



	private function convertFile($from, $to, $inputFormat, $outputFormat)
	{
		$from = \Echo511\Utils\TextFile::from($from);
		$to = \Echo511\Utils\TextFile::from($to, true);

		$this->outputInterface->writeln("Converting " . $from->getFilename() . " -> " . $to->getFilename());

		$output = $this->manager->convert($from->getContent(), $inputFormat, $outputFormat);
		$to->rewriteContent($output);
	}



}