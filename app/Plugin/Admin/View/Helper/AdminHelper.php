<?php
App::uses('AppHelper', 'View/Helper');
class AdminHelper extends AppHelper {
	var $helpers = array ('Html', 'Paginator');

	public function __construct(View $View, $options = array()) {
		parent::__construct($View, $options);
	}
}
?>
