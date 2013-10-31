<?php
class CommentableBehavior extends ModelBehavior {
	protected $modelObjet; // Variable locale contenant l'objet model, utile pour les fonctions appelées en ajax

	// Configuration par défaut
	protected $_defaults = array(
	    'foreignKey' => 'model_id', // champs pour la jointure avec le model utilisateur
	    'champUtilisateur' => 'user_id', // champs pour la jointure avec le model associé
	    'champNomModel' => 'model', // champs pour la jointure avec le model associé
	    'champCommentaire' => 'comment', // champs pour la jointure avec le model associé
	    'utilisateurModel' => 'User', // alias du model utilisateur
	    'userDisplayField' => 'username', // field to display for the user
	    'sessionUserId' => 'User.id', // nom de l'id utilisateur en session
	    'otherSessionUserId' => '', // nom de l'id utilisateur en session secondaire
		'primaryField' => 'id', // champs pour la jointure avec le model associé
	    'maxChars' => 1000, // nombre maximum de caractères par commentaire
	    'autoBind' => true, // bind automatiquement le model au model utilisateur
		'aggregate' => true, // Met à jour automatiquement un champs de comptage s'il y en a un
	    'aggregateField' => 'nb_comments', // Nom du champs de comptage
	    'modelCacheName' => 'event_id_{id}' // Nom du fichier de cache
	);

	/*
	* @name setup
	* @desc initialise le behavior et créé les relations avec les modèles existants
	* @param object $Model objet du modèle actAs
	* @param array $settings tableau d'options pour surcharger $_defaults
	*/
	function setup(&$Model, $settings = array()) {
		// On merge les options définies par l'utilisateur avec celles par défaut
		if (!isset($this->__settings[$Model->alias])) {
			$this->__settings[$Model->alias] = $this->_defaults;
		}
		$this->__settings[$Model->alias] = array_merge($this->__settings[$Model->alias], (is_array($settings)?$settings:array()));

		// Si on souhaite créé les relations automatiquement
		if($this->__settings[$Model->alias]['autoBind']) {
			// Relation hasMany
			$commonHasMany = array(
				'Comment' => array('className' => 'Sociable.Comment',
									'foreignKey' => $this->__settings[$Model->alias]['foreignKey'])
									);
			// Création des relations
			$Model->bindModel(array('hasMany' => $commonHasMany), false);

			// Relation belongsTo
			$commonBelongsTo = array(
				$Model->name => array('className' => $Model->name,
									'foreignKey' => $this->__settings[$Model->alias]['foreignKey']),
				$this->__settings[$Model->alias]['utilisateurModel'] => array('className' => $this->__settings[$Model->alias]['utilisateurModel'],
																				'foreignKey' => $this->__settings[$Model->alias]['champUtilisateur'])
									);
			$Model->Comment->bindModel(array('belongsTo' => $commonBelongsTo), false);
        }
		// On sort l'objet, utile pour les fonctions appelées en ajax
		$this->modelObjet = $Model;
	}

	/*
	* @name afterFind
	* @desc récupère les commentaires pour chaque modèle que l'on a récupèré
	* @param object $Model objet du modèle actAs
	* @param array $results résultats du find
	* @param bool $primary
	*/
	function afterFind(&$Model, $results, $primary = false) {
		if(count($results) >= 1 && isset($results[0][$Model->alias][$Model->primaryKey])) {
			foreach($results as $key => $resultat) {				
				// On récupère touts les commentaires pour ce modèle
				$contraintes = array();
				$contraintes['recursive'] = 1;
				$contraintes['order'] = 'Comment.created DESC';
				$contraintes['conditions']['Comment.'.$this->__settings[$Model->alias]['foreignKey']] = $results[$key][$Model->alias][$Model->primaryKey];
				$contraintes['conditions']['Comment.'.$this->__settings[$Model->alias]['champNomModel']] = $Model->alias;
				$contraintes['conditions']['Comment.deleted'] = '0';
				$contraintes['contain'][$this->__settings[$Model->alias]['utilisateurModel']] = array();
				$results[$key][$Model->alias]['comments'] = $Model->Comment->find('all', $contraintes);
			}
		}
		
		return $results;
	}

	/*
	* @name comment
	* @desc ajoute un commentaire à un modèle (article ....)
	* @param object $obj objet du modèle actAs
	* @param int $idModel id du modèle à ajouter
	* @param int $idUtilisateur id de l'utilisateur
	* @return bool
	*/
	public function comment($obj, $idModel, $idUtilisateur, $commentaire) {
		$erreur = '';

		$toSave = array();
		$toSave[$this->__settings[$this->modelObjet->alias]['foreignKey']] = $idModel;
		$toSave[$this->__settings[$this->modelObjet->alias]['champUtilisateur']] = $idUtilisateur;
		$toSave[$this->__settings[$this->modelObjet->alias]['champNomModel']] = $this->modelObjet->alias;
		//$toSave[$this->__settings[$this->modelObjet->alias]['champCommentaire']] = utf8_decode($commentaire);
		$toSave[$this->__settings[$this->modelObjet->alias]['champCommentaire']] = $commentaire;

		if(strlen($toSave[$this->__settings[$this->modelObjet->alias]['champCommentaire']]) < $this->__settings[$this->modelObjet->alias]['maxChars']) {
			if($this->modelObjet->Comment->save($toSave)) {
				// On récupère les informations du commentaire que l'on vient d'enregistrer pour les passer à la vue
				$lastInsertId = $this->modelObjet->Comment->getLastInsertID();
				$com = $this->modelObjet->Comment->find('first', array('conditions' => array('Comment.id' => $lastInsertId), 'recursive' => 1));
				// On récupère l'utilisateur s'il existe
				$user = null;
				if($com['Comment'][$this->__settings[$this->modelObjet->alias]['champUtilisateur']] > 0) {
					App::uses($this->__settings[$this->modelObjet->alias]['utilisateurModel'], 'Model');					
					$UserObj = new $this->__settings[$this->modelObjet->alias]['utilisateurModel']();

					$user = $UserObj->find('first', array('conditions' => array('id' => $com['Comment'][$this->__settings[$this->modelObjet->alias]['champUtilisateur']]), 'recursive' => -1));
				}

				if($this->__settings[$this->modelObjet->alias]['aggregate']) {					
					$describe = $this->modelObjet->query('DESCRIBE '.$this->modelObjet->tablePrefix.$this->modelObjet->useTable);
					if(!empty($describe) && is_array($describe)) {
						foreach($describe as $field) {
							if($field['COLUMNS']['Field'] == $this->__settings[$this->modelObjet->alias]['aggregateField']) {
								$conditions = array();
								$conditions[$this->__settings[$this->modelObjet->alias]['foreignKey']] = $idModel;
								$conditions[$this->__settings[$this->modelObjet->alias]['champNomModel']] = $this->modelObjet->alias;
								$nbCommentaires = $this->modelObjet->Comment->find('count', array('conditions' => $conditions, 'recursive' => -1));

								$toSaveAggregate = array();
								$toSaveAggregate[$this->__settings[$this->modelObjet->alias]['primaryField']] = $idModel;
								$toSaveAggregate[$this->__settings[$this->modelObjet->alias]['aggregateField']] = $nbCommentaires;
								$this->modelObjet->save($toSaveAggregate);
							}
						}
					}
				}

				// On vide le cache
				//$cacheName = str_replace('{id}', $idModel, $this->__settings[$this->modelObjet->alias]['modelCacheName']);                
				//Cache::delete($cacheName);

				// On construit l'objet JSON pour le retour au javascript
				$json = '{';
					$json .= '"error":"",';
					$json .= '"commentaire":[';
						$json .= '{';
							$json .= '"commentaire":"'.$this->cleanCommentaire($com['Comment'][$this->__settings[$this->modelObjet->alias]['champCommentaire']]).'",';
							$json .= '"date":"'.$com['Comment']['created'].'"';
						$json .= '}';
					$json .= '],';
					$json .= '"utilisateur":[';
						$json .= '{';
							$json .= '"id":"'.$user[$this->__settings[$this->modelObjet->alias]['utilisateurModel']]['id'].'",';
							$json .= '"pseudo":"'.$user[$this->__settings[$this->modelObjet->alias]['utilisateurModel']][$this->__settings[$this->modelObjet->alias]['userDisplayField']].'"';
						$json .= '}';
					$json .= ']';
				$json .= '}';
				return $json;
			}else {
				$erreur = __('An error occur while saving. Please try again or contact site admin.');
			}
		}else {			
			$erreur = __('Your comment is too long, must be %s chars max.', $this->__settings[$this->modelObjet->alias]['maxChars']);
		}
		// Objet JSON pour le traitement d'erreurs
		$json = '{';
			$json .= '"error":"'.$erreur.'",';
			$json .= '"commentaire":[{}],';
			$json .= '"utilisateur":[{}]';
		$json .= '}';
		return $json;
	}

	function cleanCommentaire($commentaire) {
		$commentaire = preg_replace('/\n/i', "<br>", $commentaire);
		$commentaire = preg_replace('/<br(\s+)?\/?><br(\s+)?\/?>/i', "<br/>", $commentaire);
		return addslashes($commentaire);
	}
}
?>
