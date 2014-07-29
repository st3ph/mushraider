<div class="box dark">
    <header>
        <div class="icons"><i class="icon-home"></i></div>
        <h5><?php echo __('Edit Attenuement');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Attenuement', array('url' => '/admin/attenuements/edit/'.$this->data['Attenuement']['id'], 'class' => ''));?>
            <div class="form-group">
                <?php echo $this->Form->input('Attenuement.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                 <?php echo $this->Form->input('Attenuement.rank', array('type' => 'text', 'required' => true, 'label' => __('Rank'), 'class' => 'span5'));?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('Attenuement.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>