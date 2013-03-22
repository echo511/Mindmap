<?php

namespace Echo511\App\Mindmap\Convertor\Output;

class Freemind extends \Nette\Object implements \Echo511\App\Mindmap\Convertor\IOutput
{

	public function getName()
	{
		return 'freemind';
	}



	public function getExtension()
	{
		return 'mm';
	}



	public function convert(\Echo511\App\Mindmap\Entity\Node $node)
	{
		$string = '';
		$string .= '<?xml version="1.0"?>';
		$string .= '<map version="0.7.1">';
		$string .= $this->processNode($node);
		$string .= '</map>';

		return $string;
	}



	private $lastId = 0;

	public function processNode(\Echo511\App\Mindmap\Entity\Node $node)
	{
		$node->id = $this->lastId + 1;
		$this->lastId = $node->id;

		$string = '';
		$string .= '<node TEXT="' . $node->content . '" ID="' . $node->id . '">';
		foreach ($node->getDescendants() as $descendant) {
			$string .= $this->processNode($descendant);
		}
		$string .= '</node>';

		return $string;
	}



}