<?php
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
                        if(!isset($imageName['error'])) {
                            $theme['bgimage'] = $imageName['name'];
                        }
                    }else {
                        $theme['bgimage'] = null;
                    }
                }
                $this->Setting->setOption('theme', json_encode($theme));
            }

            $this->Session->setFlash(__('Settings have been updated'), 'flash_success');
            $this->redirect('/admin/settings');
        }

        $this->request->data['Setting']['title'] = $this->Setting->getOption('title');
        $this->request->data['Setting']['css'] = $this->Setting->getOption('css');
        $theme = json_decode($this->Setting->getOption('theme'));
        $this->request->data['Setting']['theme']['logo'] = $theme->logo;
        $this->request->data['Setting']['theme']['bgcolor'] = $theme->bgcolor;
        $this->request->data['Setting']['theme']['bgimage'] = $theme->bgimage;
        $this->request->data['Setting']['theme']['bgrepeat'] = $theme->bgrepeat;
        $this->request->data['Setting']['theme']['bgnoimage'] = !$theme->bgimage;
    }

    private function image($image, $customWebroot = false) {
        $return = array();
        if(!$image['error']) {
            $webroot = $customWebroot?$this->request->webroot:'/';
            $imageName = $image['name'];
            $this->Image->resize($image['tmp_name'], 'files/theme', $imageName);
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