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

	public function __construct()
	{
		$this->self = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
		$this->selfQueryString = $_SERVER['REQUEST_URI'];

		$database = new Database();
		self::$database = $database->connection();

		self::$user = new User(self::$database);
	}

	public function render()
	{
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

	public function renderStylesheets()
	{
		foreach(CORE::config('Application.Stylesheets') as $sheet)
		{
			echo '<link rel="stylesheet" href="' . STYLESHEET_DIR . $sheet . '.css" type="text/css" media="screen" title="Screen">';
		}
	}
}
