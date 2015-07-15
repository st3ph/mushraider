<?php
App::uses('IniReader', 'Configure');
class DATABASE_CONFIG {
	public $default = array(
		'datasource' => 'FakeSource',
		'persistent' => true,
		'host' => '',
		'login' => '',
		'password' => '',
		'database' => '',
		'prefix' => '',
		'port' => '',
		//'encoding' => 'utf8',
	);

	function __construct() {
		Configure::config('configini', new IniReader());
        Configure::load('config.ini', 'configini');

		if($databaseConf = Configure::read('Database')) {
			$this->default['datasource'] = $databaseConf['datasource'];
			$this->default['host'] = $databaseConf['host'];
			$this->default['login'] = $databaseConf['login'];
			$this->default['password'] = $databaseConf['password'];
			$this->default['database'] = $databaseConf['database'];
			$this->default['prefix'] = $databaseConf['prefix'];
			$this->default['port'] = $databaseConf['port'];
		}
	}
}
