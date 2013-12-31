<ul class="nav nav-tabs nav-stacked">
    <li class="<?php echo $this->action == 'characters' || $this->action == 'characters_edit'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-shield"></i> '.__('Characters').'<i class="icon-chevron-right pull-right"></i>', '/account/characters', array('escape' => false, 'title' => __('Characters')));?>
    </li>
    <li class="<?php echo $this->action == 'settings'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-gears"></i> '.__('Options').'<i class="icon-chevron-right pull-right"></i>', '/account/settings', array('escape' => false, 'title' => __('Options')));?>
    </li>
    <li class="<?php echo $this->action == 'password'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-key"></i> '.__('Password').'<i class="icon-chevron-right pull-right"></i>', '/account/password', array('escape' => false, 'title' => __('Password')));?>
    </li>
</ul>