<?php

namespace ezRPG\Library\Interfaces;

interface View
{
	public function __construct(Container $container, $name);

	public function get($variable, $htmlEncode = false);

	public function set($variable, $value);

	public function render();
}