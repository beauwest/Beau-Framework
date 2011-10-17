<?php

class Database
{

	private static $instance = null;
	private static $connection = null;
	private static $isSetup = false;
	private static $Server = null;
	private static $DatabaseName = null;
	private static $Username = null;
	private static $Password = null;

	public static function setupConnection($Server, $Database, $Username, $Password)
	{
		Database::$isSetup = true;
		Database::$Server = $Server;
		Database::$DatabaseName = $Database;
		Database::$Username = $Username;
		Database::$Password = $Password;
	}

	public static function singleton()
	{
		if(self::$instance == null)
		{
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance->connection();
	}

	public function connection()
	{
		if(self::$connection == null)
		{
			$DatbaseOptions = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
			self::$connection = new PDO('mysql:host=' . Database::$Server . ';dbname=' . Database::$DatabaseName, Database::$Username, Database::$Password, $DatbaseOptions);
		}
		return self::$connection;
	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public function __wakeup()
	{
		trigger_error('Unserializing is not allowed.', E_USER_ERROR);
	}
}