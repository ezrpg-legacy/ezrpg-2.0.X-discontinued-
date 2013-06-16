<?php

namespace ezRPG\Library\Interfaces;

interface Module
{
	public function __construct(App $app, View $view);

	public function index();
}