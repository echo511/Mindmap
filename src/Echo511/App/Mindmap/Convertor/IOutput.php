<?php

namespace Echo511\App\Mindmap\Convertor;

interface IOutput
{

	public function convert(\Echo511\App\Mindmap\Entity\Node $node);

	public function getExtension();

	public function getName();
}