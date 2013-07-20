<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;

/**
 * Installer
 * @see Library\Model
 */
class Installer extends Model
{
	/**
	 * runSqlFile
	 * @param string $file
	 * @param string $prefix
	 */
	public function runSqlFile($file, $prefix)
	{
		$sql = file_get_contents($file);
		$sql = str_ireplace("<pre>", $prefix, $sql);
		$query = $this->query($sql);
		return $query;
	}
}