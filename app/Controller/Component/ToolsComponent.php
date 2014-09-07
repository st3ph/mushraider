<?php
class ToolsComponent extends Component {
	var $model;
	var $controller;

	public function initialize(Controller $controller) {
		$this->controller = &$controller;
	}

	/**
	 * @name noAccent
	 * @desc Return string without accent
	 * @param string $string
	 * @return  string
	 */
	public function noAccent($string){
		return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ°',
							 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY ');
	}

	public function slugMe($string, $spacer = '-') {
		return strtolower(Inflector::slug($string, $spacer));
	}

	function reverseDate($date, $sep = '/', $sepResult = '-', $time = false) {
		if(!empty($date)) {
			if($time) {
				$dates = explode(' ', $date);
				$dateExplode = explode($sep, $dates[0]);
				return $dateExplode[2].$sepResult.$dateExplode[1].$sepResult.$dateExplode[0].' '.$dates[1];
			}else {
				$dateExplode = explode($sep, $date);
				return $dateExplode[2].$sepResult.$dateExplode[1].$sepResult.$dateExplode[0];
			}
		}
	}

    function buildCleanUrl($data) {
        if (!empty($data)) {
            $data = strtr(trim($data), '+\/?,:^#','        ');
            $tab = explode(' ',$data);
            $data = implode('+',$tab);
        } else {
            return false;
        }
        return $data;
    }

    /**
    * @name : genere_password
    * @desc : Gen a password without : I, L, 0, O , 1
    * @params
    * @length : password length
    * @return string
    **/
    function genere_password($length){
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $pos = rand(0, strlen($chars)-1);
            $string .= $chars{$pos};
        }
        return $string;
    }

	function diff_date($startDate, $endDate = null, $type = 'mois') {
		$endDate = ($endDate)?$endDate:date('Y-m-d');
		// Parse dates for conversion
		$startArry = date_parse($startDate);
        $endArry = date_parse($endDate);
		// Convert dates to Julian Days
        $start_date = gregoriantojd($startArry["month"], $startArry["day"], $startArry["year"]);
        $end_date = gregoriantojd($endArry["month"], $endArry["day"], $endArry["year"]);

		if($type == 'annees') {
			$annees = ($end_date - $start_date) / 365;
			if($annees < 1) {
				$mois = round(round(($end_date - $start_date), 0) / 30);
				if($mois >= 1) {
					return $mois.' mois';
				}else {
					return 'moins d\'un mois';
				}
			}else {
				$wordingAnnees = $annees > 1?'ans':'an';
				$retour = floor($annees).' '.$wordingAnnees;
				$arrondi = floor($annees);
				if($arrondi < $annees) {
					$jours = ($annees - $arrondi) * 365;
					$mois = round(($jours / 30), 0);
					$retour .=' et '.$mois.' mois';
				}
				return $retour;
			}
		}elseif($type == 'mois') {
			$mois = round(round(($end_date - $start_date), 0) / 30);
			if($mois > 0) {
				return $mois.' mois';
			}else {
				$jours = $end_date - $start_date;
				$wording = $jours > 1?'jours':'jour';
				return $jours > 0?$jours.' '.$wording:false;
			}
		}elseif($type == 'semaines') {
			$semaines = floor(($end_date - $start_date) / 7);
			$wordingSemaine = $semaines > 1?'semaines':'semaine';
			return $semaines.' '.$wordingSemaine;
		}elseif($type == 'jours') {
			$jours = $end_date - $start_date;
			$wording = $jours > 1?'jours':'jour';
			return $jours > 0?$jours.' '.$wording:false;
		}
	}

	function get_unity_per_year($type= 'month',$date_start, $date_end) {
        list($y1,$m1) = explode('-', $date_start);
        list($y2,$m2) = explode('-', $date_end);
        $years_days = array();
        for($m = $m1; $m <= $m2; $m++) {
            $month_days[] = date('t', mktime(0, 0, 0, 1, 1, $m));
        }
        // Type de retour : mois ou jour
        if ($type == 'month') { // mois
            $nbmois = count($month_days) - 1;
            return $nbmois;
        } elseif ($type == 'day') { // jour
            return round(array_sum($month_days) / count($month_days), 2);
        } else { // autre
            return round(array_sum($month_days) / count($month_days), 2);
        }
    }

    function br2nl($input) {
		return preg_replace('/<br(\s+)?\/?>/i', "\n", $input);
	}
    
    /*
    @name in_arrayi
    @desc case insensitive in_array
    @params string $needle the string to search for
    @params array $haystack the array to search in
    */
    function in_arrayi($needle, $haystack) {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }

    /*
	* @name removeUrl
	* @desc construct url without a parameter
	* @param array $paramsArray params array to remove
	* @return string
	*/
	function removeUrl($paramsArray) {
		$url = $this->controller->request->here;
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

    /*
    * @name removeSubdir
    * @desc return current path without the install's subdir
    * @return string
    */
    function here() {
        return str_replace($this->controller->request->webroot, '/', $this->controller->request->here);
    }

    /*
    * @name quoteArray
    * @desc return array with all the values quoted
    * @param array $a
    * @return string
    */
    function quoteArray($a) {
        if(!empty($a)) {
            foreach($a as $key => $value) {
                $a[$key] = '"'.$value.'"';
            }
        }
        return $a;
    }

    /*
    * @name paramsToUrl
    * @desc return params string
    * @param array $params
    * @return string
    */
    function paramsToUrl($params) {
        $url = '';
        foreach($params as $key => $value) {
            $url .= '/'.$key.':'.$value;
        }

        return $url;
    }

    /*
    * @name extractIds
    * @desc return list of ids with id field as key
    * @param array $arValues
    * @return string
    */
    function extractIds($arValues) {
        $ids = array();

        if(!empty($arValues)) {
            foreach($arValues as $arValue) {
                if(isset($arValue['id'])) {
                    $ids[$arValue['id']] = true;
                }
            }
        }

        return $ids;
    }
}