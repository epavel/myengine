<?php

namespace MyEngine\Mvc;

class Request
{
	public function uri() 
	{
		return $_SERVER['REQUEST_URI'];
	}
} 