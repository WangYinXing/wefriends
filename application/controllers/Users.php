<?php
defined('BASEPATH') OR exit('No direct script access allowed');


//require 'mailgun-php/vendor/autoload.php';
//use Mailgun\Mailgun;


class Users extends Api_User {

	function __construct(){
		parent::__construct();

		$this->load->helper('url');
	}

	public function index() {
		parent::initView($this->ctrlName . '/list', 'users', 'Manage iprayees for CRUDing',
			array()
		);

		parent::loadView();
	}

	public function edit($arg) {
		$user = $this->Mdl_Users->get($arg);

		parent::initView($this->ctrlName . '/edit', 'users', 'Edit iprayee information.',
			$user
		);

		parent::loadView();
	}

	public function save() {
		$id = $_POST["id"];
		unset($_POST["id"]);

		$_POST["suspended"] = ($_POST["suspended"] == "on") ? 1 : 0;


		$this->Mdl_Users->updateEx($id, $_POST);

		redirect('/Users/', 'refresh');
	}

	public function del($arg) {
		$this->Mdl_Users->remove($arg);

		redirect('/Users/', 'refresh');
	}
	
	public function verify() {
		$data['error'] = "";

		if (!isset($_GET["token"])) {
			$data['content'] = "Sorry. Your token has been expired or invalid.";
			$this->load->view('vw_error', $data);
			return;
		}

		$data['token'] = $token = $_GET["token"];

		$this->load->model("Mdl_Tokens");

		$tokenRecords = $this->Mdl_Tokens->getAll("token", $token);

		if (count($tokenRecords) == 0) {
			$data['content'] = "Sorry. Your token is illegal.";
			$this->load->view('vw_error', $data);
			return;
		}

		$this->load->model("Mdl_Users");
		$user = $this->Mdl_Users->get($tokenRecords[0]->user);

		$this->Mdl_Users->updateEx($user->id, array("verified" => 1));
		$this->Mdl_Tokens->remove($tokenRecords[0]->id);

		$data['info'] = "Your account has been verified. You can login now.";
		//$this->load->view('Login/login',$data);
		echo "Your account has been verified successfully.";
	}
}

?>