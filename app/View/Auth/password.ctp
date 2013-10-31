<header>
    <h1><i class="icon-ambulance"></i> <?php echo __('Recovery');?></h1>
</header>

<?php echo $this->Form->create('User', array('url' => '/auth/password', 'class' => ''));?>
    <div class="row-fluid">
        <?php echo $this->Form->input('User.password', array('type' => 'password', 'required' => true, 'label' => false, 'placeholder' => __('Enter your new password'), 'class' => 'span12', 'autocomplete' => 'off'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('User.verify_password', array('type' => 'password', 'required' => true, 'label' => false, 'placeholder' => __('Confirm your new password'), 'class' => 'span12', 'autocomplete' => 'off'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->submit(__('Change my password'), array('class' => 'btn btn-info span12'));?>
    </div>
<?php echo $this->Form->end();?>
<div class="footerLinks">
    <?php echo $this->Html->link(__('Login'), '/auth/login', array('class' => '', 'title' => __('Login')));?>
    <?php echo $this->Html->link(__('Signup'), '/auth/signup', array('class' => 'pull-right signup', 'title' => __('Signup and start raiding')));?>
</div>