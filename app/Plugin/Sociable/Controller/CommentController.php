<?php
class CommentController extends SociableAppController {
	public $components = array('Emailing');
	var $helpers = array();

	public function beforeRender() {
		parent::beforeRender();

		Configure::write('debug', 2);
		$this->autoRender = false;
	}

	/*
	* @name ajouter
	* @spectre ajax
	* @desc ajoute un  commentaire
	* @param int $idModel id du "model" commenté
	* @param string $nomModel nom du model commenté
	* @return string
	*/
	public function index() {
		$nomModel = $this->request->data['m'];
		$commentaire = nl2br(strip_tags($this->request->data['c']));
		$idModel = $this->request->data['id'];

		if(!empty($idModel) && !empty($nomModel) && !empty($commentaire)) {
			// On importe le model
			App::uses($nomModel, 'Model');
			$Modele = new $nomModel();
			// Si ce modèle peut être commenté
			if(in_array('Sociable.Commentable', $Modele->actsAs) || !empty($Modele->actsAs['Sociable.Commentable'])) {
				$sessionUserId = $Modele->Behaviors->Commentable->__settings[$Modele->alias]['sessionUserId'];
				$otherSessionUserId = $Modele->Behaviors->Commentable->__settings[$Modele->alias]['otherSessionUserId'];
				$idUser = $this->Session->check($sessionUserId)?$this->Session->read($sessionUserId):null;
				if(!$idUser) {
					$idUser = $this->Session->check($otherSessionUserId)?$this->Session->read($otherSessionUserId):null;
				}

				echo $Modele->comment($idModel, $idUser, $commentaire, $this->Emailing);
			}
		}
		exit;
    }

    public function delete() {
    	$id = $this->request->data['id'];

    	App::uses('Comment', 'Model');
		$Model = new Comment();

		$Model->delete($id);

    	exit;
    }
}