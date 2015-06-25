<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-puzzle-piece"></i></div>
        <h5><?php echo __('Add widget');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Widget', array('url' => '/admin/widgets/add', 'class' => 'span12', 'id' => 'addWidget'));?>
            <h3>1. <?php echo __('Choose widget type');?></h3>
            <div class="form-group">
                <?php echo $this->Form->input('Widget.type', array('type' => 'select', 'label' => __('Widget'), 'empty' => '', 'options' => $availableWidgets, 'class' => 'span5'));?>
            </div>
            <?php if($selectedWidget):?>
                <h3>2. <?php echo __('Widgets options');?></h3>
                <?php echo $this->element($selectedWidget, array('gamesList' => $gamesList), array('plugin' => 'Widget'));?>
                <div class="form-group">
                    <?php echo $this->Form->input('Widget.params.private', array('type' => 'checkbox', 'label' => __('Private ? (must be logged to view content)'), 'checked' => !empty($this->data['Widget']['Params']['private'])));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Widget.params.domain', array('type' => 'url', 'label' => __('Restrict to the following domain (optional)'), 'placeholder' => __('http://myguild.com'), 'class' => 'span5'));?>
                </div>

                <h3>3. <?php echo __('Customize your Widget');?></h3>
                <div class="row-fluid">
                    <div class="span6 customize">
                        <div class="form-group">
                            <?php echo $this->Form->input('Widget.params.headerBgColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['Params']['headerBgColor'])?$this->data['Widget']['Params']['headerBgColor']:'#bf4d28', 'label' => __('Header background color (optional)'), 'class' => 'span1 colorpicker'));?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('Widget.params.textColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['Params']['textColor'])?$this->data['Widget']['Params']['textColor']:'#333333', 'label' => __('Text color (optional)'), 'class' => 'span1 colorpicker'));?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('Widget.params.linkColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['Params']['linkColor'])?$this->data['Widget']['Params']['linkColor']:'#bf4d28', 'label' => __('Links color (optional)'), 'class' => 'span1 colorpicker'));?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('Widget.params.bgColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['Params']['bgColor'])?$this->data['Widget']['Params']['bgColor']:'#ffffff', 'label' => __('Background color (optional)'), 'class' => 'span1 colorpicker'));?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('Widget.params.headerTextColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['Params']['headerTextColor'])?$this->data['Widget']['Params']['headerTextColor']:'#ffffff', 'label' => __('Header text color (optional)'), 'class' => 'span1 colorpicker'));?>
                        </div>
                    </div>
                    <div class="span6">
                        <h4><?php echo __('Widget preview');?></h4>
                        <div class="preview">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-large btn-success pull-right'));?>
                </div>
            <?php endif;?>
        <?php echo $this->Form->end();?>
    </div>
</div>