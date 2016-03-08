<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Users extends Mdl_Campus {

	function __construct() {
		$this->table = 'wf_users';
	}
	
	public function online_usercnt() {
		$this->db->select("id");
		$this->db->from($this->table);
		$this->db->where('token != "" AND devicetoken != ""');

		return $this->db->get()->num_rows();
	}

	public function signup($username, $email, $password, $fullname, $city, $bday, $qbuser) {
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where('email', $email);

		if ($this->db->get()->num_rows() != 0) {
			$this->latestErr = "email is already used by another user.";
			return null;
		}

		$data = array(
			'email'=> $email,
			'username'=> $username,
			'password'=> $password,
			'fullname'=> $fullname,
			'city'=> $city,
			'bday'=> $bday,
			'verified' => 0,
			'qbid'=> $qbuser->id,
		);

		if (!$this->db->insert($this->table, $data)) {
			$this->latestErr = "Failed to create excute sql with : " . json_encode($data);
			return;
		}

		$data['id'] = $this->db->insert_id();

		unset($data['password']);

		return $data;
	}

	public function signin($qbid, $token) {
		$this->db->select("*");
		$this->db->from($this->table);

		$this->db->where("qbid", $qbid);
		$user = $this->db->get()->result()[0];


		$this->db->select("*");
		$this->db->where("qbid", $qbid);

		if (!$this->db->update($this->table, array('token'=> $token))) {
			return;
		}

		unset($user->password);
		//unset($user->updated_time);

		$user->token = $token;
		
		return $user;
	}

	public function signout($user) {
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where("id", $user);

		if (!$this->db->update($this->table, array('token'=> '', 'devicetoken' => '', 'udid' => ''))) {
			return;
		}

		//unset($user->password);
		//unset($user->updated_time);

		//$user['token'] = '';

		//return $user;
	}

	public function update($arg) {
		$id = $arg['id'];

		unset($arg['id']);

		$this->db->select("*");
		$this->db->from($this->table);

		$this->db->where("id", $id);

		if (!$this->db->update($this->table, $arg)) {
			return;
		}

		$this->db->from($this->table);

		$this->db->where("id", $id);

		return $this->db->get()->result()[0];
	}

	public function makeFriends($a, $b) {
		$this->latestErr = "";

		$userA = $this->get($a);

		if (!$userA) {
			$this->latestErr = "UserA is not valid...";
			return;
		}

		$ret = $this->addToStrArray($b, $userA->friends);

		if (!$ret['succeed']) {
			$this->latestErr = "This user added already.";
			return;
		}
		

		$this->db->from($this->table);
		$this->db->where('id', $a);
		$this->db->update($this->table, array('friends'=> json_encode($ret['array'])));





		$userB = $this->get($b);

		if (!$userB) {
			$this->latestErr = "UserB is not valid...";
			return;
		}


		$ret = $this->addToStrArray($a, $userB->friends);

		if (!$ret['succeed']) {
			$this->latestErr = "This user added already.";
			return;
		}
		

		$this->db->from($this->table);
		$this->db->where('id', $b);
		$this->db->update($this->table, array('friends'=> json_encode($ret['array'])));
	}
}

?>