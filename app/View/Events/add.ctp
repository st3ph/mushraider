<header>
	 
	<h1>
        <i class="icon-calendar"></i> <?php echo __('Add event');?> <?php echo __('the');?> <?php echo $this->Former->date($eventDate, 'jour');?>
       
    </h1>
</header>

<?php echo $this->Form->create('Event', array('url' => '/events/add/'.$eventDate));?>

	<h3><?php echo __('Template');?></h3>
    <?php echo $this->Form->input('Template.list', array('type' => 'select', 'options' => $tplList, 'label' => false, 'div' => null, 'empty' => __('Choose a template'), 'class' => 'form-control'));?>

	<h3><?php echo __('General informations');?></h3>
    <div class="form-group">
        <?php echo $this->Form->input('Event.title', array('type' => 'text', 'label' => __('Event title'), 'maxlength' => 50, 'class' => 'form-control'));?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('Event.game_id', array('type' => 'select', 'required' => true, 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'class' => 'form-control'));?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('Event.dungeon_id', array('type' => 'select', 'required' => true, 'label' => __('Dungeon'), 'empty' => __('Select a game first'), 'class' => 'form-control'));?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('Event.description', array('type' => 'textarea', 'label' => __('Event description'), 'class' => 'form-control wysiwyg'));?>
    </div>

   	<h3><?php echo __('Roles');?></h3>
    <?php if(!empty($rolesList)):?>
    	<?php foreach($rolesList as $roleId => $roleTitle):?>
    		<div class="form-group form-roles">
		    	<?php echo $this->Form->input('Event.roles.'.$roleId, array('type' => 'text', 'label' => __('Number of').' '.$roleTitle, 'class' => 'form-control', 'pattern' => '[0-9]{1,3}'));?>
		    </div>
    	<?php endforeach;?>
	<?php endif;?>
    <div class="form-group form-roles">
    	<?php echo $this->Form->input('Event.character_level', array('type' => 'text', 'required' => true, 'label' => __('Character minimum level'), 'class' => 'form-control'));?>
    </div>
	<h3><?php echo __('Event time');?></h3>
    <div class="form-group">
    	<?php echo $this->Form->input('Event.time_invitation', array('type' => 'time', 'timeFormat' => 24, 'interval' => 15, 'required' => true, 'label' => __('Invitations start'), 'class' => 'form-control'));?>
    </div>
    <div class="form-group">
    	<?php echo $this->Form->input('Event.time_start', array('type' => 'time', 'timeFormat' => 24, 'interval' => 15, 'required' => true, 'label' => __('Event start'), 'class' => 'form-control'));?>
    </div>

    <hr />

    <div class="form-group">		    	
    	 <?php echo $this->Form->submit(__('Create event'), array('class' => 'btn btn-success btn-large pull-right'));?>
    </div>
    <div class="clearfix"></div>
<?php echo $this->Form->end();?>