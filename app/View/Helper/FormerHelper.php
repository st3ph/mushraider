<?php
App::uses('AppHelper', 'View/Helper');
class FormerHelper extends AppHelper {
	var $helpers = array ('Html', 'Tools');

	var $jour_semaine = array('0'=>'Dimanche', '1'=>'Lundi','2'=>'Mardi','3'=>'Mercredi','4'=>'Jeudi','5'=>'Vendredi','6'=>'Samedi');
	var $jour_semaine_court = array('0'=>'Dim','1'=>'Lun','2'=>'Mar','3'=>'Mer','4'=>'Jeu','5'=>'Ven','6'=>'Sam');
	var $jour = array('0'=>'Jour', '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9, '10'=>10, '11'=>11, '12'=>12, '13'=>13, '14'=>14, '15'=>15, '16'=>16, '17'=>17, '18'=>18, '19'=>19, '20'=>20, '21'=>21, '22'=>22, '23'=>23, '24'=>24, '25'=>25, '26'=>26, '27'=>27, '28'=>28, '29'=>29, '30'=>30, '31'=>31);
	var $month = array('1'=>'JAN', '2'=>'FEV', '3'=>'MAR', '4'=>'AVR', '5'=>'MAI', '6'=>'JUN', '7'=>'JUI', '8'=>'AOU', '9'=>'SEP', '10'=>'OCT', '11'=>'NOV', '12'=>'DEC');
	var $mois = array('01'=>'janvier', '02'=>'février', '03'=>'mars', '04'=>'avril', '05'=>'mai', '06'=>'juin', '07'=>'juillet', '08'=>'août', '09'=>'septembre', '10'=>'octobre', '11'=>'novembre', '12'=>'décembre');
	var $annee = array();

	public function __construct(View $View, $options = array()) {
		parent::__construct($View, $options);
		
		// Translate arrays
		$this->jour_semaine = array('0'=>__('Dimanche'),'1'=>__('Lundi'),'2'=>__('Mardi'),'3'=>__('Mercredi'),'4'=>__('Jeudi'),'5'=>__('Vendredi'),'6'=>__('Samedi'));
		$this->jour_semaine_court = array('0' => __('Dim'), '1' => __('Lun'), '2' => __('Mar'), '3' => __('Mer'), '4' => __('Jeu'), '5' => __('Ven'), '6' => __('Sam'));
		$this->month = array('1'=>__('JAN'), '2'=>__('FEV'), '3'=>__('MAR'), '4'=>__('AVR'), '5'=>__('MAI'), '6'=>__('JUN'), '7'=>__('JUI'), '8'=>__('AOU'), '9'=>__('SEP'), '10'=>__('OCT'), '11'=>__('NOV'), '12'=>__('DEC'));
		$this->mois = array('01'=>__('janvier'), '02'=>__('février'), '03'=>__('mars'), '04'=>__('avril'), '05'=>__('mai'), '06'=>__('juin'), '07'=>__('juillet'), '08'=>__('août'), '09'=>__('septembre'), '10'=>__('octobre'), '11'=>__('novembre'), '12'=>__('décembre'));
		
	}

	function truncateText($string, $length, $replacer = '...') {
		if(strlen($string) > $length) {
			return (preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;
		}
		return $string;
	}

	function date($date, $mode = 'full') {
		$dates = explode(' ', $date);
		$jours = explode('-', $dates[0]);
		if($mode == 'jour') {
			return $jours[2].'/'.$jours[1].'/'.$jours[0];
		}elseif($mode == 'full') {
			$heures = explode(':', $dates[1]);
			return $jours[2].'/'.$jours[1].'/'.$jours[0].' '.__('at').' '.$heures[0].':'.$heures[1];
		}elseif($mode == 'heure') {
			$heures = explode(':', $dates[1]);
			return $heures[0].':'.$heures[1];
		}
	}
	
	function url($url = NULL, $full = false) {
	    $intPos = strpos($url, 'http://');
        if($intPos === false || $intPos != 0) {
            $url = 'http://'.$url;
        }
        
	    return $url;
	}

	function charactersToRoles($eventRoles, $characters, $user = null, $event = array()) {
		if(!empty($eventRoles) && !empty($characters)) {
			foreach($characters as $character) {
				switch($character['status']) {
					case 1:
						$status = 'waiting';
						break;
					case 2:
						$status = 'validated';
						break;
					case 3:
						$status = 'refused';
						break;
					default:
						$status = 'nok';
				}
				$eventRoles['role_'.$character['raids_role_id']]['current'] = $status == 'validated'?$eventRoles['role_'.$character['raids_role_id']]['current'] + 1:$eventRoles['role_'.$character['raids_role_id']]['current'];
				$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '<li data-id="'.$character['Character']['id'].'" data-user="'.$character['Character']['User']['id'].'">';
					$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '<span class="character" style="color:'.$character['Character']['Classe']['color'].'">';
						if(!empty($character['Character']['Classe']['icon'])) {
							$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= $this->Html->image($character['Character']['Classe']['icon'], array('class' => 'tt', 'title' => $character['Character']['Classe']['title'], 'width' => '16')).'&nbsp;';
						}
						$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '<span class="tt" title="'.__('Signed in').' '.$this->Tools->niceDate($character['created']).'">'.$character['Character']['title'].'</span>';
						$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= ' ('.(empty($character['Character']['Classe']['icon'])?$character['Character']['Classe']['title'].' ':'').$character['Character']['level'].')';
					$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '</span>';
					if(!empty($character['comment'])) {
						$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '<span class="tt" title="'.$character['comment'].'"><span class="fa fa-comments-o"></span></span>';
					}
					if($user && (($user['User']['can']['manage_own_events'] && $user['User']['id'] == $event['User']['id']) || $user['User']['can']['manage_events'] || $user['User']['can']['full_permissions'])) {
						$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '<span class="fa fa-arrows muted pull-right"></span>';
					}
				$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '</li>';				
			}
		}

		return $eventRoles;
	}
}
?>
