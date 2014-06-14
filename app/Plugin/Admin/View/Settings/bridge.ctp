<div class="box dark">
    <header>
        <div class="icons"><i class="icon-cog"></i></div>
        <h5><?php echo __('Configure MushRaider');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Setting', array('url' => '/admin/settings/bridge', 'class' => 'span12', 'enctype' => 'multipart/form-data'));?>
            <h3><?php echo __('Integration');?></h3>

            <div class="alert alert-info">
                <h4><i class="icon-exclamation-sign"></i> <?php echo __('What is that ?');?></h4>
                <p><?php echo __('MushRaider integration (or bridge) let your users connect to MushRaider using the login and password of your current website/forum instead of having 2 differents accounts.');?></p>
                <p><?php echo __('To know if MushRaider support your current system please head to the <a href="http://mushraider.com/bridge">official bridge page</a>.');?></p>
            </div>

            <div class="well well-white">
                <div class="form-group">
                    <label><?php echo __('Integration');?></label>
                    <?php echo $this->Form->input('Setting.enabled', array('type' => 'checkbox', 'label' => __('Integrate MushRaider with your current website/forum')));?>
                </div>

                <div class="form-group">
                    <?php $after = '<span class="hint icon-question-sign tt" title="'.__('Adress provided in the settings of your site/forum MushRaider plugin').'"></span>'?>
                    <?php echo $this->Form->input('Setting.url', array('type' => 'text', 'label' => __('Third party url'), 'after' => $after, 'class' => 'span5'));?>
                </div>
                <div class="form-group">
                    <?php $after = '<span class="hint icon-question-sign tt" title="'.__('Copy this secret key in the settings of your site/forum MushRaider plugin.').'"></span>'?>
                    <?php echo $this->Form->input('Setting.secret', array('type' => 'text', 'label' => __('Secret key'), 'pattern' => '[0-9a-zA-Z\-_]{16}', 'after' => $after, 'maxlength' => 16, 'class' => 'span5'));?>
                    <span class="muted"><?php echo __('Must be 16 characters long. Allowed characters : a-z, 0-9, -, _.');?></span>
                </div>
            </div>

            <div class="form-group">
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>    
</div>