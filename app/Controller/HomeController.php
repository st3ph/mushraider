<?php
class HomeController extends AppController {    
    var $helpers = array();
    var $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();

    }

    public function index() {
    	$this->redirect('/events');
    }
}