<?php
class RaidrolesController extends AdminAppController {
    public $uses = array('RaidsRole');

    var $adminOnly = true;

    function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        
    }
}