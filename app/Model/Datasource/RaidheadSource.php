<?php
/**
 * @name RaidHead API DataSource
 * @author Stéphane Litou aka Mush
 * @version 1.0
 * @desc Used for reading from RaidHead API.
 *
 */

App::uses('DataSource', 'Model/Datasource');
App::uses('HttpSocket', 'Network/Http');
App::uses('Configure', 'Core');

class RaidheadSource extends DataSource {

	public $config = array(
		'baseUrl' => 'https://api.raidhead.com/',
		'langs' => array('eng', 'fra')
	);

	public function __construct() {
		$lang = strtolower(Configure::read('Settings.language'));
		if(!in_array($lang, $this->config['langs'])) {
			$lang = $this->config['langs'][0];
		}

		$this->config['baseUrl'] .= $lang;
	}

	/**
	 * query api and return response as array or false
	 * @param  string $uri api uri
	 * @return array|false response as array or false if failed
	 */
	private function _get($uri)
	{
		$client = new HttpSocket([
			'request' => [
				'redirect' => 2,
			],
		]);

		try {
			$response = $client->get($this->config['baseUrl'] . $uri, [], [
				'header' => [
					'User-Agent' => 'Mushraider/' . Configure::read('mushraider.version'),
				],
			]);

			$return = json_decode($response->body(), true);
		} catch (Exception $e) {
			$return = false;
		}

		return $return;
	}

	public function gets($type = 'all') {
		$list = array();
		$games = $this->_get('/games/index.json');
		if($games === false) {
			$error = json_last_error();
			throw new CakeException($error);
		}

		if($type == 'list') {
			if(!empty($games)) {
				foreach($games as $game) {
					$list[$game['short']] = $game['title'];
				}
			}

			return $list;
		}

		return $games;
	}

	public function get($slug) {
		$game = $this->_get('/games/get/' . $slug . '.json');
		if($game === false) {
			$error = json_last_error();
			throw new CakeException($error);
		}

		return $game;
	}

	public function serverStatus($slug) {
		$serverStatus = $this->_get('/server_status/get/' . $slug . '.json');
		if($serverStatus === false) {
			$error = json_last_error();
			throw new CakeException($error);
		}

		return $serverStatus;
	}
}
