<?php $gamesList = !empty($gamesList)?$gamesList:array();?>
<div class="box dark">
    <header>
        <div class="icons"><i class="icon-home"></i></div>
        <h5><?php echo __('Add Attunement');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->element('addAttunement', array('gamesList' => $gamesList));?>
    </div>
</div>