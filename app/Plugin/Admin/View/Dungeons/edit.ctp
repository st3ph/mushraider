<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-home"></i></div>
        <h5><?php echo __('Edit Dungeon');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Dungeon', array('url' => '/admin/dungeons/edit/'.$this->data['Dungeon']['id'], 'class' => '', 'enctype' => 'multipart/form-data'));?>
            <div class="form-group">
                <?php echo $this->Form->input('Dungeon.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Dungeon.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5'));?>
            </div>
            <div class="form-group dungeonSizeInputs" data-error="<?php echo __('Please fill one of the 2 inputs');?>">
                <?php echo $this->Form->input('Dungeon.raidssize_id', array('type' => 'select', 'label' => __('Number of players'), 'options' => $raidssizeList, 'empty' => '', 'required' => false, 'class' => 'span5'));?>
                <i class="fa fa-plus-circle"></i> <?php echo __('or add custom number');?> <?php echo $this->Form->input('Dungeon.customraidssize', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'span1', 'pattern' => '[0-9]{1,3}'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Dungeon.level_required', array('type' => 'text', 'required' => false, 'label' => __('Required level'), 'class' => 'span5', 'pattern' => '[0-9]{1,3}'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Dungeon.icon', array('type' => 'file', 'label' => __('Icon').' (64px / 64px)', 'class' => 'span5 imageupload'));?>
                <?php echo $this->Form->input('Dungeon.deleteIcon', array('type' => 'hidden', 'value' => '0'));?>
                <div id="previewcanvascontainer">
                    <canvas id="previewcanvas" class="w64"></canvas>
                    <?php if(!empty($this->data['Dungeon']['icon'])):?>
                        <div id="currentImage">
                            <h6><?php echo __('Current icon');?> :</h6>
                            <?php echo $this->Html->image($this->data['Dungeon']['icon'], array('class' => ''));?>
                            <button class="btn btn-mini btn-danger" type="button"><i class="fa fa-trash"></i> <?php echo __('delete');?></button>
                        </div>
                    <?php endif;?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('Dungeon.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-large btn-success', 'div' => array('class' => 'submit pull-right')));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>