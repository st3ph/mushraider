<header>
    <h1><i class="fa fa-unlock-alt"></i> <?php echo __('Signup');?></h1>
</header>

<?php echo $this->Form->create('User', array('url' => '/auth/signup', 'class' => ''));?>
    <div class="row-fluid widhtHint">
        <?php $after = '<div class="hint">'.__('Letters and numbers only, 3 to 20 chars length.').'</div>';?>
        <?php echo $this->Form->input('User.username', array('type' => 'text', 'required' => true, 'label' => false, 'placeholder' => __('Username'), 'class' => 'span12', 'pattern' => '[a-zA-Z0-9_\-]{3,20}', 'after' => $after));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('User.email', array('type' => 'email', 'required' => true, 'label' => false, 'placeholder' => __('Valid Email'), 'class' => 'span12', 'autocomplete' => 'off'));?>
    </div>
    <div class="row-fluid widhtHint">
        <?php $after = '<div class="hint">'.__('Minimum 6 chars length.').'</div>';?>
        <?php echo $this->Form->input('User.password', array('type' => 'password', 'required' => true, 'label' => false, 'placeholder' => __('Password'), 'class' => 'span12', 'pattern' => '.{6,}', 'after' => $after, 'autocomplete' => 'off'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('User.verify_password', array('type' => 'password', 'required' => true, 'label' => false, 'placeholder' => __('Confirm password'), 'class' => 'span12'));?>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <?php $after = '<span class="hint">'.$this->Html->link(__('I already have an account.'), '/auth/login', array('escape' => false)).'</span>';?>
            <?php echo $this->Form->submit(__('Signup'), array('class' => 'btn btn-success', 'after' => $after));?>
        </div>
    </div>
<?php echo $this->Form->end();?>