<?php
namespace Home\Model;
use Think\Model;

class UsersModel extends Model {
	function loginAction($username, $password, $isPersistedLogin){
		$password = md5(md5($password) . $salt);
    
    $map['username'] = $username;  

		$check = $this -> where($map) -> find();

		if($check){
      $dbPassword = $this -> where($map) -> getField('password');

      if($password != $dbPassword){
        return [
	        error_code => '1004',
	        error_msg => 'Wrong password'
				];
      }else{
      	$nickname = $this -> where($map) -> getField('nickname');
      	$ident_code = md5(md5($username) . $salt);
      	$token = get_random(32);

      	if($isPersistedLogin == 'true'){
      		$timeout = time() + 30 * 24 * 60 * 60;
      	}else{
          $timeout = time() + 24 * 60 * 60;
      	}

      	$data['ident_code'] = $ident_code;
      	$data['token'] = $token;
      	$data['timeout'] = $timeout;

      	$updateUser = $this -> where($map) -> data($data) -> filter('strip_tags') -> save();

      	if($updateUser){
      		setcookie('auth', $ident_code . ':' . $token, $timeout, '/');
      		setcookie('nickname', $nickname, $timeout, '/');

      		return [
            error_code => '200',
            error_msg => 'Login ok'
      		];
      	}else{
      		return [
            error_code => '1005',
            error_msg => 'Login failed'
      		];
      	}
      }
		}else{
			return [
        error_code => '1003',
        error_msg => 'This username does not exist'
			];
		} 
	}

  function checkAuth($ident_code, $token){
    $map['ident_code'] = $ident_code;
    $map['token'] = $token;

    $check = $this -> where($map) -> find();

    if($check){
      $now = time();
      $timeout = $this -> where($map) -> getField('timeout');

      if($timeout > $now){
        return [
          error_code => '200',
          error_msg => 'Login check is ok'
        ];
      }else{
        setcookie('auth', '', time() - 3600, '/');
        setcookie('nickname', '', time() - 3600, '/');

        return [
          error_code => '1007',
          error_msg => 'Login expired'
        ];
      }
    }else{
      setcookie('auth', '', time() - 3600, '/');
      setcookie('nickname', '', time() - 3600, '/');
      
      return [
        error_code => '1006',
        error_msg => 'This token is not invalid'
      ];
    }
  }
}


