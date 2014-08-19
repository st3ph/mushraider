<?php
class WidgetAppController extends AppController {
    public $uses = array('Widget');

	public function beforeFilter() {
		parent::beforeFilter();

        $this->userRequired = false;
	}
}