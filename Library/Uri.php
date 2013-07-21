<?php

namespace ezRPG\Library;

/**
 * URI
 */
abstract class Uri {
	protected $segments = NULL;

	/**
	 * Initiates the Uri class
	 */
	private function __construct()
	{
		$this->segments = explode("/", $_GET['q']);
	}

	/**
	 * Segment
	 * @param int $number number of segment
	 * @param mixed $noExist value to return if segment doesn't exist.
	 * @return mixed segment, or $noExist if segment doesn't exist.
	 */
	public function segment($number, $noExist=false)
	{
		$number = intval($number);
		if (isset($this->segments[$number])) {
			return $this->segments[$number];
		} else {
			return $noExist;
		}
	}

	/**
	 * 
	 * @param int $number number of segment
	 * @param string $slash location of slash. 'leading', 'trailing', or 'both'
	 * @return mixed segment, or false if segment doesn't exist.
	 */
	public function slash_segment($number, $slash='trailing')
	{
		if ($this->segment($number)) {
			$return = "";
			if ($slash == 'leading' || $slash == 'both')
				$return .= "/";
			$return .= $this->segment($number);
			if ($slash == 'trailing' || $slash == 'both')
				$return .= "/";
			return $return;
		} else {
			return false;
		}
	}
}