<header>
    <h1><i class="icon-user"></i> <?php echo __('My Profile');?></h1>
</header>

<div class="row">
	<div class="span6">
		<h3><?php echo __('Edit you personnal informations');?></h3>
        <?php echo $this->Form->create('User', array('url' => '/account', 'class' => ''));?>
            <div class="row-fluid">
                <?php echo $this->Form->input('User.currentpassword', array('type' => 'password', 'required' => true, 'label' => __('Current password'), 'class' => 'span5'));?>
            </div>
            <div class="row-fluid">
                <?php echo $this->Form->input('User.newpassword', array('type' => 'password', 'required' => true, 'label' => __('New password'), 'class' => 'span5', 'pattern' => '.{6,}'));?>
            </div>
            <div class="row-fluid">
                <?php echo $this->Form->input('User.newpassword2', array('type' => 'password', 'required' => true, 'label' => __('Confirm new password'), 'class' => 'span5', 'pattern' => '.{6,}'));?>
            </div> 
            <div class="row-fluid">
                <div class="span2">
                    <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-warning'));?>
                </div>
            </div>
        <?php echo $this->Form->end();?>		
	</div>
	<div class="span5">
		<h3><?php echo __('Manage your characters');?></h3>
		<div class="manageCharacters">
			<p><i class="icon-chevron-down"></i> <?php echo __('Click the button below below to manager your characters');?> <i class="icon-chevron-down"></i></p>
			<?php echo $this->Html->link('<i class="icon-shield"></i>', '/account/characters', array('class' => 'btn btn-large btn btn-warning', 'escape' => false));?>
		</div>
	</div>
</div>