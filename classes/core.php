<?php
class Core
{

	protected static $_Config = array();

	public function __construct($Configuration)
	{
		if(is_array($Configuration))
		{
			foreach($Configuration as $Key => $Config)
			{
				$this->setConfig($Key, $Config);
			}
		}
	}

	public function dispatch($Controller = null)
	{
		// Replace any preceeding slashes from the request.
		$Controller = preg_replace('/^\//', '', $Controller);

		if(empty($Controller))
		{
			$Controller = $this->config('Application.Controller.Default');
		}

		// Require our controller
		if(!file_exists(CONTROLLER_DIR . $Controller . CONTROLLER_EXT))
		{
			$Controller = '_error404';
		}
		include(CONTROLLER_DIR . $Controller . CONTROLLER_EXT);

		// Execute the controller index
		$SetupControllerName = $Controller . 'Controller';
		$SetupController = new $SetupControllerName;
		$SetupController->view = $Controller;

		if(!$SetupController->index())
		{
			return false;
		}
	}

	public static function config($Name = FALSE)
	{
		if($Name === FALSE)
		{
			$Result = self::getAllConfig();
		}
		else
		{
			$Result = self::getConfig($Name);
		}

		return $Result;
	}

	private static function getAllConfig()
	{
		return self::$_Config;
	}

	private static function getConfig($Name)
	{
		if(array_key_exists($Name, self::$_Config))
		{
			return self::$_Config[$Name];
		}
	}

	private function setConfig($Key, $FieldName)
	{

		if(is_array($FieldName) === FALSE)
		{
			$FieldName = array($FieldName);
		}

		foreach($FieldName as $Name => $Field)
		{
			self::$_Config[$Key . '.' . $Name] = $Field;
		}
	}
}
