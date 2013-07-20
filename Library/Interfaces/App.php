<?php

namespace ezRPG\Library\Interfaces;

interface App
{
	public function run();

	public function serve();
	
	public function getModel($modelName);

	public function registerHook($hookName, array $params = array());

	public function error($number, $string, $file, $line);
}