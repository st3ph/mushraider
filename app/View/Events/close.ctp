<header>
    <h1>
        <i class="icon-lock"></i> <?php echo __('Close event & create a report');?>
    </h1>
</header>

<?php echo $this->Form->create('Event', array('url' => '/events/close/'.$event['Event']['id'], 'enctype' => 'multipart/form-data'));?>
    <h3><?php echo __('Event report');?></h3>
    <div class="control-group">
        <?php echo $this->Form->input('Report.description', array('type' => 'textarea', 'label' => __('Description'), 'class' => 'wysiwyg-tall'));?>
    </div>

    <div class="control-group">
        <div class="row">
            <?php echo $this->Form->input('Report.screenshot_1', array('type' => 'file', 'label' => __('Screenshot 1').' (max '.Configure::read('Config.maxFileSize').')', 'div' => array('class' => 'input file span5')));?>
            <?php echo $this->Form->input('Report.screenshot_2', array('type' => 'file', 'label' => __('Screenshot 2').' (max '.Configure::read('Config.maxFileSize').')', 'div' => array('class' => 'input file span5')));?>
            <?php echo $this->Form->input('Report.screenshot_3', array('type' => 'file', 'label' => __('Screenshot 3').' (max '.Configure::read('Config.maxFileSize').')', 'div' => array('class' => 'input file span5')));?>
            <?php echo $this->Form->input('Report.screenshot_4', array('type' => 'file', 'label' => __('Screenshot 4').' (max '.Configure::read('Config.maxFileSize').')', 'div' => array('class' => 'input file span5')));?>
        </div>
        <span class="label label-info"><i class="icon-info-sign"></i> <?php echo __('Total max size for all files (based on you php settings)');?> : <?php echo Configure::read('Config.maxPostSize');?></span>
    </div>

    <hr />

    <div class="control-group">
        <?php echo $this->Form->submit(__('Save  & close'), array('class' => 'btn btn-success btn-large pull-right'));?>
    </div>
    <div class="clearfix"></div>
<?php echo $this->Form->end();?>