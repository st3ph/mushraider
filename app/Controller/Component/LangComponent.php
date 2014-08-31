<?php
App::uses('Folder', 'Utility');
class LangComponent extends Component {
    var $controller;

    public function initialize(Controller $controller) {
        $this->controller = &$controller;
    }  

    public function startup(Controller $controller) {
        $this->controller = &$controller;

        $appLocales = array();
        $localesFolder = new Folder(APP.'Locale');
        $locales = $localesFolder->read(true);
        if(!empty($locales) && !empty($locales[0])) {
            foreach($locales[0] as $locale) {
                $appLocales[$locale] = $locale;
            }
        }
        $this->controller->set('appLocales', $appLocales); 

        $lang = Configure::read('Config.language');
        if(!empty($this->controller->request->params['pass'][0])) {
            if(in_array($this->controller->request->params['pass'][0], $appLocales)) {
                $lang = $this->controller->request->params['pass'][0];
            }   
        }

        $this->controller->Cookie->write('Lang', $lang, false, '+4 weeks');
    }
}