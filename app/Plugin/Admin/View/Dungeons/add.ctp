<?php $gamesList = !empty($gamesList)?$gamesList:array();?>
<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-home"></i></div>
        <h5><?php echo __('Add Dungeon');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->element('addDungeon', array('gamesList' => $gamesList, 'isNotAjax' => true));?>
    </div>
</div>