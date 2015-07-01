<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-file-text-o"></i></div>
        <h5><?php echo __('Create a page');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Page', array('url' => '/admin/cms/add', 'class' => 'span12'));?>
            <div class="form-group">
                <?php echo $this->Form->input('Page.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Page.content', array('type' => 'textarea', 'label' => __('Page content'), 'class' => 'span5 wysiwyg', 'data-height' => 500));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Page.public', array('options' => array('0' => __('Private'), '1' => __('Public')), 'required' => true, 'label' => __('Visibility'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Page.published', array('options' => array('0' => __('Draft'), '1' => __('Published')), 'required' => true, 'label' => __('Status'), 'class' => 'span5'));?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-large btn-success pull-right'));?>
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>