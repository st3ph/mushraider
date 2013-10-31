<header>
    <h2><i class="icon-lock"></i> <?php echo __('Login');?></h2>
</header>

<?php echo $this->Form->create('User', array('url' => '/auth/login', 'class' => ''));?>
    <div class="row-fluid">
        <?php echo $this->Form->input('User.login', array('type' => 'text', 'required' => true, 'label' => false, 'placeholder' => __('Username or Email'), 'class' => 'span12'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('User.password', array('type' => 'password', 'required' => true, 'label' => false, 'placeholder' => __('Password'), 'class' => 'span12'));?>
    </div>            
    <div class="row-fluid">
        <?php echo $this->Form->submit(__('Login'), array('class' => 'btn btn-info span12'));?>
    </div>
<?php echo $this->Form->end();?>    
<div class="footerLinks">
    <?php echo $this->Html->link(__('Password lost :('), '/auth/recovery', array('class' => '', 'title' => __('Recover your password')));?>
    <?php echo $this->Html->link(__('Signup'), '/auth/signup', array('class' => 'pull-right signup', 'title' => __('Signup and start raiding')));?>
</div>