<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;

class Setting extends Model
{
	protected $tableName = 'setting';
	
	/**
	 * Retrieve all settings as an associative array
	 * @return multitype:string
	 */
	public function getAll() 
	{
		$parents = $this->query('SELECT title FROM setting WHERE parent_id IS NULL AND active = 1');
		$parents = $parents->fetchAll();
		
		$cache = array();
		foreach($parents as $v) {
			$cache[$v['title']] = '';
		}

		while(1) {
			$oc = count($cache, \COUNT_RECURSIVE);
			$this->recurse($cache);
			$nc = count($cache, \COUNT_RECURSIVE);
			
			if ($nc === $oc) {
				break;
			}
		}

		return $cache;
	}
	
	/**
	 * Utility function that recurses through an array and fills
	 * up parent settings.
	 * @param array $cache
	 */
	private function recurse(&$cache)
	{
		array_walk_recursive(
			$cache,
			function(&$item, $key, $obj)
			{
				$parent = $obj->query('SELECT id FROM setting WHERE title = \'' . $key . '\' AND value IS NULL');
				$parent = current($parent->fetchAll());
					
				if ($parent == false) {
					return;
				}
			
				$lookup = $obj->query('SELECT title, value FROM setting WHERE parent_id = ' . $parent['id']);
				$lookup = $lookup->fetchAll();
					
				foreach($lookup as $v) {
					$val = $v['value'];
			
					if ($val === '0' || $val === 'false') {
						$val = false;
					} elseif ($val === '1' || $val === 'true') {
						$val = true;
					} elseif (ctype_digit($val)) {
						$val = intval($val);
					}
			
					$item[$v['title']] = $val;
				}
			},
			$this
		);
	}
}