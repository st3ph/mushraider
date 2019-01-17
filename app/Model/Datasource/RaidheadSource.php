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

class RaidheadSource extends DataSource {

	public $config = array(
		'baseUrl' => 'http://api.raidhead.com/',
		'langs' => array('eng', 'fra')
	);

	private $http;

	public function __construct() {
		$this->http = new HttpSocket();
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
			$response = $client->get([
				'uri' => $this->config['baseUrl'] . $uri,
				'header' => [
					'User-Agent' => 'Mushraider',
				],
			]);

			$return = json_decode($response, true);
		} catch (Exception $e) {
			$return = false;
		}

		return $return;
	}

	public function gets($type = 'all') {
		$list = array();
		$json = $this->http->get($this->config['baseUrl'].'/games/index.json');
		$games = json_decode($json, true);
		if(is_null($games)) {
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
		$json = $this->http->get($this->config['baseUrl'].'/games/get/'.$slug.'.json');
		$game = json_decode($json, true);
		if(is_null($game)) {
			$error = json_last_error();
			throw new CakeException($error);
		}

		return $game;
	}

	public function serverStatus($slug) {
		$json = $this->http->get($this->config['baseUrl'].'/server_status/get/'.$slug.'.json');
		$serverStatus = json_decode($json, true);
		if(is_null($serverStatus)) {
			$error = json_last_error();
			throw new CakeException($error);
		}

		return $serverStatus;
	}
}
