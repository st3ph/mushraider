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

class RaidplannerDbSource extends DataSource {

	public $config = array(
		'baseUrl' => 'https://db.raidplanner.org/',
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
	 * @return array response as array
	 * @throws CakeException
	 */
	private function _get($uri)
	{
		$client = new HttpSocket(array(
			'timeout' => 5,
			'request' => array(
				'redirect' => 2,
			),
		));

		try {
			$response = $client->get($this->config['baseUrl'] . $uri, array(), array(
				'header' => array(
					'User-Agent' => 'Mushraider/' . Configure::read('mushraider.version'),
				),
			));

			$return = json_decode($response->body(), true);

			if ($return === false) {
				throw new CakeException(json_last_error());
			}

			return $return;
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function gets($type = 'all') {
		$games = $this->_get('/games.json');

		if($type == 'list') {
			$list = array();
			foreach($games as $game) {
				$list[$game['short']] = $game['title'];
			}

			return $list;
		}

		return $games;
	}

	public function get($slug) {
		return $this->_get('/game/' . $slug . '.json');
	}
}
