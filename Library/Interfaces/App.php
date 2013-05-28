<?php

namespace ezRPG\Library\Interfaces;

interface App
{
	public function run();

	public function serve();

	public function getConfig($variable);

	public function setConfig($variable, $value);

	public function getRootPath();

	public function getAction();

	public function getArgs();

	public function getModel($modelName);

	/**
	 * @date 05/27/13
	 * @deprecated
	 */
	public function getSingleton($modelName);

	public function registerHook($hookName, array $params = array());

	public function error($number, $string, $file, $line);
}