<div class="box dark">
    <header>
        <div class="icons"><i class="icon-group"></i></div>
        <h5><?php echo __('Add player role');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('RaidsRole', array('url' => '/admin/raidroles/add', 'class' => 'span12'));?>
            <div class="form-group">
                <?php echo $this->Form->input('RaidsRole.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
      
            <div class="form-group">
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>