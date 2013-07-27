<?php

namespace ezRPG\Module\Installer\Config;
use ezRPG\Library\Module;

/**
 * Config Index
 * @see Library\Module
 */
class Index extends Module
{
	/**
	 * Default Action
	 */
	public function index()	{
		$data['guessUrl'] = 'http://'.$_SERVER['HTTP_HOST'].str_ireplace('/index.php', '', $_SERVER['PHP_SELF']);
		if ( isset($_POST['submit']) ) {
			$dbconfig = array(
				'db' => array(
					'driver'   => $_POST['dbtype'],
					'host'	 => $_POST['dbhost'],
					'database' => $_POST['dbname'],
					'username' => $_POST['dbuser'],
					'password' => $_POST['dbpass'],
					'port' => '',
					'prefix' => $_POST['dbpref']
				),
			);
			$newConfig = array_merge($this->container->offsetGet('config'), $dbconfig);
			$this->container->offsetSet('config', $newConfig);
			try {
				$installer = $this->app->getModel('installer');
				foreach ( glob("./Module/Installer/Config/Sql/*.sql") as $query ) {
					$installer->runSqlFile($query, $_POST['dbpref']);
				}
			} catch(\Exception $e) {
				$this->container['view']->setMessage("Please check your database configurations.", 'fail');
				$error = 1;
			}
			if ( !isset($error) ) {
				$dbtype = $_POST['dbtype'];
				$dbhost = $_POST['dbhost'];
				$dbname = $_POST['dbname'];
				$dbuser = $_POST['dbuser'];
				$dbpass = $_POST['dbpass'];
				$dbpref = $_POST['dbpref'];
				$gamename = $_POST['gamename'];
				$gameurl = $_POST['gameurl'];
				/* Generate configuration file */
				$config = <<<CONFIG
<?php

/**
 * Database Configuration
 */ 

\$config['db'] = array(
	'driver'   => '$dbtype',
	'host'	 => '$dbhost',
	'database' => '$dbname',
	'username' => '$dbuser',
	'password' => '$dbpass',
	'port' => '',
	'prefix' => '$dbpref'
);
CONFIG;
				$fh = fopen('config.php', 'w+');
				fwrite($fh, $config);
				fclose($fh);
				/* Generate Settings file */
				$settings = <<<SETTINGS
<?php
\$config['site'] = array(
		'name' => '$gamename',
		'url' => '$gameurl',
		'theme' => 'default'
);

\$config['router'] = array(
		'partialRoutes' => true,
		'routes' => array()
);

\$config['cache'] = array(
		'use' => false,
		'prefix' => 'ezRPG',
		'ttl' => 60
);

\$config['security'] = array(
		'hashRounds' => 11,
		'hashSaltSize' => 16,
		'passwordStrength' => 1,
		'login'	=> array(
				'showInvalidLoginReason' => true,
				'returnUsernameOnFailure' => true
		),
		'showExceptions' => true,
		'acl' => array(
			'use' => true,
			'rootRole' => 1 # !!THIS IS WRONG!! - 2 IS REALLY ROOT, 1 IS GUEST
		)
);

\$config['accounts'] = array(
		'requireActivation' => false,
		'emailActivation' => false
);


\$config['routes'] = array(
		'installer'	=> array(
				'module' => 'installer',
		),

		'admin' => array(
				'module' => 'admin',
		),

		'admin/player/listing' => array(
				'base' => 'admin',
				'module' => 'player',
				'action' => 'listing'
		),

		'admin/player' => array(
				'base' => 'admin',
				'module' => 'player'
		),

		'index(.*)' => array(
				'module' => 'index',
				'type' => 'regex',
				'params' => array('act')
		),

		'error' => array(
				'module' => 'error404',
				'action' => 'index'
		),

		'player/([a-z]+)' => array(
				'module' =>	'player',
				'action' => 'view',
				'params' => array('username'),
				'type' => 'regex'
		),

		'login' => array(
				'module' => 'login',
		),

		'register' => array(
				'module' => 'register',
		),

		'home' => array(
				'module' => 'home',
		),
);
SETTINGS;
				$fh = fopen('settings.php', 'w+');
				fwrite($fh, $settings);
				fclose($fh);
				header("location: {$gameurl}/installer/admin");
			}
			
		}

		$this->container['view']->set('data', $data);
		$this->view->name = "config";
	}
}
