<?php
App::uses('IniReader', 'Configure');
App::uses('Controller', 'Controller');
class ApiAppController extends Controller {
    public $components = array('RequestHandler', 'Tools');

	public function beforeFilter() {		
		parent::beforeFilter();

        Configure::config('configini', new IniReader());
        Configure::load('config.ini', 'configini');        

        /*
        * "Auth" logic
        * Generate and test the security key
        */
        $api = json_decode($this->Setting->getOption('api'));
        if(empty($api) || empty($api->enabled) || !$api->enabled) {
            return $this->api401(__('API is disabled'));
        }

        // No key, refuse connexion
        if(empty($this->request->params['named']) || empty($this->request->params['named']['key'])) {
            return $this->api401(__('Security key is missing'));
        }

        // Get the key and remote it from the params
        $theirSecurityKey = $this->request->params['named']['key'];
        unset($this->request->params['named']['key']);

        // Make sure named params are in alphabetical order
        asort($this->request->params['named']);

        // generate the security key
        $paramsUrl = '/'.$this->request->params['controller'].'/'.$this->request->params['action'];
        $paramsUrl .= $this->Tools->paramsToUrl($this->request->params['named']);
        $securityKey = hash_hmac('sha1', $paramsUrl, $api->privateKey);

        if($theirSecurityKey != $securityKey) {
            return $this->api401(__('Wrong security key'));
        }
	}

    private function api401($msg) {
        $this->response->statusCode(401);
        $this->set('message', $msg);
        return $this->render('Errors/error401');
    }
}