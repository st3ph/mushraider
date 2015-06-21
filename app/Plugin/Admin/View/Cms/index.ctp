<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-file-text-o"></i></div>
        <h5><?php echo __('Create a page');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Page', array('class' => 'span12'));?>
            <div class="form-group">
                <?php echo $this->Form->input('Page.content', array('type' => 'textarea', 'label' => __('Page content'), 'class' => 'span5 wysiwyg', 'data-height' => 500));?>
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>