<div class="box dark">
    <header>
        <div class="icons"><i class="icon-home"></i></div>
        <h5><?php echo __('Edit Class');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Classe', array('url' => '/admin/classes/edit/'.$this->data['Classe']['id'], 'class' => ''));?>
            <div class="form-group">
                <?php echo $this->Form->input('Classe.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Classe.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Classe.color', array('type' => 'text', 'required' => true, 'label' => __('Color'), 'class' => 'span1 colorpicker'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Classe.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>