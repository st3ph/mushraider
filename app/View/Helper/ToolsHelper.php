<?php
App::uses('AppHelper', 'View/Helper');
class ToolsHelper extends AppHelper {
	var $helpers = array ('Html', 'Paginator');
	var $jour_semaine = array('0'=>'Dimanche','1'=>'Lundi','2'=>'Mardi','3'=>'Mercredi','4'=>'Jeudi','5'=>'Vendredi','6'=>'Samedi');
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

	public function slugMe($string) {
		return strtolower(Inflector::slug($string,'-'));
	}

	public function date($timestamp, $format = 'd/m/Y') {
		return date($format, $timestamp);
	}

	public function niceDate($date, $preciseDate = false) {
		$dates = explode(' ', $date);
		$jours = explode('-', $dates[0]);
		$heures = explode(':', $dates[1]);

		if($date > date('Y-m-d H:i:s')) {
			$date_a = new DateTime(date('Y-m-d H:i:s'));
			$date_b = new DateTime($date);
			$interval = date_diff($date_a, $date_b);
			$intervalFormat = explode(':', $interval->format('%d:%h:%I'));

			$str = $intervalFormat[0] > 0?$intervalFormat[0].' '.__('day').($intervalFormat[0] > 1?'s':'').' ':'';
			$str .= $intervalFormat[1] > 0?$intervalFormat[1].'h':'';
			$str .= $intervalFormat[0] == 0 && $intervalFormat[2] > 0?$intervalFormat[2]:'';

			if($preciseDate && $dates[0] == date("Y-m-d")) {
				$str .= ' ('.__('Today at').' '.$heures[0].'h'.$heures[1].')';
			}elseif($preciseDate) {
				$str .= ' ('.$date_b->format('d/m/Y').')';
			}

			return __('in').' '.$str;
		}elseif($dates[0] == date("Y-m-d")) {
			$text = __('Today at');
			return $text.' '.$heures[0].'h'.$heures[1];
		}elseif($dates[0] == date('Y-m-d', time() - 3600 * 24)) {
			$text = __('Yesterday at');
			return $text.' '.$heures[0].'h'.$heures[1];
		}else {
			$wording = $this->jour_semaine_court[date('w', mktime(0, 0, 0, $jours[1], $jours[2], $jours[0]))].' ';
			$wording .= $jours[2].' '.(strtolower($this->month[intval($jours[1])])).' '.$jours[0];
			return $wording;
		}
	}

	function constructDate($fields = null, $sep = '/') {
		$date = '';
		if(!empty($fields['year'])) {
			$date = $fields['year'].$date;
		}
		if(!empty($fields['month'])) {
			$date = $fields['month'].(!empty($date)?$sep:'').$date;
		}
		if(!empty($fields['day'])) {
			$date = $fields['day'].(!empty($date)?$sep:'').$date;
		}

		return $date;
	}

    function get_timestamp($date, $full = false) {
    	$h = $i = $s = 0;
    	if($full) {
    		$dates = explode(' ', $date);
    		$date = $dates[0];
    		list($h, $i, $s) = explode(':', $dates[1]);
    	}
        list($y, $m, $d) = explode('-', $date);
        return mktime($h, $i, $s, $m, $d, $y);
    }

	function pagination($modele, $showCurrent = false) {
		if(isset($this->Paginator->params['paging'])) {
			if($this->Paginator->params['paging'][$modele]['pageCount'] > 1) {
				$html = '';
				$html .= '<div class="pagination"><ul>';
				$options = array();

				if($this->params['controller'] == 'univers' && !empty($this->params['universSlug'])) {
					$this->params['controller'] = $this->params['universSlug'];
				}elseif($this->params['controller'] == 'user' && !empty($this->params['pseudo'])) {
					$this->params['action'] = $this->params['pseudo'];
				}

				if(isset($this->params['pass'])) {
					foreach($this->params['pass'] as $cle => $valeur) {
						if($cle > 0) {
							$options[$cle] =  urlencode($valeur);
						}
					}
				}

				if (!empty($this->params['named'])) {
					foreach($this->params['named'] as $cle => $valeur) {
						$options[$cle] =  urlencode($valeur);
					}
				}

				$this->Paginator->options(array('url' => $options));

				$firstLink = $this->Paginator->first('&lsaquo;&lsaquo;', array('class' => 'debutPage', 'escape' => false));
				$lastLink = $this->Paginator->last('&rsaquo;&rsaquo;', array('class' => 'finPage', 'escape' => false));
				$html .= !empty($firstLink)?'<li class="first">'.$firstLink.'</li>':'';
				$html .= '<li class="prev">'.$this->Paginator->prev('&laquo; '.__('Pr&eacute;c&eacute;dente'), array('class' => 'pagePrec', 'rel' => 'prev', 'escape' => false), null, array('class' => 'disabled', 'escape' => false)).'&nbsp;</li>';
				$html .= '<li class="pages">'.$this->Paginator->numbers(array('separator' => '', 'modulus' => 7, 'currentClass' => 'active')).'</li>';
				$html .= '<li class="next">'.$this->Paginator->next(__('Suivante').' &raquo;', array('class' => 'pageSuiv', 'rel' => 'next', 'escape' => false), null, array('class' => 'disabled', 'escape' => false)).'</li>';
				$html .= !empty($lastLink)?'<li class="last">'.$lastLink.'</li>':'';
				if($showCurrent) {
					$html .= '<div class="currentPage">'.$this->Paginator->counter(array('format' => 'Page <span>{:page}</span> / {:pages}')).'</div>';
				}
				$html .= '</ul></div>';
				return $html;
			}
		}
	}
    
    function mPagination($modele) {
		if(isset($this->Paginator->params['paging'])) {
			if($this->Paginator->params['paging'][$modele]['pageCount'] > 1) {
				$html = '';
				$html .= '<div id="pagination">';
                    $options = array();
                    if(isset($this->params['pass'])) {
                        foreach($this->params['pass'] as $cle => $valeur) {
                            if($cle > 0) {
                                $options[$cle] =  urlencode($valeur);
                            }
                        }
                    }

                    if (!empty($this->params['named'])) {
                        foreach($this->params['named'] as $cle => $valeur) {
                            $options[$cle] =  urlencode($valeur);
                        }
                    }
                    $this->Paginator->options(array('url' => $options));
                    $this->Paginator->options['url']['controller'] = 'c';
                    $this->Paginator->options['url']['action'] = $this->Paginator->params['pass'][0];
                    $html .= $this->Paginator->prev('Précédent', array('class' => 'pagePrec', 'data-role' => 'button', 'data-icon' => 'arrow-l', 'data-inline' => 'true', 'escape' => false), null, array('class' => 'disabled', 'escape' => false));
                    $html .= $this->Paginator->next('Suivant', array('class' => 'pageSuiv', 'data-role' => 'button', 'data-icon' => 'arrow-r', 'data-inline' => 'true', 'data-iconpos' => 'right', 'escape' => false), null, array('class' => 'disabled', 'escape' => false));
				$html .= '</div>';
				return $html;
			}
		}
	}

	/*
	* @name rand
	* @desc fait un random en prenant en compte une liste de nombre à ignorer
	* @param int $min nombre minimal
	* @param int $max nombre maximal
	* @param array $ignore tableau des nombres à ignorer
	* @return string
	*/
	function rand($min, $max, $ignore = array()) {
		$rand = rand($min, $max);
		if(in_array($rand, $ignore)) {
			$ignore[] = $rand;
			$rand = $this->rand($min, $max, $ignore);
		}
		return $rand;
	}

	/*
	* @name extractTag
	* @desc extrait un tag d'un texte et retourne sous forme de tableau le texte sans le tag et le tag trouvé
	* @param string $texte texte à analyser
	* @param string $tagStart début du tag à trouver
	* @param string $tagEnd fin du tag à trouver
	* @return array
	*/
	function extractTag($texte, $tagStart, $tagEnd) {
		$tagStart = addcslashes($tagStart, "?/<>");
		$tagEnd = addcslashes($tagEnd, "?/<>");
		$output = array();
		if(preg_match('/('.$tagStart.'.*'.$tagEnd.')/', $texte, $matches)) {
			$output['texte'] = preg_replace('/'.addcslashes($matches[1], "?/<>").'/', '', $texte, 1);
			$output['tag'] = $matches[1];
		}

        return $output;
	}

	function buildRssUrl() {
		$i = 0;
		$params = !empty($this->params['named'])?'?':'';
		foreach($this->params['named'] as $paramName => $paramValue) {
			$params .= ($i?'&':'').$paramName.'='.$paramValue;
			$i++;
		}
		return $params;
	}
    
    /*
	* @name clickToCall
	* @desc format un téléphone en lien click to call
	* @param string $tel numéro de téléphone
	* @return string
	*/
	function clickToCall($tel) {
		$telToCall = str_replace(' ', '', $tel);
		$telToCall = str_replace('.', '', $telToCall);
        if($telToCall[0] == '0') {
            $telToCall = substr($telToCall, 1);
            $telToCall = '+33'.$telToCall;
        }
        
		//return $this->Html->link($tel, , array('title' => 'Click to call', 'rel' => 'external'));
		return '<a href="tel:'.$telToCall.'" rel="external" title="Click to call">'.$tel.'</a>';
	}

	/*
	* @name buildUrl
	* @desc construit une url avec un paramètre donné
	* @param array $paramsArray tableau de param à ajouter
	* @param string $title titre du lien
	* @param string $activeClass class si lien actif
	* @return string
	*/
	function buildUrl($paramsArray, $title = '', $forceActive = false, $activeClass = 'active') {
		$url = $this->here();
		$urlDecoded = urldecode($url);
		$directionClass = '';

		$chunks = explode('/', $url);
		if(!empty($chunks) && !empty($paramsArray)) {
			foreach($paramsArray as $keyParam => $valueParam) {
				$founded = false;
				foreach($chunks as $key => $chunk) {
					$explode = explode(':', $chunk);
					if(strtolower($explode[0]) == strtolower($keyParam) && !empty($explode[1])) {
						$chunks[$key] = $keyParam.':'.$valueParam;
						$founded = true;
						break;
					}
				}

				if(!$founded) {
					array_push($chunks, ($keyParam.':'.$valueParam));
				}

				// Direction class
				$directionClass = $keyParam == 'direction'?$valueParam:'';
			}
		}

		$url = implode('/', $chunks);

		if(!empty($title)) {
			$active = true;
			foreach($paramsArray as $keyParam => $valueParam) {
				if(!preg_match('/(\/'.$keyParam.':'.$valueParam.'\/?)/i', $urlDecoded)) {
					$active = false;
				}
			}
			
			$active = $forceActive?true:$active;
			$output = $this->Html->link($title, $url, array('class' => ($active?$activeClass:'').' '.$directionClass, 'escape' => false, 'title' => $title));
		}else {
			$output = $url;
		}

        return $output;
	}

	/*
	* @name removeUrl
	* @desc construit une url sans un paramètre donné
	* @param array $paramsArray tableau de param à supprimer
	* @return string
	*/
	function removeUrl($paramsArray) {
		$url = $this->here();
		$chunks = explode('/', $url);

		if(!empty($chunks)) {
			foreach($chunks as $key => $chunk) {
				$explode = explode(':', $chunk);
				foreach($paramsArray as $deleteParam) {
					if(strtolower($explode[0]) == strtolower($deleteParam) && !empty($explode[1])) {
						unset($chunks[$key]);
					}
				}
			}
		}

		$newurl = implode('/', $chunks);
		$newurl = !empty($newurl)?$newurl:'/';

        return $newurl;
	}

	function getCanonical($request = null) {
		$url = rtrim(Configure::Read('Serveur.site'), "/");
		// Url change only for FR
		$subdomain = strtolower(substr(env("HTTP_HOST"), 0, strpos(env("HTTP_HOST"), ".")));
		if($subdomain == 'fr') {
			$url = rtrim(Configure::Read('Serveur.canonical'), "/");
		}

		return $url.$request;
	}

	/*
	* @name getRegisteredCharacter
	* @desc return registered character to an event
	* @param int $userId id user
	* @param array EventsCharacter(s)
	* @return mix
	*/
	function getRegisteredCharacter($userId, $eventsCharacters) {
		if(!empty($eventsCharacters)) {
			foreach($eventsCharacters as $eventsCharacter) {
				if($eventsCharacter['user_id'] == $userId) {
					$eventsCharacter['Character']['raids_role_id'] = $eventsCharacter['raids_role_id'];
					$eventsCharacter['Character']['status'] = $eventsCharacter['status'];
					$eventsCharacter['Character']['comment'] = $eventsCharacter['comment'];
					return $eventsCharacter['Character'];
				}
			}
		}

		return false;
	}

	/*
	* @name getAvailableCharacter
	* @desc return available character(s) to an event
	* @param int $userId id user
	* @param array $event
	* @return mix
	*/
	function getAvailableCharacter($user, $event) {
		if(!empty($user['Character']) && !empty($event)) {
			$characters = array();
			foreach($user['Character'] as $character) {
				if($character['level'] >= $event['Event']['character_level'] && $character['game_id'] >= $event['Event']['game_id']) {
					$characters[] = $character;
				}
			}

			return $characters;
		}

		return false;
	}

	/*
    * @name removeSubdir
    * @desc return current path without the install's subdir
    * @return string
    */
    function here() {
        return str_replace($this->webroot, '/', $this->here);
    }

    /*
    * @name getProtocol
    * @desc return current protocol
    * @return string
    */
    function getProtocol() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	    return $protocol;
    }
}
?>
