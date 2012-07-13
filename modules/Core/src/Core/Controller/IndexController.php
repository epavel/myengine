<?php

namespace Core\Controller;

use MyEngine\Mvc\AbstractController;

class IndexController extends AbstractController
{
	public function headerAction() 
	{
		return array();
	}

	public function indexAction() 
	{
		return array();
	}
	
	public function testAction() 
	{
        throw new \Exception('Some error');
		return array();
	}
	
}