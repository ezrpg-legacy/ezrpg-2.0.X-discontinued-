<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;

class PlayerRole extends Model
{
	protected $tableName = 'player_role';

	public function getRoles($player_id) {
		$sql = 'SELECT * FROM player_role pr INNER JOIN role r ON r.id = pr.role_id WHERE pr.player_id = :player_id';
		$query = $this->prepare($sql);
		$query->bindParam('player_id', $player_id);
		$query->execute();
	
		return $query->fetchAll();
	}
	
}