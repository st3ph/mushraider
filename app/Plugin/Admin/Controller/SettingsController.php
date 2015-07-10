<?php
App::uses('Folder', 'Utility');
App::uses('HttpSocket', 'Network/Http');
class SettingsController extends AdminAppController {
    public $components = array('Image');
    public $uses = array('Setting', 'Page');

    var $adminOnly = true;

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        if(!empty($this->request->data['Setting'])) {
            $this->Setting->setOption('title', $this->request->data['Setting']['title']);
            $this->Setting->setOption('homePage', $this->request->data['Setting']['homePage']);
            if(!empty($this->request->data['Setting']['notifications'])) {
                $notifications = array();
                $notifications['enabled'] = $this->request->data['Setting']['notifications']['enabled'];
                $notifications['signup'] = $this->request->data['Setting']['notifications']['signup'];
                $notifications['contact'] = $this->request->data['Setting']['notifications']['contact'];
                $this->Setting->setOption('notifications', json_encode($notifications));
            }

            $this->Cookie->write('Lang', $this->request->data['Setting']['sitelang'], false, '+4 weeks');

            $databaseConfig = Configure::read('Database');
            $settingsConfig = Configure::read('Settings');
            $settingsConfig['language'] = $this->request->data['Setting']['sitelang']; // Site lang
            Configure::write('Database', $this->Tools->quoteArray($databaseConfig));
            Configure::write('Settings', $this->Tools->quoteArray($settingsConfig));
            Configure::dump('config.ini', 'configini', array('Database', 'Settings'));

            if(!empty($this->request->data['Setting']['email'])) {
                $email = array();
                $email['name'] = $this->request->data['Setting']['email']['name'];
                $email['from'] = $this->request->data['Setting']['email']['from'];
                $email['encoding'] = $this->request->data['Setting']['email']['utf8']?'utf8':'';
                $email['transport'] = $this->request->data['Setting']['email']['transport'];
                $email['host'] = $this->request->data['Setting']['email']['host'];
                $email['port'] = $this->request->data['Setting']['email']['port'];
                $email['username'] = $this->request->data['Setting']['email']['username'];
                $email['password'] = $this->request->data['Setting']['email']['password'];
                $this->Setting->setOption('email', json_encode($email));
            }

            $this->Setting->setOption('timezone', $this->request->data['Setting']['timezone']);

            $this->Session->setFlash(__('Settings have been updated'), 'flash_success');
            return $this->redirect('/admin/settings');
        }

        // General
        $this->request->data['Setting']['title'] = $this->Setting->getOption('title');
        $this->request->data['Setting']['homePage'] = $this->Setting->getOption('homePage');
        $pagesList = $this->Page->find('list', array('order' => 'title'));
        $this->set('pagesList', $pagesList);

        $notifications = json_decode($this->Setting->getOption('notifications'));
        if(!empty($notifications)) {
            $this->request->data['Setting']['notifications']['enabled'] = $notifications->enabled;
            $this->request->data['Setting']['notifications']['signup'] = $notifications->signup;
            $this->request->data['Setting']['notifications']['contact'] = $notifications->contact;
        }

        // Langs
        $appLocales = array();
        $localesFolder = new Folder(APP.'Locale');
        $locales = $localesFolder->read(true);
        if(!empty($locales) && !empty($locales[0])) {
            foreach($locales[0] as $locale) {
                $appLocales[$locale] = $locale;
            }
        }
        $this->set('appLocales', $appLocales);
        $this->request->data['Setting']['sitelang'] = Configure::read('Settings.language');

        // Timezone
        $this->request->data['Setting']['timezone'] = $this->Setting->getOption('timezone');

        // Emails
        $email = json_decode($this->Setting->getOption('email'));
        if(!empty($email)) {
            $this->request->data['Setting']['email']['name'] = $email->name;
            $this->request->data['Setting']['email']['from'] = $email->from;
            $this->request->data['Setting']['email']['utf8'] = $email->encoding == 'utf8'?true:false;
            $this->request->data['Setting']['email']['transport'] = $email->transport;
            $this->request->data['Setting']['email']['host'] = $email->host;
            $this->request->data['Setting']['email']['port'] = $email->port;
            $this->request->data['Setting']['email']['username'] = $email->username;
            $this->request->data['Setting']['email']['password'] = $email->password;
        }else {
            $host = substr_count($_SERVER['HTTP_HOST'], '.') > 1?substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.') + 1):$_SERVER['HTTP_HOST'];                        
            $host = strpos($host, ':') !== false?substr($host, 0, strpos($host, ':')):$host; // Remove port if present on unusual configurations
            $this->request->data['Setting']['email']['name'] = 'MushRaider';
            $this->request->data['Setting']['email']['from'] = 'mushraider@'.$host;
            $this->request->data['Setting']['email']['utf8'] = false;
        }
    }

    public function display() {
        $currentTheme = json_decode($this->Setting->getOption('theme'));
        if(!empty($this->request->data['Setting'])) {
            $this->Setting->setOption('css', $this->request->data['Setting']['css']);

            if(!empty($this->request->data['Setting']['theme'])) {
                // bgcolor
                $theme['bgcolor'] = $this->request->data['Setting']['theme']['bgcolor'];
                // bgrepeat
                $theme['bgrepeat'] = $this->request->data['Setting']['theme']['bgrepeat'];
                // logo                
                if(!empty($this->request->data['Setting']['theme']['logo']['tmp_name'])) {
                    $imageName = $this->image($this->request->data['Setting']['theme']['logo']);
                    if(!isset($imageName['error'])) {
                        $theme['logo'] = $imageName['name'];
                    }
                }elseif(!empty($currentTheme->logo)) {
                    $theme['logo'] = $currentTheme->logo;
                }else {
                    $theme['logo'] = '';
                }
                // bgimage
                if($this->request->data['Setting']['theme']['bgoriginal']) {
                    $theme['bgimage'] = $this->request->webroot.'img/bg.png';
                    $theme['bgrepeat'] = 'repeat';
                    $theme['bgcolor'] = '#444444';
                }else {
                    if(!$this->request->data['Setting']['theme']['bgnoimage']) {
                        $imageName = $this->image($this->request->data['Setting']['theme']['bgimage'], true);
                        if($this->request->data['Setting']['theme']['bgimage']['error'] == 4) {
                            $themeSetting = json_decode($this->Setting->getOption('theme'));
                            if(!empty($themeSetting->bgimage)) {
                                $theme['bgimage'] = $themeSetting->bgimage;
                            }else {
                                $theme['bgimage'] = $this->request->webroot.'img/bg.png';
                                $theme['bgrepeat'] = 'repeat';
                                $theme['bgcolor'] = '#444444';
                            }
                        }elseif(!isset($imageName['error'])) {
                            $theme['bgimage'] = $imageName['name'];
                        }
                    }else {
                        $theme['bgimage'] = null;
                    }
                }
                $this->Setting->setOption('theme', json_encode($theme));
            }

            $customLinks = array();
            if(!empty($this->request->data['Setting']['links'])) {
                pr($this->request->data['Setting']['links']);

                if(!empty($this->request->data['Setting']['links']['title'])) {
                    foreach($this->request->data['Setting']['links']['title'] as $key => $title) {
                        if(!empty($title) && !empty($this->request->data['Setting']['links']['url'][$key])) {
                            $customLink = array();
                            $customLink['title'] = strip_tags(trim($title));
                            $customLink['url'] = strip_tags(trim($this->request->data['Setting']['links']['url'][$key]));
                            $customLinks[] = $customLink;
                        }
                    }
                }
            }

            $this->Setting->setOption('links', json_encode($customLinks));

            $this->Session->setFlash(__('Settings have been updated'), 'flash_success');
            return $this->redirect('/admin/settings/display');
        }

        // Theming
        $this->request->data['Setting']['css'] = $this->Setting->getOption('css');
        $theme = json_decode($this->Setting->getOption('theme'));
        $this->request->data['Setting']['theme']['logo'] = $theme->logo;
        $this->request->data['Setting']['theme']['bgcolor'] = $theme->bgcolor;
        $this->request->data['Setting']['theme']['bgimage'] = $theme->bgimage;
        $this->request->data['Setting']['theme']['bgrepeat'] = $theme->bgrepeat;
        $this->request->data['Setting']['theme']['bgnoimage'] = !$theme->bgimage;

        // Custom Links
        $this->request->data['Setting']['links'] = json_decode($this->Setting->getOption('links'), true);

        // Still no link ? add empty one
        if(empty($this->request->data['Setting']['links'])) {
            $this->request->data['Setting']['links'][0]['title'] = '';
            $this->request->data['Setting']['links'][0]['url'] = '';
        }
    }

    public function calendar() {
        $currentSettings = json_decode($this->Setting->getOption('calendar'));
        if(!empty($this->request->data['Setting'])) {
            $calendar = array();
            $calendar['weekStartDay'] = $this->request->data['Setting']['weekStartDay'];
            $calendar['title'] = $this->request->data['Setting']['title'];
            $calendar['timeToDisplay'] = $this->request->data['Setting']['timeToDisplay'];
            $calendar['gameIcon'] = $this->request->data['Setting']['gameIcon'];
            $calendar['dungeonIcon'] = $this->request->data['Setting']['dungeonIcon'];
            $this->Setting->setOption('calendar', json_encode($calendar));

            $this->Session->setFlash(__('Settings have been updated'), 'flash_success');
            return $this->redirect('/admin/settings/calendar');
        }

        $calendar = json_decode($this->Setting->getOption('calendar'));
        $this->request->data['Setting']['weekStartDay'] = $calendar->weekStartDay;
        $this->request->data['Setting']['title'] = $calendar->title;
        $this->request->data['Setting']['timeToDisplay'] = $calendar->timeToDisplay;
        $this->request->data['Setting']['gameIcon'] = $calendar->gameIcon;
        $this->request->data['Setting']['dungeonIcon'] = $calendar->dungeonIcon;
    }

    public function api() {
        $defaultRoleId = $this->Role->getIdByAlias('member');

        if(!empty($this->request->data['Setting'])) {
            $error = false;

            $api = array();
            $api['enabled'] = $this->request->data['Setting']['enabled'];
            $api['privateKey'] = trim($this->request->data['Setting']['privateKey']);
            if($api['enabled'] && empty($api['privateKey'])) {
                $this->request->data['Setting']['enabled'] = 0;
                $this->Session->setFlash(__('Private key is mandatory'), 'flash_error');  
            }elseif(!empty($api['privateKey'])) {
                // Is bridge enable too ?
                $bridge = array();
                $bridge['enabled'] = $api['enabled']?$this->request->data['Setting']['bridge']['enabled']:0;
                $bridge['url'] = $this->request->data['Setting']['bridge']['url'];
                $bridge['default_group'] = $this->request->data['Setting']['bridge']['default_group'];
                if($bridge['enabled'] && empty($bridge['url'])) {
                   $this->Session->setFlash(__('Bridge\'s third party url is mandatory'), 'flash_error');
                   $error = true;
                }elseif($bridge['enabled']) { // test the bridge url before enabling it
                    $HttpSocket = new HttpSocket();
                    $response = $HttpSocket->post($bridge['url'], array('login' => 'mushraider_bot', 'pwd' => ''));
                    $auth = json_decode($response->body);
                    if($response->code >= 400 && $response->code < 600) {
                        if($response->code >= 400 && $response->code < 500) {
                            $this->Session->setFlash(__('Bridge is disabled because the third party url does not look right (code %s)', $response->code), 'flash_error');
                        }elseif($response->code >= 500) {
                            $this->Session->setFlash(__('Bridge is disabled because it seems that the third party plugin have a problem (code %s)', $response->code), 'flash_error');
                        }
                        $error = true;
                    }elseif(empty($auth) || (!empty($auth) && !isset($auth->authenticated))) {
                        $this->Session->setFlash(__('Bridge is disabled because the third party url does not look right'), 'flash_error');
                        $error = true;
                    }
                }

                if(!$error) {
                    $this->Setting->setOption('api', json_encode($api));
                    $this->Setting->setOption('bridge', json_encode($bridge));
                    $this->Session->setFlash(__('API settings have been updated'), 'flash_success');
                    return $this->redirect('/admin/settings/api');
                }
            }
        }
        
        $api = json_decode($this->Setting->getOption('api'));
        if(!empty($api)) {
            $this->request->data['Setting']['enabled'] = $api->enabled;
            $this->request->data['Setting']['privateKey'] = $api->privateKey;
        }

        $bridge = json_decode($this->Setting->getOption('bridge'));
        if(!empty($bridge)) {
            $this->request->data['Setting']['bridge']['enabled'] = $bridge->enabled;
            $this->request->data['Setting']['bridge']['url'] = $bridge->url;
            $this->request->data['Setting']['bridge']['default_group'] = !empty($bridge->default_group)?$bridge->default_group:$defaultRoleId;
        }

        $this->set('roles', $this->Role->find('list'));
    }

    private function image($image, $customWebroot = false) {
        $return = array();
        if(!$image['error']) {
            $webroot = $customWebroot?$this->request->webroot:'/';
            $imageName = $image['name'];
            $this->Image->resize($image['tmp_name'], 'files/theme', $imageName, null, null);
            $return['name'] = $webroot.'files/theme/'.$imageName;
        }else {            
            switch($image['error']) {
                case 1:
                case 2:
                    $error = __('Image is too big');
                    break;
                case 3:
                    $error = __('An error occur while uploading');
                    break;
            }
            if(!empty($error)) {
                $this->Session->setFlash($error, 'flash_error');  
                $return['error'] = true;
            }
        }

        return $return;
    }
}