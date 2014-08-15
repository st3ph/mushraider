<h3><?php echo __('Add new dungeon');?></h3>

<?php echo $this->Form->create('Dungeon', array('url' => '/admin/dungeons/add', 'class' => '', 'enctype' => 'multipart/form-data'));?>
    <div class="form-group">
        <?php echo $this->Form->input('Dungeon.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
    </div>
    <?php if(!empty($gamesList)):?>
        <div class="form-group">
            <?php echo $this->Form->input('Dungeon.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5'));?>
        </div>
    <?php endif;?>
    <div class="form-group dungeonSizeInputs" data-error="<?php echo __('Please fill one of the 2 inputs');?>">
        <?php echo $this->Form->input('Dungeon.raidssize_id', array('type' => 'select', 'label' => __('Number of players'), 'options' => $raidssizeList, 'empty' => '', 'required' => false, 'class' => 'span5'));?>
        <i class="icon-plus-sign"></i> <?php echo __('or add custom number');?> <?php echo $this->Form->input('Dungeon.customraidssize', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'span1', 'pattern' => '[0-9]{1,3}'));?>
    </div>
    <?php if(isset($isNotAjax)):?>
        <div class="form-group">
            <?php echo $this->Form->input('Dungeon.icon', array('type' => 'file', 'label' => __('Icon').' (64px / 64px)', 'class' => 'span5 imageupload'));?>
            <div id="previewcanvascontainer">
                <canvas id="previewcanvas" class="w64"></canvas>
            </div>
        </div>
    <?php endif;?>

    <div class="form-group">
        <?php if(isset($dungeonEdit)):?>
            <?php echo $this->Form->input('Dungeon.id', array('type' => 'hidden'));?>
        <?php endif;?>
        <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
    </div>
<?php echo $this->Form->end();?>