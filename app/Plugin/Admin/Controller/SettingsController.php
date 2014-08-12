<?php
App::uses('Folder', 'Utility');
class SettingsController extends AdminAppController {
    public $components = array('Image');
    public $uses = array('Setting');

    var $adminOnly = true;

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        $currentTheme = json_decode($this->Setting->getOption('theme'));
        if(!empty($this->request->data['Setting'])) {
            $this->Setting->setOption('title', $this->request->data['Setting']['title']);
            $this->Setting->setOption('notifications', $this->request->data['Setting']['notifications']);

            $this->Cookie->write('Lang', $this->request->data['Setting']['sitelang'], true, '+4 weeks');

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

            $this->Session->setFlash(__('Settings have been updated'), 'flash_success');
            return $this->redirect('/admin/settings');
        }

        // General
        $this->request->data['Setting']['title'] = $this->Setting->getOption('title');
        $this->request->data['Setting']['notifications'] = $this->Setting->getOption('notifications');

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
                foreach($this->request->data['Setting']['links'] as $customLink) {
                    if(!empty($customLink['title']) && !empty($customLink['url'])) {
                        $customLink['title'] = strip_tags(trim($customLink['title']));
                        $customLink['url'] = strip_tags(trim($customLink['url']));
                        $customLinks[] = $customLink;
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
        $customLinks = json_decode($this->Setting->getOption('links'));
        if(!empty($customLinks)) {
            foreach($customLinks as $customLink) {
                $link = array('title' => $customLink->title, 'url' => $customLink->url);
                $this->request->data['Setting']['links'][] = $link;
            }
        }
    }

    public function api() {
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
                if($bridge['enabled'] && empty($bridge['url'])) {
                   $this->Session->setFlash(__('Bridge\'s third party url is mandatory'), 'flash_error');
                   $error = true;
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
        }
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