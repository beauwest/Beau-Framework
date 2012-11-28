<?php

class Controller
{
	public $view = '';
	public $header = true;
	public $footer = true;
	public $self;
	public $selfQueryString;

	public static $user;
	public static $database;
	public $exceptionHandlerException;

	public function __construct($isException = false)
	{
		if($isException)
		{
			return;
		}
		set_exception_handler('Controller::exception_handler');
		set_error_handler('Controller::error_handler', E_ALL | E_STRICT);
		register_shutdown_function('Controller::shutdown_function');
			
		$this->self = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
		$this->selfQueryString = $_SERVER['REQUEST_URI'];

		$database = new Database();
		self::$database = $database->connection();

		self::$user = new User(self::$database);
	}

	public function render()
	{
		header('Content-type: text/html; charset=utf-8');
		if($this->header)
		{
			include(VIEW_DIR . '_header' . VIEW_EXT);
		}

		include(VIEW_DIR . $this->view . VIEW_EXT);

		if($this->footer)
		{
			include(VIEW_DIR . '_footer' . VIEW_EXT);
		}
	}
	
	public static function exception_handler(Exception $exception)
	{
		$resource = new Controller(true);
		$resource->pageTitle = 'Application exception';
		$resource->exceptionHandlerException = $exception;
		$resource->view = '_exception';
		$resource->render();
		exit;
	}

	public static function error_handler($errorNumber, $errorString, $errorFile, $errorLine)
	{
		throw new Exception($errorString, $errorNumber);
	}

	public static function shutdown_function()
	{
		$error = error_get_last();
		if($error['type'] == 1)
		{
			throw new Exception($error['message'], $error['type']);
		}
	}
}
