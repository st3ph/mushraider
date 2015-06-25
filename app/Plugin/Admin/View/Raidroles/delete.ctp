<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-group"></i></div>
        <h5><?php echo __('Delete player role');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('RaidsRole', array('url' => '/admin/raidroles/delete/'.$this->data['RaidsRole']['id'], 'class' => 'span12'));?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>
                <?php echo __('Before deleting the role "%s" you have to select a replacing one', $this->data['RaidsRole']['title']);?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('RaidsRole.id', array('options' => $raidRoles, 'required' => true, 'label' => __('Replace %s with...', $this->data['RaidsRole']['title']), 'class' => 'span5'));?>
            </div>
      
            <div class="form-group">
                <?php echo $this->Form->submit(__('Delete'), array('class' => 'btn btn-large btn-danger pull-right'));?>
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>