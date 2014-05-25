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
            $this->Setting->setOption('css', $this->request->data['Setting']['css']);

            Configure::write('Config.language', $this->request->data['Setting']['sitelang']);
            Configure::write('Settings.language', $this->request->data['Setting']['sitelang']);            
            Configure::dump('config.ini', 'configini', array('Database', 'Settings'));

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

            $this->Setting->setOption('links', json_encode($customLinks));

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
        $this->request->data['Setting']['sitelang'] = Configure::read('Config.language');

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

        // Emails
        $email = json_decode($this->Setting->getOption('email'));
        $this->request->data['Setting']['email']['name'] = $email->name;
        $this->request->data['Setting']['email']['from'] = $email->from;
        $this->request->data['Setting']['email']['utf8'] = $email->encoding == 'utf8'?true:false;
        $this->request->data['Setting']['email']['transport'] = $email->transport;
        $this->request->data['Setting']['email']['host'] = $email->host;
        $this->request->data['Setting']['email']['port'] = $email->port;
        $this->request->data['Setting']['email']['username'] = $email->username;
        $this->request->data['Setting']['email']['password'] = $email->password;
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