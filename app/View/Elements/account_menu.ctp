<ul class="nav nav-tabs nav-stacked">
    <li class="<?php echo $this->action == 'personal'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-user"></i> '.__('Account').'<i class="icon-chevron-right pull-right"></i>', '/account/personal', array('escape' => false, 'title' => __('Account')));?>
    </li>
    <li class="<?php echo $this->action == 'characters' || $this->action == 'characters_edit'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-shield"></i> '.__('Characters').'<i class="icon-chevron-right pull-right"></i>', '/account/characters', array('escape' => false, 'title' => __('Characters')));?>
    </li>
    <li class="<?php echo $this->action == 'notifications'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-bullhorn"></i> '.__('Notifications').'<i class="icon-chevron-right pull-right"></i>', '/account/notifications', array('escape' => false, 'title' => __('Notifications')));?>
    </li>
    <?php if(empty($bridge) || (!empty($bridge) && !$bridge->enabled)):?>
        <li class="<?php echo $this->action == 'password'?'active':'';?>">
            <?php echo $this->Html->link('<i class="icon-key"></i> '.__('Password').'<i class="icon-chevron-right pull-right"></i>', '/account/password', array('escape' => false, 'title' => __('Password')));?>
        </li>
    <?php endif?>
    <li class="<?php echo $this->action == 'availabilities'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-time"></i> '.__('Absences').'<i class="icon-chevron-right pull-right"></i>', '/account/availabilities', array('escape' => false, 'title' => __('Absences')));?>
    </li>
    <li class="<?php echo $this->action == 'calendar'?'active':'';?>">
        <?php echo $this->Html->link('<i class="icon-calendar"></i> '.__('Calendar').'<i class="icon-chevron-right pull-right"></i>', '/account/calendar', array('escape' => false, 'title' => __('Calendar')));?>
    </li>
</ul>