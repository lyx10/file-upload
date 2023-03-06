<?php
namespace Home\Controller;
use Think\Controller;

header("Access-Control-Allow-Origin: *");

class UserController extends Controller {
	public function login(){
		$username = trim_space(I('post.username'));
		$password = trim_space(I('post.password'));
		$isPersistedLogin = I('post.isPersistedLogin');

		if(mb_strlen($username, "utf-8") < 6 || mb_strlen($username, "utf-8") > 20){
			$this -> ajaxReturn(
        [
          error_code => '1001',
          error_msg => 'Invalid username length'
        ]
			);
		}

		if(mb_strlen($password, "utf-8") < 6 || mb_strlen($password, "utf-8") > 20){
			$this -> ajaxReturn(
        [
          error_code => '1002',
          error_msg => 'Invalid password length'
        ]
			);
		}

		$users = D('Users');

		$this -> ajaxReturn($users -> loginAction($username, $password, $isPersistedLogin)); 
	}

	public function checkAuth(){
		$auth = I('post.auth');

		$arr = explode(':', $auth);
		$ident_code = $arr[0];
		$token = $arr[1];

		$users = D('Users');

		$this -> ajaxReturn($users -> checkAuth($ident_code, $token));
	}

	public function logout(){
		setcookie('auth', '', time() - 3600, '/');
		setcookie('nickname', '', time() - 3600, '/');

		header('Location: http://localhost/login/index.html');
	}
}