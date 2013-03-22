<?php

namespace Echo511\App\Mindmap\Convertor\Input;

class StructuredTxt extends \Nette\Object implements \Echo511\App\Mindmap\Convertor\IInput
{

	public function getName()
	{
		return 'structuredTxt';
	}



	public function getExtension()
	{
		return 'txt';
	}



	/** Main node */
	private $node;

	public function convert($string)
	{
		$lines = explode("\n", $string);

		foreach ($lines as $line) {
			if (trim($line) != '') {
				$line = rtrim($line);
				$this->processLine($line);
			}
		}

		return $this->node;
	}



	private $lastIndent = -1; // because we cannot create a sibling to main node
	private $indentMap = array();
	private $lastNode;

	private function processLine($line)
	{
		$indent = strlen($line) - strlen(ltrim($line));

		$node = $this->createNode();
		$node->content = trim($line);

		if ($this->lastIndent == -1) {
			$this->node = $node;
		} elseif ($indent == $this->lastIndent) {
			$this->lastNode->getAncestor()->addDescendant($node);
		} elseif ($indent > $this->lastIndent) {
			$this->lastNode->addDescendant($node);
		} elseif ($indent < $this->lastIndent) {
			$this->findAncestorByIndent($indent)->addDescendant($node);
		}

		$this->lastIndent = $indent;
		$this->indentMap[] = array('indent' => $indent, 'node' => $node);
		$this->lastNode = $node;
	}



	private function findAncestorByIndent($indent)
	{
		foreach (array_reverse($this->indentMap) as $data) {
			if ($data['indent'] > $indent) {
				continue; // so long until the last possible ancestor found - in text node can skip from 3rd level to 1st
			} elseif ($data['indent'] <= $indent) {
				if (!$data['node']->getAncestor() instanceof \Echo511\App\Mindmap\Entity\Node)
					throw new \Exception("Multiple main nodes detected in structured txt. Only one main node supported.");

				return $data['node']->getAncestor();
			}
		}
	}



	public function createNode()
	{
		return new \Echo511\App\Mindmap\Entity\Node;
	}



}