<?php

namespace ezRPG\Module\Admin\Config;
use ezRPG\Library\Module;

/**
 * Config Index
 */
class Index extends Module
{

	/**
	 * Default Action
	 */
	public function index()
	{
		$this->view->name = 'admin/config/index';
	}
	public function route()
	{
		$data = $this->container['app']->getModel('Route')->findAll();
		$this->container['view']->set('routes', $data);
		$this->view->name = 'admin/config/route';
	}
	public function rebuildroutes()
	{
		$route = $this->container['app']->getmodel('Route');
		$route->buildCache();
		header("location: ../route");
	}
	public function editroute($params=array())
	{
		$route = $this->container['app']->getmodel('Route');
		$data = array();
		$id = $params['id'];
		if ($id == "new") {
			$data['id'] = "new";
			$data['path'] = "";
			$data['base'] = "";
			$data['action'] = "";
			$data['type'] = "";
			$data['module'] = "";
			$data['params'] = "";
			$data['permission'] = "";
			$data['role'] = "";
		} else {
			$id = intval($id);
			$data = $route->get($id);
			if(!$data) {
				header("location: ../route");
				die;
			}
		}
		if ($_POST) {
			$data['path'] = (isset($_POST['path']))?$_POST['path']:null;
			$data['base'] = (isset($_POST['base']))?$_POST['base']:null;
			$data['action'] = (isset($_POST['action']))?$_POST['action']:null;
			$data['type'] = (isset($_POST['type']))?$_POST['type']:'literal';
			$data['module'] = (isset($_POST['module']))?$_POST['module']:null;
			$data['params'] = (isset($_POST['params']))?$_POST['params']:null;
			$data['permission'] = implode(',',$_POST['permission']);
			$data['role'] = implode(',',$_POST['role']);
			if ($id == "new") {
				$route->add($data);
				$route->buildCache();
				header("location: ../route");
			} else {
				$route->save($data, $id);
				$route->buildCache();
				header("location: ../route");
			}
		}
		$data['permission'] = explode(",",$data['permission']);
		$data['role'] = explode(",",$data['role']);
		foreach(glob("./module/*") as $base) {
			$data['bases'][] = strtolower(str_ireplace('./module/','',$base));
		}
		$data['permissions'] = $this->container['app']->getmodel('Permission')->findAll();
		$data['roles'] = $this->container['app']->getmodel('Role')->findAll();
		$this->container['view']->set('data', $data);
		$this->view->name = 'admin/config/editroute';
	}
}
