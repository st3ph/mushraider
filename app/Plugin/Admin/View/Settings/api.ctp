<div class="box dark">
    <header>
        <div class="icons"><i class="icon-cog"></i></div>
        <h5><?php echo __('Configure MushRaider');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Setting', array('url' => '/admin/settings/api', 'class' => 'span12', 'enctype' => 'multipart/form-data'));?>
            <h3><?php echo __('API and Bridge');?></h3>

            <div class="alert alert-info">
                <h4><i class="icon-info-sign"></i> <?php echo __('What is that ?');?></h4>
                <p><?php echo __('MushRaider API allows your other websites to "discuss" with MushRaider and site admins to get and display some informations about the raid planner in their own websites like a list of users, characters or events.');?></p>
                <p><?php echo __('To view the list of available endpoints and to learn how to use the API please head to the <a href="http://mushraider.com/api">official api documentation page</a>.');?></p>
            </div>

            <div class="well well-white">
                <h3><?php echo __('API');?></h3>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.enabled', array('type' => 'checkbox', 'label' => __('Enable API calls')));?>
                </div>

                <div class="form-group">
                    <div class="input text" id="apiPrivateKey">
                        <label><?php echo __('Private key');?></label>
                        <?php $privateKey = !empty($this->data['Setting']['privateKey'])?$this->data['Setting']['privateKey']:__('Generate the private key using the little button on the right');?>
                        <span class="input-xxlarge uneditable-input privateKey"><?php echo $privateKey;?></span>
                        <span class="refresh icon-refresh tt" title="<?php echo __('Generate a new private key');?>"></span>
                        <?php echo $this->Form->input('Setting.privateKey', array('type' => 'hidden', 'label' => false));?>
                    </div>
                    <span class="muted"><?php echo __('Private key used to secure (HMAC) your queries. Keep it secret !');?></span>
                </div>

                <div id="apiModules" <?php echo isset($this->data['Setting']['enabled']) && $this->data['Setting']['enabled']?'':'class="hide"';?>>
                    <h4><?php echo __('Bridge');?></h4>

                    <p class="label label-info">
                        <i class="icon-info-sign"></i> <?php echo __('MushRaider integration (or bridge) let your users connect to MushRaider using the login and password of your current website/forum instead of having 2 differents accounts.');?><br />
                        <?php echo __('To know if MushRaider support your current system please head to the <a href="http://mushraider.com/bridge">official bridge page</a>.');?>
                    </p>

                    <p class="label label-important">
                        <i class="icon-exclamation-sign"></i> <?php echo __('Before enabling bridge make sure your site/forum is set up correctly to bridge with MushRaider by installing and setting up the corresponding plugin.');?>
                    </p>

                    <div class="form-group">
                        <?php echo $this->Form->input('Setting.bridge.enabled', array('type' => 'checkbox', 'label' => __('Enable MushRaider bridge with your current website/forum')));?>
                    </div>

                    <div class="form-group">
                        <?php $after = '<span class="hint icon-question-sign tt" title="'.__('Adress provided in the settings of your site/forum MushRaider plugin').'"></span>'?>
                        <?php echo $this->Form->input('Setting.bridge.url', array('type' => 'text', 'label' => __('Third party url'), 'after' => $after, 'class' => 'span5'));?>
                    </div>

                    <div class="form-group">
                        <?php echo $this->Form->input('Setting.bridge.default_group', array('type' => 'select', 'label' => __('Default user group'), 'options' => $roles));?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>    
</div>