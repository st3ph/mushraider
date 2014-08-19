<div class="box dark">
    <header>
        <div class="icons"><i class="icon-puzzle-piece"></i></div>
        <h5><?php echo __('Edit widget');?> : <?php echo $this->data['Widget']['title'];?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Widget', array('url' => '/admin/widgets/edit/'.$this->data['Widget']['id'], 'class' => 'span12', 'id' => 'addWidget'));?>
            <h3>1. <?php echo __('Widget type');?></h3>
            <?php echo $availableWidgets[$this->data['Widget']['controller'].'_'.$this->data['Widget']['action']];?>

            <h3>2. <?php echo __('Widgets options');?></h3>
            <?php echo $this->element($this->data['Widget']['controller'].'_'.$this->data['Widget']['action'], array('gamesList' => $gamesList), array('plugin' => 'Widget'));?>
            <div class="form-group">
                <?php echo $this->Form->input('Widget.params.private', array('type' => 'checkbox', 'label' => __('Private ? (must be logged to view content)'), 'checked' => !empty($this->data['Widget']['params']['private'])));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Widget.params.domain', array('type' => 'url', 'label' => __('Restrict to the following domain (optional)'), 'placeholder' => __('http://myguild.com'), 'class' => 'span5'));?>
            </div>

            <h3>3. <?php echo __('Customize your Widget');?></h3>
            <div class="row-fluid">
                <div class="span6 customize">
                    <div class="form-group">
                        <?php echo $this->Form->input('Widget.params.headerBgColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['params']['headerBgColor'])?$this->data['Widget']['params']['headerBgColor']:'#bf4d28', 'label' => __('Header background color (optional)'), 'class' => 'span1 colorpicker'));?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('Widget.params.textColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['params']['textColor'])?$this->data['Widget']['params']['textColor']:'#333333', 'label' => __('Text color (optional)'), 'class' => 'span1 colorpicker'));?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('Widget.params.linkColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['params']['linkColor'])?$this->data['Widget']['params']['linkColor']:'#bf4d28', 'label' => __('Links color (optional)'), 'class' => 'span1 colorpicker'));?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('Widget.params.bgColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['params']['bgColor'])?$this->data['Widget']['params']['bgColor']:'#ffffff', 'label' => __('Background color (optional)'), 'class' => 'span1 colorpicker'));?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('Widget.params.headerTextColor', array('type' => 'text', 'value' => !empty($this->data['Widget']['params']['headerTextColor'])?$this->data['Widget']['params']['headerTextColor']:'#ffffff', 'label' => __('Header text color (optional)'), 'class' => 'span1 colorpicker'));?>
                    </div>
                </div>
                <div class="span6">
                    <h4><?php echo __('Widget preview');?></h4>
                    <div class="preview">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('Widget.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>