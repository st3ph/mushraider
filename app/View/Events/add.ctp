<header>
	<h1>
        <div class="row">
            <div class="span8">
                <i class="icon-calendar"></i> <?php echo __('Add event');?> <?php echo __('the');?> <?php echo $this->Former->date($eventDate, 'jour');?>
            </div>
            <div class="pull-right text-right  span3">
                <?php if(!empty($tplList)):?>
                    <div class="pull-right">
                        <?php echo $this->Html->link('<i class="icon-download"></i> '.__('Load template'), '/events/add', array('id' => 'loadTemplate', 'class' => 'btn btn-mini', 'escape' => false));?>
                        <span id="tplList"><?php echo $this->Form->input(false, array('type' => 'select', 'options' => $tplList, 'id' => 'TemplateList', 'label' => false, 'div' => null, 'empty' => __('Choose a template')));?></span>
                    </div>       
                <?php endif;?> 
            </div>
        </div>
    </h1>
</header>

<?php echo $this->Form->create('Event', array('url' => '/events/add/'.$eventDate));?>
	<h3><?php echo __('General informations');?></h3>
    <div class="row">
        <div class="span5">
            <div class="form-group">
                <?php echo $this->Form->input('Event.title', array('type' => 'text', 'label' => __('Event title'), 'maxlength' => 50, 'class' => 'span4'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Event.game_id', array('type' => 'select', 'required' => true, 'label' => __('Game'), 'options' => $gamesList, 'data-error' => __('An error occur while loading'), 'empty' => '', 'class' => 'span4'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Event.dungeon_id', array('type' => 'select', 'required' => true, 'label' => __('Dungeon'), 'empty' => __('Select a game first'), 'class' => 'span4'));?>
            </div>
        </div>
        <div class="span6">            
            <div class="form-group">
                <?php echo $this->Form->input('Event.description', array('type' => 'textarea', 'label' => __('Event description'), 'class' => 'span5 wysiwyg'));?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="span6">
           	<h3><?php echo __('Roles');?></h3>
            <?php if(!empty($rolesList)):?>
            	<?php foreach($rolesList as $roleId => $roleTitle):?>
            		<div class="form-group form-roles">
        		    	<?php echo $this->Form->input('Event.roles.'.$roleId, array('type' => 'text', 'label' => __('Number of').' '.$roleTitle, 'class' => 'span1', 'pattern' => '[0-9]{1,3}'));?>
        		    </div>
            	<?php endforeach;?>
        	<?php endif;?>
            <div class="form-group form-roles">
            	<?php echo $this->Form->input('Event.character_level', array('type' => 'text', 'required' => true, 'label' => __('Character minimum level'), 'class' => 'span1'));?>
            </div>
        </div>
        <div class="span5">
        	<h3><?php echo __('Event time');?></h3>
            <div class="form-group">
            	<?php echo $this->Form->input('Event.time_invitation', array('type' => 'time', 'timeFormat' => 24, 'interval' => 15, 'required' => true, 'label' => __('Invitations start'), 'class' => 'span2'));?>
            </div>
            <div class="form-group">
            	<?php echo $this->Form->input('Event.time_start', array('type' => 'time', 'timeFormat' => 24, 'interval' => 15, 'required' => true, 'label' => __('Event start'), 'class' => 'span2'));?>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="span3 pull-right">
            <div class="form-group">
            	<?php echo $this->Form->submit(__('Create event'), array('class' => 'btn btn-success btn-large pull-right'));?>
            </div>
        </div>
        <div class="span3 pull-right eventTemplate">
            <?php echo $this->Form->input('Event.template', array('type' => 'checkbox', 'label' => __('create a template based on this event'), 'class' => 'zspan2'));?>
            <?php echo $this->Form->input('Event.templateName', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'tplName', 'placeholder' => __('template name')));?>
        </div>
    </div>
    <div class="clearfix"></div>
<?php echo $this->Form->end();?>