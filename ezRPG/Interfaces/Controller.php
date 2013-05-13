<?php

namespace ezRPG\Interfaces;

interface Controller
{
	public function __construct(App $app, View $view);

	public function index();

	public function notImplemented();
}
