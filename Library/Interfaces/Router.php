<?php

namespace ezRPG\Library\Interfaces;

interface Router {
	public function addRoute($route);
	public function resolve($url);
}