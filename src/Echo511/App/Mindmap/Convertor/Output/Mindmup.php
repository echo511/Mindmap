<?php

namespace Echo511\App\Mindmap\Convertor\Output;

class Mindmup extends \Nette\Object implements \Echo511\App\Mindmap\Convertor\IOutput
{

	public function getName()
	{
		return 'mindmup';
	}



	public function getExtension()
	{
		return 'mup';
	}



	public function convert(\Echo511\App\Mindmap\Entity\Node $node)
	{
		$array = $this->processNode($node);

		$string = json_encode($array);
		return $string;
	}



	private $lastId = 0;

	public function processNode(\Echo511\App\Mindmap\Entity\Node $node)
	{
		$node->id = $this->lastId + 1;
		$this->lastId = $node->id;

		$array = array();
		$array['id'] = $node->id;
		$array['title'] = $node->content;

		$position = 0;
		foreach ($node->getDescendants() as $descendant) {
			$position++;
			$array['ideas'][$position] = $this->processNode($descendant);
		}

		return $array;
	}



}