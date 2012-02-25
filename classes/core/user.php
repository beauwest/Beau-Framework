<?php

class User
{

	public $storage;
	private $database;

	public function __construct(PDO &$database)
	{
		$this->database = &$database;
		if (empty($_SESSION['user']))
		{
			$_SESSION['user'] = new stdClass();
		}
		$this->storage = &$_SESSION['user'];
	}

	public function isLoggedIn()
	{
		if (!empty($this->storage->id))
		{
			return true;
		}
		return false;
	}

	public function requireLogin()
	{
		if ($this->isLoggedIn())
		{
			return true;
		}
		return false;
	}

	public function logIn($email, $password)
	{
		$query = $this->database->prepare("SELECT * FROM user WHERE email = :email AND password = :password");
		$query->bindValue(':email', $email, PDO::PARAM_STR);
		$query->bindValue(':password', sha1($password), PDO::PARAM_STR);
		$query->execute();
		$record = $query->fetch();

		if (!empty($record))
		{
			$this->storage->id = $record->id;
			return true;
		}
		return false;
	}
}