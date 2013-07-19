<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;

class PlayerRole extends Model
{
	protected $tableName = 'player_role';

	public function findAllForPlayer($player_id) {
		$sql = 'SELECT * FROM <prefix>player_role pr LEFT JOIN <prefix>role r ON r.id = pr.role_id WHERE pr.player_id = :player_id';
		$query = $this->prepare($sql);
		$query->bindParam('player_id', $player_id);
		$query->execute();
		
		return $query->fetchAll();
	}
	
	public function addRole($player_id, $role_id) {
		$data['player_id'] = intval($player_id);
		$data['role_id'] = intval($role_id);
		
		$data['id'] = parent::add($data);
		return $data['id'];
	}
	
}