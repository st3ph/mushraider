<div class="box dark">
    <header>
        <div class="icons"><i class="icon-cog"></i></div>
        <h5><?php echo __('Configure MushRaider');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Setting', array('url' => '/admin/settings/api', 'class' => 'span12', 'enctype' => 'multipart/form-data'));?>
            <h3><?php echo __('API');?></h3>

            <div class="alert alert-info">
                <h4><i class="icon-exclamation-sign"></i> <?php echo __('What is that ?');?></h4>
                <p><?php echo __('MushRaider API allow site admins to get and display some informations about the raid planner in their own websites like a list of users, characters or events.');?></p>
                <p><?php echo __('To view the list of available endpoints and to learn how to use the API please head to the <a href="http://mushraider.com/api">official api documentation page</a>.');?></p>
            </div>

            <div class="well well-white">
                <div class="form-group">
                    <label><?php echo __('API');?></label>
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
                    <span class="muted"><?php echo __('Private key used to secure (HMAC) your queries.');?></span>
                </div>
            </div>

            <div class="form-group">
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>    
</div>