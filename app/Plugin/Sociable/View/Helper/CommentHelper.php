<?php
App::uses('AppHelper', 'View/Helper');
class CommentHelper extends AppHelper { 
    var $helpers = array ('Html','Form');

    var $init_done = false;

    var $options= array(
        'connected' => true, // booléen
        'selector' => '#ajoutCommentaire', // selecteur du formulaire pour les commentaires
        'url' => '/sociable/comment', // url à appeler en ajax
        'maxComments' => 10, // Nombre de commentaires par page
        'maxLinks' => 5, // Nombre de liens vers les pages
        'defaultPseudo' => 'Invité'
    );

    function initialisation() {
        if($this->init_done) {
            return;
        }       
        $this->options['defaultPseudo'] = __('Guest');

        // Test if webroot is a subdir
        $this->options['url'] = $this->request->base.$this->options['url'];

        // On place le js requis        
        $this->Html->script('/sociable/js/comment', array('inline' => false, 'block' => 'scriptBottom'));

        // On place le css requis
        $this->Html->css('/sociable/css/comment', null, array('inline' => false));

        // Initialisation du javascript
        $selector = $this->options['selector'];
        $script = "$(function() {";
        // Si on est connecté on active la fonction de commentaire
        if($this->options['connected']) {
            $script .= "$('{$selector}').comment({url:'{$this->options['url']}', maxComments:'{$this->options['maxComments']}', maxLinks:'{$this->options['maxLinks']}'});";
        }
        $script .= "});";
        $this->Html->scriptBlock($script, array('inline' => false, 'block' => 'scriptBottom'));

        // Initialisation faîte, on ne fera plus d'appel à cette dernière
        $this->init_done = true;
    }

    //
    function show($datas, $nomModel, $options = array()) {
        $idModel = $datas[$nomModel]['id'];
        $comments = isset($datas[$nomModel]['comments'])?$datas[$nomModel]['comments']:array();

        $this->options = array_merge($this->options, $options);
        $this->initialisation();

        // On affiche le commentaire
        $output = '<div id="commentaires">';
            $output .= '<h4>'.__('Add comment').'</h4>';
            $output .= $this->Form->create('', array('url' => '/commentaire/ajouter', 'id' => 'ajoutCommentaire'));
			    $output .= '<div class="row-fluid">';
			        $output .= '<div class="span8">';
			            $output .= $this->Form->input('Commentaire.commentaire', array('id' => 'commentaire', 'type' => 'textarea', 'label' => false, 'div' => false));
			            $output .= $this->Form->input('Commentaire.idModel', array('type' => 'hidden', 'value' => $idModel, 'label' => false, 'div' => false));
			            $output .= $this->Form->input('Commentaire.nomModel', array('type' => 'hidden', 'value' => $nomModel, 'label' => false, 'div' => false));
			        $output .= '</div>';
			    $output .= '</div>';
	            $output .= '<div class="row-fluid">';
	                $output .= '<div class="span8">';
	                    $output .= '<div class="span8">';
	                        $output .= '<div class="compteur"><span>0</span> / 1000 '.__('chars used').'</div>';
	                    $output .= '</div>';
	                    $output .= '<div class="span4">';
	                        $output .= $this->Form->submit(__('Send'), array('class' => 'btn btn-primary pull-right'));
	                    $output .= '</div>';
	                $output .= '</div>';
	            $output .= '</div>';
            $output .= $this->Form->end();



            $nbComments = !empty($comments)?count($comments):0;

            $output .= '<h4 class="second"><span class="nbComments">'.$nbComments.'</span> '.__('comment').($nbComments > 1?'s':'').'</h4>';
            $output .= '<ul class="listeCommentaires unstyled easyPaginate span8">';
                if(!empty($comments)) {                 
                    foreach($comments as $comment) {                        
                        $dates = explode(' ', $comment['Comment']['created']);
                        $jours = explode('-', $dates[0]);
                        $heures = explode(':', $dates[1]);
                        $output .= '<li rel="commentaire:'.$comment['Comment']['id'].'">';
                            $output .= '<div class="auteur">'.__('The').' '.$jours[2].'/'.$jours[1].'/'.$jours[0].' '.__('at').' '.$heures[0].'h'.$heures[1].' '.__('by').' <span>'.$comment['User']['username'].'</span></div>';                           
                            $output .= '<div class="texte">'.$comment['Comment']['comment'].'</div>';

                            // Edit
                            if($this->options['connected'] && !empty($comment['User']) && $this->options['connected']['User']['id'] == $comment['User']['id']) {
                                $output .= '<div class="edit_comment">';
                                    $output .= '<i class="fa fa-pencil-square-o edit" title="'.__('Edit').'"></i>';
                                    $output .= '<span>';
                                        $textSignal = __('reported');
                                        $output .= $this->Html->link(__('save'), '', array('title' => __('Save'), 'class' => 'save', 'rel' => $datas[$nomModel]['id'].'|'.$comment['Comment']['id'])).' '.$this->Html->link(__('cancel'), '', array('title' => __('Cancel'), 'class' => 'cancel'));
                                    $output .= '</span>';
                                $output .= '</div>';
                            }

                            // Abus
                            $output .= '<div class="signaler_abus">';
                                $output .= '<i class="fa fa-exclamation-triangle signaler" title="'.__('Report spam').'"></i>';
                                $output .= '<span>'.__('Are you sure ?').' ';
                                    $textSignal = __('reported as spam');
                                    $output .= $this->Html->link(__('yes'), '', array('title' => __('Report spam'), 'class' => 'signaler_oui', 'rel' => $datas[$nomModel]['id'].'|'.$comment['Comment']['id'].'|commentaire_video|'.$textSignal)).' '.$this->Html->link(__('no'), '', array('title' => __('Don\'t report spam'), 'class' => 'signaler_non'));
                                $output .= '</span>';
                            $output .= '</div>';

                            // Delete
                            $output .= '<div class="delete">';
                                $output .= '<i class="fa fa-times" title="'.__('Delete').'"></i>';
                                $output .= '<span>'.__('Are you sure ?').' ';
                                    $output .= $this->Html->link(__('yes'), '', array('title' => __('Delete'), 'class' => 'validate', 'data-id' => $comment['Comment']['id'])).' '.$this->Html->link(__('no'), '', array('title' => __('Don\'t delete'), 'class' => 'cancel'));
                                $output .= '</span>';
                            $output .= '</div>';
                        $output .= '</li>';
                    }
                }
            $output .= '</ul>';
            $output .= '<div class="clearfix"></div>';
        $output .= '</div>';
        echo $output;
    }
}
?>
