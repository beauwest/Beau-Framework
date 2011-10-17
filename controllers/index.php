<?php

class indexController extends Controller
{
	public $code;
	public $loginFailed = false;

	public function index()
	{
		$this->render();
	}
}
