<?php
class Core
{

	protected static $configuration = array();

	public function __construct($configuration)
	{
		if (is_array($configuration))
		{
			foreach ($configuration as $key => $option)
			{
				$this->setConfig($key, $option);
			}
		}
	}

	public function requestedPath()
	{
		if (isset($_SERVER['PATH_INFO']))
		{
			return $_SERVER['PATH_INFO'];
		}

		if (isset($_SERVER['REQUEST_URI']))
		{
			return str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
		}
		return false;
	}

	public function dispatch($controller = null)
	{
		// Replace any proceeding slashes from the request.
		$controller = preg_replace('/^\//', '', $controller);

		if (empty($controller))
		{
			$controller = $this->config('Application.Controller.Default');
		}

		// Include a class that extends the base controller if it exists.
		preg_match('/^(.*)\//iU', $controller, $extender);
		if (!empty($extender[1]))
		{
			$extender = str_replace('/', DS, $extender[1]) . DS . '_controller' . CONTROLLER_EXT;
			if (file_exists(CONTROLLER_DIR . $extender))
			{
				include(CONTROLLER_DIR . $extender);
			}
		}

		// Replace the controller path with the directory separator for sub-folders in the controllers.
		$controller = str_replace('/', DS, $controller);

		// Require our controller
		if (!file_exists(CONTROLLER_DIR . $controller . CONTROLLER_EXT))
		{
			$controller = '_error404';
		}
		include(CONTROLLER_DIR . $controller . CONTROLLER_EXT);

		// Execute the controller index
		$setupControllerName = preg_replace('/\/([a-z])/ie', "strtoupper('$1')", $controller);
		$setupControllerName = str_replace(array('\\', '/'), '', $setupControllerName);
		$SetupControllerName = $controller . 'Controller';
		$SetupController = new $SetupControllerName;
		$SetupController->view = $controller;

		if ($SetupController->index())
		{
			return true;
		}
		return false;
	}

	public static function config($configName = false)
	{
		if ($configName === false)
		{
			return self::getAllConfig();
		}
		else
		{
			return self::getConfiguration($configName);
		}
	}

	private static function getAllConfig()
	{
		return self::$configuration;
	}

	private static function getConfiguration($name)
	{
		if (array_key_exists($name, self::$configuration))
		{
			return self::$configuration[$name];
		}
		return false;
	}

	private function setConfig($key, $option)
	{

		if (is_array($option) === false)
		{
			$option = array($option);
		}

		foreach ($option as $name => $field)
		{
			self::$configuration[$key . '.' . $name] = $field;
		}
	}
}
