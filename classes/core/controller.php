<?php

class Controller
{
	public $view = '';
	public $header = true;
	public $footer = true;
	public $self;

	public function __construct()
	{
		$this->self = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
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
		foreach(CORE::Config('Application.Stylesheets') as $sheet)
		{
			echo '<link rel="stylesheet" href="' . STYLESHEET_DIR . $sheet . '.css" type="text/css" media="screen" title="Screen">';
		}
	}
}
