<?php
namespace ezRPG\Module\Installer;

use ezRPG\Library\Module;

class Index extends Module
{
    public function index()
    {
        $errors = array();
        
        $data = array
        (
            'php_version' => true,
            'writable' => array(
                'config' => true,
                'settings' => true,
            ),
            'passed' => true,
        );

        if (version_compare(PHP_VERSION, '5.3.2', '<')) {
            $data['php_version'] = false;
            $errors[] = 'Your web server need to be running at least PHP version 5.3.2.';
        }
        
        if (!is_writable('config.php')) {
            $data['writable']['config'] = false;
            $errors[] = 'The config.php file must be writable. Please rename config.php.new to config.php';
        }
        
        if (!is_writable('settings.php')) {
            $data['writable']['settings'] = false;
            $errors[] = 'The settings.php file must be writable. Please rename settings.php.new to settings.php';
        }

        if ($errors) {
            $data['passed'] = false;
            $this->view->set('errors', $errors);
        }
        
        $this->view->set('data', $data);
        $this->view->name = 'requirements';
    }
}