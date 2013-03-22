<?php

namespace Echo511\App\Mindmap\Entity;

class Node extends \Nette\Object
{

	public $id;
	public $content;
	public $ancestor = null;
	public $descendants = array();

	public function addAncestor(Node $node)
	{
		$node->ancestor = $this->ancestor;
		$this->ancestor = $node;
		return $this;
	}



	public function addDescendant(Node $node)
	{
		$node->ancestor = $this;
		$this->descendants[] = $node;
		return $this;
	}



	public function getAncestor()
	{
		return $this->ancestor;
	}



	public function getAncestors()
	{
		if ($this->getAncestor() === null)
			return array();

		return array_merge($this->getAncestor()->getAncestors(), array($this->getAncestor()));
	}



	public function getDescendants()
	{
		return $this->descendants;
	}



	public function toArray()
	{
		$array = array();

		$array['id'] = $this->id;
		$array['content'] = $this->content;

		foreach ($this->getDescendants() as $descendant) {
			$array['descendants'][] = $descendant->toArray();
		}

		return $array;
	}



}