<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-home"></i></div>
        <h5><?php echo __('Edit Race');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Race', array('url' => '/admin/races/edit/'.$this->data['Race']['id'], 'class' => ''));?>
            <div class="form-group">
                <?php echo $this->Form->input('Race.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Race.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Race.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>