<?php

class Database
{
	private static $server = null;
	private static $databaseName = null;
	private static $username = null;
	private static $password = null;
	private static $driver = null;

	public function __construct()
	{
		Database::$server = Core::config('Database.Host');
		Database::$databaseName = Core::config('Database.Database');
		Database::$username = Core::config('Database.Username');
		Database::$password = Core::config('Database.Password');
		Database::$driver = Core::config('Database.Driver');
	}

	public function connection()
	{
		try
		{
			$databaseOptions = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
			return new PDO('mysql:host=' . Database::$server . ';dbname=' . Database::$databaseName, Database::$username, Database::$password, $databaseOptions);
		} catch (PDOException $exception)
		{
			trigger_error('Database connection failed with message: "' . $exception->getMessage() . '"', E_USER_ERROR);
		}
		return false;
	}
}