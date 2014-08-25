<?php
App::uses('AppHelper', 'View/Helper');
class FormerHelper extends AppHelper {
	var $helpers = array ('Html', 'Tools');

	var $jour_semaine = array('1'=>'Lundi','2'=>'Mardi','3'=>'Mercredi','4'=>'Jeudi','5'=>'Vendredi','6'=>'Samedi','7'=>'Dimanche');
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
	
	function url($url) {
	    $intPos = strpos($url, 'http://');
        if($intPos === false || $intPos != 0) {
            $url = 'http://'.$url;
        }
        
	    return $url;
	}

	function calendar($options = array(), $events = array()) {
		$__options = array(
			'month' => date('n'),
			'year' => date('Y'),
		);
		$options = array_merge($__options, $options);

		$prev_year = $next_year = $options['year'];		 
		$prev_month = $options['month'] - 1;
		$next_month = $options['month'] + 1;
		 
		if($prev_month == 0) {
		    $prev_month = 12;
		    $prev_year = $options['year'] - 1;
		}
		if ($next_month == 13 ) {
		    $next_month = 1;
		    $next_year = $options['year'] + 1;
		}

		$todayTimestamp = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$timestamp = mktime(0, 0, 0, $options['month'], 1, $options['year']);
		$maxday = date("t", $timestamp);
		$thismonth = getdate($timestamp);
		$startday = $thismonth['wday'];
		$monthIn2Digits = str_pad($options['month'], 2, "0", STR_PAD_LEFT);



		$output = '';
		$output .= '<table id="calendar">';
			$output .= '<tr align="center">';
				$output .= '<td>';
					$output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
						$output .= '<tr class="links">';
							$output .= '<td class="prev">'.$this->Html->link('<i class="icon-chevron-left"></i> '.__('Previous'), '/events/index/m:'.$prev_month.'/y:'.$prev_year, array('escape' => false)).'</td>';
							$output .= '<td class="month">'.ucfirst($this->mois[$monthIn2Digits]).' '.$options['year'].'</td>';
							$output .= '<td class="next">'.$this->Html->link(__('Next').' <i class="icon-chevron-right"></i>', '/events/index/m:'.$next_month.'/y:'.$next_year, array('escape' => false)).'</td>';
						$output .= '</tr>';
					$output .= '</table>';
				$output .= '</td>';
			$output .= '</tr>';
			$output .= '<tr>';
				$output .= '<td align="center">';
					$output .= '<table width="100%" border="0" cellpadding="2" cellspacing="2" class="dates">';
						$output .= '<tr class="days">';
							foreach($this->jour_semaine as $jour) {
								$output .= '<td>'.$jour.'</td>';
							}
						$output .= '</tr>';

						for ($i=0; $i<($maxday+$startday); $i++) {
						    if(($i % 7) == 0 ) {
						    	$output .= '<tr>';
						    }
						    if($i < $startday) {
						    	$output .= '<td></td>';
						    }else {
						    	$day = ($i - $startday + 1);
						    	$dayTimestamp = mktime(0, 0, 0, $options['month'], $day, $options['year']);
						    	$formatCurrentDay = date('Y-m-d', mktime(0, 0, 0, $options['month'], $day, $options['year']));
						    	$dayClass = $dayTimestamp == $todayTimestamp?'currentDay':'';
						    	$dayClass = $dayTimestamp < $todayTimestamp?'pastDay':$dayClass;
						    	$output .= '<td valign="top" class="day '.$dayClass.'">';
						    		$output .= '<div class="clearfix dayNumber">';
						    			$output .= $day;
						    			if($dayTimestamp >= $todayTimestamp && ($options['user']['User']['can']['manage_events'] || $options['user']['User']['can']['full_permissions'])) {
						    				$output .= $this->Html->link('<i class="icon-plus-sign-alt"></i>', '/events/add/'.$formatCurrentDay, array('title' => __('Add event'), 'class' => 'pull-right tt', 'escape' => false));
						    			}
						    		$output .= '</div>';

					    			// Check if there is events this day
					    			if($matchingEvents = $this->extractEvents($events, $formatCurrentDay)) {
										$output .= '<ul>';
											foreach($matchingEvents as $matchingEvent) {
												// Tooltip
												$playersNeeded = 0;
												if($matchingEvent['EventsRole']) {
													foreach($matchingEvent['EventsRole'] as $eventRoles) {
														$playersNeeded += $eventRoles['count'];
													}
												}

												$tooltip = '<h5>'.$matchingEvent['Event']['title'].'</h5>';
												$tooltip .= '<div>';
													$tooltip .= $matchingEvent['Game']['title'].'<br/>';
													$tooltip .= $matchingEvent['Dungeon']['title'].'<br/>';
													$tooltip .= __('Roster').' : '.count($this->extractUsersWithStatus($matchingEvent['EventsCharacter'], 2)).'/'.$playersNeeded.'<br/>';
													$tooltip .= __('Start').' : '.$this->date($matchingEvent['Event']['time_start'], 'heure');
												$tooltip .= '<div>';


												$output .= '<li>';
													$output .= '<span class="time">'.$this->date($matchingEvent['Event']['time_invitation'], 'heure').'</span>';
													if(!empty($matchingEvent['Game']['logo'])) {
														$output .= $this->Html->image($matchingEvent['Game']['logo'], array('class' => 'logo', 'width' => 16));
													}
													if(!empty($matchingEvent['Dungeon']['icon'])) {
														$output .= $this->Html->image($matchingEvent['Dungeon']['icon'], array('class' => 'logo', 'width' => 16));
													}
													// Test is the user is registered for this event
													$registeredClass = '';
													if($registeredCharacter = $this->Tools->getRegisteredCharacter($options['user']['User']['id'], $matchingEvent['EventsCharacter'])) {
														$registeredClass = 'registered_'.$registeredCharacter['status'];
													}
													$eventTitle = !empty($matchingEvent['Event']['title'])?$matchingEvent['Event']['title']:$matchingEvent['Dungeon']['title'];
													$output .= $this->Html->link($eventTitle, '/events/view/'.$matchingEvent['Event']['id'], array('escape' => false, 'title' => $tooltip, 'class' => 'tt '.$registeredClass));
													if(!empty($matchingEvent['Report']['id'])) {
														$output .= ' <i class="icon-lock"></i>';
													}
												$output .= '</li>';
											}
										$output .= '</ul>';
					    			}

						    	$output .= '</td>';
						    }
						    if(($i % 7) == 6 ) {
						    	$output .= '</tr>';
						    }
						}
					$output .= '</table>';
				$output .= '</td>';
			$output .= '</tr>';
		$output .= '</table>';

		return $output;
	}

	function extractEvents($events, $date) {
		$matchingEvents = array();
		if(!empty($events)) {
			foreach($events as $event) {
				$eventDate = explode(' ', $event['Event']['time_invitation']);
				if($eventDate[0] == $date) {
					$matchingEvents[] = $event;
				}
			}
		}

		return $matchingEvents;
	}

	function extractUsersWithStatus($eventCharacters, $status) {
		$chars = array();

		if(!empty($eventCharacters)) {
			foreach($eventCharacters as $char) {
				if($char['status'] == $status) {
					$chars[] = $char;
				}
			}
		}

		return $chars;
	}

	function charactersToRoles($eventRoles, $characters, $user = null) {
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
							$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= $this->Html->image($character['Character']['Classe']['icon'], array('class' => 'tt', 'title' => $character['Character']['Classe']['title'], 'width' => '16'));
						}
						$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= ' '.$character['Character']['title'];
						$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= ' ('.(empty($character['Character']['Classe']['icon'])?$character['Character']['Classe']['title'].' ':'').$character['Character']['level'].')';
					$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '</span>';
					if(!empty($character['comment'])) {
						$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '<span class="tt" title="'.$character['comment'].'"><span class="icon-comments-alt"></span></span>';
					}
					if($user && ($user['User']['can']['manage_events'] || $user['User']['can']['full_permissions'])) {
						$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '<span class="icon-move muted pull-right"></span>';
					}
				$eventRoles['role_'.$character['raids_role_id']]['characters'][$status] .= '</li>';				
			}
		}

		return $eventRoles;
	}
}
?>
