<div class="box dark">
	<header>
		<div class="icons"><i class="icon-gamepad"></i></div>
		<h5><?php echo __('Add game');?></h5>
	</header>
	<div class="accordion-body body in collapse">
		<?php echo $this->Form->create('Game', array('url' => '/admin/games/add', 'class' => 'span12', 'enctype' => 'multipart/form-data'));?>
			<h3><?php echo __('Général Informations');?></h3>
		    <div class="form-group">
		        <?php echo $this->Form->input('Game.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
		    </div>
		    <div class="form-group">
		        <?php echo $this->Form->input('Game.logo', array('type' => 'file', 'label' => __('Logo').' (64px / 64px)', 'class' => 'span5 imageupload'));?>
		        <div id="previewcanvascontainer">
    				<canvas id="previewcanvas" class="w64"></canvas>
				</div>
		    </div>

		    <h3><?php echo __('Dungeons');?></h3>
		    <div class="form-group">
		        <?php echo $this->Form->input('Game.dungeons', array('type' => 'select', 'label' => __('Select existing one'), 'options' => $dungeonsList, 'empty' => '', 'class' => 'span5 selectFiller', 'data-list' => 'dungeonsList', 'data-field' =>  'dungeons'));?>
		        <?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('or create new one'), '', array('class' => 'addObjectToGame', 'data-controller' => 'dungeons', 'data-list' => 'dungeonsList', 'data-field' =>  'dungeons', 'escape' => false));?>
		        <ul class="unstyled gameFilledList" id="dungeonsList">

        		</ul>
		    </div>

		    <h3><?php echo __('Classes');?></h3>
		    <div class="form-group">
		        <?php echo $this->Form->input('Game.classes', array('type' => 'select', 'label' => __('Select existing one'), 'options' => $classesList, 'empty' => '', 'class' => 'span5 selectFiller', 'data-list' => 'classesList', 'data-field' =>  'classes'));?>
		        <?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('or create new one'), '', array('class' => 'addObjectToGame', 'data-controller' => 'classes', 'data-list' => 'classesList', 'data-field' =>  'classes', 'escape' => false));?>
		        <ul class="unstyled gameFilledList" id="classesList">

        		</ul>
		    </div>

		    <h3><?php echo __('Races');?></h3>
		    <div class="form-group">
		        <?php echo $this->Form->input('Game.Races', array('type' => 'select', 'label' => __('Select existing one'), 'options' => $racesList, 'empty' => '', 'class' => 'span5 selectFiller', 'data-list' => 'racesList', 'data-field' =>  'races'));?>
		        <?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('or create new one'), '', array('class' => 'addObjectToGame', 'data-controller' => 'races', 'data-list' => 'racesList', 'data-field' =>  'races', 'escape' => false));?>
		        <ul class="unstyled gameFilledList" id="racesList">

        		</ul>
		    </div>

		    <div class="form-group">		    	
		    	 <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>		    	
		    </div>
		<?php echo $this->Form->end();?>
	</div>
</div>