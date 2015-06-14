<header>
    <h1><i class="fa fa-ambulance"></i> <?php echo __('Recovery');?></h1>
</header>

<?php echo $this->Form->create('User', array('url' => '/auth/recovery', 'class' => ''));?>
    <div class="row-fluid">
        <?php echo $this->Form->input('User.email', array('type' => 'email', 'required' => true, 'label' => false, 'placeholder' => __('Email used when signup'), 'class' => 'span12', 'autocomplete' => 'off'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->submit(__('Recover my password'), array('class' => 'btn btn-info span12'));?>
    </div>
<?php echo $this->Form->end();?>
<div class="footerLinks">
    <?php echo $this->Html->link(__('Login'), '/auth/login', array('class' => '', 'title' => __('Login')));?>
    <?php echo $this->Html->link(__('Signup'), '/auth/signup', array('class' => 'pull-right signup', 'title' => __('Signup and start raiding')));?>
</div>