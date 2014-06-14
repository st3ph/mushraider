<div class="box dark">
    <header>
        <div class="icons"><i class="icon-cog"></i></div>
        <h5><?php echo __('Configure MushRaider');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Setting', array('url' => '/admin/settings', 'class' => 'span12', 'enctype' => 'multipart/form-data'));?>
            <h3><?php echo __('General settings');?></h3>
            <div class="well well-white">
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.title', array('type' => 'text', 'label' => __('Site title'), 'class' => 'span5'));?>
                </div>
                <div class="form-group">
                    <label><?php echo __('Email notifications');?></label>
                    <?php echo $this->Form->input('Setting.notifications', array('type' => 'checkbox', 'label' => __('Enable email notifications')));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.sitelang', array('type' => 'select', 'options' => $appLocales, 'required' => true, 'class' => 'span2', 'label' => __('Default language')));?>
                </div>
            </div>

            <h3><?php echo __('Emails');?></h3>
            <div class="well well-white">
                <h4><?php echo __('Sender');?></h4>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.email.name', array('type' => 'text', 'label' => __('Your name'), 'required' => true, 'class' => 'span5'));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.email.from', array('type' => 'email', 'label' => __('Your email address'), 'required' => true, 'class' => 'span5'));?>
                </div>

                <h4><?php echo __('Advanced settings');?></h4>
                <div class="form-group">
                    <label><?php echo __('Encoding');?></label>
                    <?php echo $this->Form->input('Setting.email.utf8', array('type' => 'checkbox', 'label' => __('Encode as UTF-8')));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.email.transport', array('type' => 'select', 'options' => array('Mail' => 'Mail', 'Smtp' => 'Smtp'), 'default' => 'Mail', 'label' => __('Transport')));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.email.host', array('type' => 'text', 'label' => __('SMTP Host'), 'class' => 'span5'));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.email.port', array('type' => 'text', 'label' => __('SMTP Port'), 'pattern' => '[0-9]{0,6}', 'class' => 'span5'));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.email.username', array('type' => 'text', 'label' => __('SMTP Username'), 'class' => 'span5'));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.email.password', array('type' => 'text', 'label' => __('SMTP Password'), 'class' => 'span5'));?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>    
</div>
<?php $this->Html->script('tracker', array('inline' => false, 'block' => 'scriptBottom'));?>