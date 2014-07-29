<div class="box dark">
    <header>
        <div class="icons"><i class="icon-home"></i></div>
        <h5><?php echo __('Edit Attunement');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Attunement', array('url' => '/admin/attunements/edit/'.$this->data['Attunement']['id'], 'class' => ''));?>
            <div class="form-group">
                <?php echo $this->Form->input('Attunement.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                 <?php echo $this->Form->input('Attunement.rank', array('type' => 'text', 'required' => true, 'label' => __('Rank'), 'class' => 'span5'));?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('Attunement.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>