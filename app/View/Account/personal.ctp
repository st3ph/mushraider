<header>
    <h1><i class="icon-user"></i> <?php echo __('Personal informations');?></h1>
</header>

<div class="row">
    <div class="span2">
        <?php echo $this->element('account_menu');?>
    </div>
    <div class="span9">
        <?php echo $this->Form->create('User', array('url' => '/account/personal', 'class' => ''));?>
            <div class="row-fluid">
                <?php $after = '<div class="hint">'.__('Letters and numbers only, 3 to 20 chars length.').'</div>';?>
                <?php echo $this->Form->input('User.username', array('type' => 'text', 'required' => true, 'label' => __('Username'), 'class' => 'span5', 'placeholder' => __('Username'), 'pattern' => '[a-zA-Z0-9_\-]{3,20}', 'after' => $after));?>
            </div>
            <div class="row-fluid">
                <?php echo $this->Form->input('User.email', array('type' => 'email', 'required' => true, 'label' => __('Email'), 'class' => 'span5', 'placeholder' => __('Valid Email')));?>
            </div>
            <div class="row-fluid">
                <div class="span2">
                    <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-warning'));?>
                </div>
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>