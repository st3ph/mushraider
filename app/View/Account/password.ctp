<header>
    <h1><i class="fa fa-key"></i> <?php echo __('My password');?></h1>
</header>

<div class="row">
    <div class="span2">
        <?php echo $this->element('account_menu');?>
    </div>
    <div class="span9">
        <?php echo $this->Form->create('User', array('url' => '/account/password', 'class' => ''));?>
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
</div>