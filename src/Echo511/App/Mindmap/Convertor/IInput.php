<?php

namespace Echo511\App\Mindmap\Convertor;

interface IInput
{

	public function convert($input);

	public function getExtension();

	public function getName();
}