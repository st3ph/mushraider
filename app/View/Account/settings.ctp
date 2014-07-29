<header>
    <h1><i class="icon-gears"></i> <?php echo __('My settings');?></h1>
</header>

<h3><?php echo __('Email notifications');?></h3>
<?php echo $this->Form->create('User', array('url' => '/account/settings', 'class' => ''));?>
	
    <?php echo $this->Form->input('User.notifications_cancel', array('type' => 'checkbox',  'label' => __('Receive email notifications when an event is cancelled')));?>
    <?php echo $this->Form->input('User.notifications_new', array('type' => 'checkbox', 'label' => __('Receive email notifications when an event is created')));?>
    <?php echo $this->Form->input('User.notifications_validate', array('type' => 'checkbox', 'label' => __('Receive email notifications when I\'m validated to an event')));?>
   	<?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-warning'));?>
<?php echo $this->Form->end();?>
