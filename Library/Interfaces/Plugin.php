<?php

namespace ezRPG\Library\Interfaces;

interface Plugin
{
	public function __construct(App $app, View $view, Module $module);
}