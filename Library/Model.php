<?php

namespace ezRPG\Library;

abstract class Model implements Interfaces\Model
{
	protected $app;

	/**
	 * Constructor
	 * @param object $app
	 */
	public function __construct(Interfaces\App $app)
	{
		$this->app = $app;
	}
}