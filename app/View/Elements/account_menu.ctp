<ul class="nav nav-tabs nav-stacked">
    <li class="<?php echo $this->action == 'personal'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-user"></i> '.__('Account').'<i class="icon-chevron-right pull-right"></i>', '/account/personal', array('escape' => false, 'title' => __('Account')));?>
    </li>
    <li class="<?php echo $this->action == 'characters' || $this->action == 'characters_edit'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-shield"></i> '.__('Characters').'<i class="icon-chevron-right pull-right"></i>', '/account/characters', array('escape' => false, 'title' => __('Characters')));?>
    </li>
    <li class="<?php echo $this->action == 'settings'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-cogs"></i> '.__('Options').'<i class="icon-chevron-right pull-right"></i>', '/account/settings', array('escape' => false, 'title' => __('Options')));?>
    </li>
    <?php if(empty($bridge) || (!empty($bridge) && !$bridge->enabled)):?>
        <li class="<?php echo $this->action == 'password'?'active':'';?>">
            <?php echo $this->Html->link('<i class="icon-key"></i> '.__('Password').'<i class="icon-chevron-right pull-right"></i>', '/account/password', array('escape' => false, 'title' => __('Password')));?>
        </li>
    <?php endif?>
    <li class="<?php echo $this->action == 'availabilities'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-time"></i> '.__('Absences').'<i class="icon-chevron-right pull-right"></i>', '/account/availabilities', array('escape' => false, 'title' => __('Absences')));?>
    </li>
</ul>