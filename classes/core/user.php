<?php

class User
{

	public static $id = 0;

	public static function isLoggedIn()
	{
		if(!empty($_SESSION['user_id']))
		{
			self::$id = $_SESSION['user_id'];
			return true;
		}
		return false;
	}

	public static function requireLogin()
	{
		if(self::isLoggedIn())
		{
			return true;
		}
		header('Location: /login');
		exit;
	}

	public static function logIn($code)
	{
		$Database = Database::singleton();
		$Statement = $Database->prepare("SELECT id FROM user WHERE code = :code");
		$Statement->bindParam(':code', $code, PDO::PARAM_STR);
		$Statement->execute();
		$Result = $Statement->fetchColumn();

		if(!empty($Result))
		{
			$_SESSION['user_id'] = $Result;
			return true;
		}
		return false;
	}
}