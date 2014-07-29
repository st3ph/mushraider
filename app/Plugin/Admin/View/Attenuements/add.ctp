<?php $gamesList = !empty($gamesList)?$gamesList:array();?>
<div class="box dark">
    <header>
        <div class="icons"><i class="icon-home"></i></div>
        <h5><?php echo __('Add Attenuement');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->element('addAttenuement', array('gamesList' => $gamesList));?>
    </div>
</div>