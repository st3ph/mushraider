<?php
class Comment extends SociableAppModel {
	var $actsAs = array('Containable');

    public function beforeFind($params) {
        if(!isset($params['conditions']['deleted']) && !isset($params['conditions']['Commentaire.deleted'])) {
            $params['conditions']['deleted'] = 0;
        }

        return $params;
    }
}