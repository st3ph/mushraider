<div class="box dark">
    <header>
        <div class="icons"><i class="icon-home"></i></div>
        <h5><?php echo __('Edit Dungeon');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Dungeon', array('url' => '/admin/dungeons/edit/'.$this->data['Dungeon']['id'], 'class' => ''));?>
            <div class="form-group">
                <?php echo $this->Form->input('Dungeon.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Dungeon.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Dungeon.raidssize_id', array('type' => 'select', 'label' => __('Number of players'), 'options' => $raidssizeList, 'empty' => '', 'class' => 'span5'));?>
                <i class="icon-plus-sign"></i> <?php echo __('or add custom number');?> <?php echo $this->Form->input('Dungeon.customraidssize', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'span1', 'pattern' => '[0-9]{1,3}'));?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('Dungeon.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>