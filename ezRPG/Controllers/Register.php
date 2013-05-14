<?php

namespace ezRPG\Controllers;

class Register extends \ezRPG\Controller
{
	protected
		$title = 'Home';

	/**
	 * Default action
	 */
	public function index()
	{ 
		// Some example code to get you started

		// Create a model instance, see /ezRPG/Models/Example.php
		$exampleModel = $this->app->getSingleton('auth');
		$user = '';
		if ( isset ( $_POST['email']	) )	
		{
			$user = $exampleModel->register($_POST['username'], $_POST['email'], $_POST['password']);
		}
		if ($user == 1)
		{
			header('Location: index.php');
		}
		// Pass the data to the view to display it
		$this->view->set('helloWorld', $user);
	}
	

}
