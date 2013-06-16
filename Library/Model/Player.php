<?php
namespace ezRPG\Library\Model;

class Player extends \ezRPG\Library\Model
{
	public function getOnline()
	{
		$query = $this->query('SELECT * FROM player WHERE lastActive > DATE_SUB(NOW(), INTERVAL 15 MINUTE)');
		return $query->fetchAll(\Pdo::FETCH_ASSOC);
	}
}