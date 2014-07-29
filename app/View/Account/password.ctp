<header>
    <h1><i class="icon-key"></i> <?php echo __('My password');?></h1>
</header>

<?php echo $this->Form->create('User', array('url' => '/account/password', 'class' => ''));?>
    <div class="form-group">
        <?php echo $this->Form->input('User.currentpassword', array('type' => 'password', 'required' => true, 'label' => __('Current password'), 'class' => 'form-control'));?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('User.newpassword', array('type' => 'password', 'required' => true, 'label' => __('New password'), 'class' => 'form-control', 'pattern' => '.{6,}'));?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('User.newpassword2', array('type' => 'password', 'required' => true, 'label' => __('Confirm new password'), 'class' => 'form-control', 'pattern' => '.{6,}'));?>
    </div> 
    <div class="form-group">
        <div class="span2">
            <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-warning'));?>
        </div>
    </div>
<?php echo $this->Form->end();?>
