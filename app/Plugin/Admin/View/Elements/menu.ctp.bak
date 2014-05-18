<div id="left">
    <div class="media user-media hidden-phone">
        <div class="media-body hidden-tablet">
            <h5 class="media-heading"><i class="icon-user icon-white"></i> <?php echo $user['User']['username'];?></h5>
            <ul class="unstyled user-info">
                <li><?php echo __($user['Role']['title']);?></li>
            </ul>
        </div>
    </div>

    <ul id="menu" class="unstyled accordion collapse in">
        <li class="<?php echo strtolower($this->name) == 'dashboard'?'active':'';?>">
            <?php echo $this->Html->link('<i class="icon-home icon-white"></i> '.__('Dashboard'), '/admin/dashboard', array('escape' => false));?>
        </li>

        <?php if($user['User']['isAdmin']):?>
            <li class="<?php echo strtolower($this->name) == 'settings'?'active':'';?>">
                <?php echo $this->Html->link('<i class="icon-cog icon-white"></i> '.__('Settings'), '/admin/settings', array('escape' => false));?>
            </li>
        <?php endif;?>

        <?php if($user['User']['isAdmin']):?>
            <li class="<?php echo strtolower($this->name) == 'stats'?'active':'';?>">
                <?php echo $this->Html->link('<i class="icon-bar-chart icon-white"></i> '.__('Stats'), '/admin/stats', array('escape' => false));?>
            </li>
        <?php endif;?>

        <li class="<?php echo strtolower($this->name) == 'rosters'?'active':'';?>">
            <?php echo $this->Html->link('<i class="icon-shield icon-white"></i> '.__('Roster'), '/admin/rosters', array('escape' => false));?>
        </li>

        <li class="accordion-group <?php echo strtolower($this->name) == 'events'?'active':'';?>">
            <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle <?php echo strtolower($this->name) == 'events'?'':'collapsed';?>" data-target="#events-nav">
                <i class="icon-calendar icon-white"></i> <?php echo __('Events');?> <i class="icon-chevron-down icon-white pull-right"></i>
            </a>
            <ul class="collapse <?php echo strtolower($this->name) == 'events'?'in':'';?>" id="events-nav">
                <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Manage templates'), '/admin/events/templates', array('escape' => false));?></li>
            </ul>
        </li>

        <li class="accordion-group <?php echo strtolower($this->name) == 'users'?'active':'';?>">
            <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle <?php echo strtolower($this->name) == 'users'?'':'collapsed';?>" data-target="#users-nav">
                <i class="icon-user icon-white"></i> <?php echo __('Users');?> <i class="icon-chevron-down icon-white pull-right"></i>
            </a>
            <ul class="collapse <?php echo strtolower($this->name) == 'users'?'in':'';?>" id="users-nav">
                <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Manage users'), '/admin/users', array('escape' => false));?></li>
                <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Waiting users'), '/admin/users/waiting', array('escape' => false));?></li>
            </ul>
        </li>

        <li class="accordion-group <?php echo strtolower($this->name) == 'dungeons'?'active':'';?>">
            <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle <?php echo strtolower($this->name) == 'dungeons'?'':'collapsed';?>" data-target="#dungeons-nav">
                <i class="icon-home icon-white"></i> <?php echo __('Dungeons');?> <i class="icon-chevron-down icon-white pull-right"></i>
            </a>
            <ul class="collapse <?php echo strtolower($this->name) == 'dungeons'?'in':'';?>" id="dungeons-nav">
                <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Manage dungeons'), '/admin/dungeons', array('escape' => false));?></li>
                <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Add dungeon'), '/admin/dungeons/add', array('escape' => false));?></li>
                <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Disabled dungeons'), '/admin/dungeons/disabled', array('escape' => false));?></li>
            </ul>
        </li>
        <?php if($user['User']['isAdmin']):?>
            <li class="accordion-group <?php echo strtolower($this->name) == 'games'?'active':'';?>">
                <?php $toggleControllers = array('games', 'races', 'classes');?>
                <a data-parent="#menu" data-toggle="collapse" class="accordion-toggle <?php echo in_array(strtolower($this->name), $toggleControllers)?'':'collapsed';?>" data-target="#games-nav">
                    <i class="icon-gamepad icon-white"></i> <?php echo __('Games');?> <i class="icon-chevron-down icon-white pull-right"></i>
                </a>
                <ul class="collapse <?php echo in_array(strtolower($this->name), $toggleControllers)?'in':'';?>" id="games-nav">
                    <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Manage games'), '/admin/games', array('escape' => false));?></li>
                    <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Add game'), '/admin/games/add', array('escape' => false));?></li>
                    <li class="divider"></li>
                    <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Manage classes'), '/admin/classes', array('escape' => false));?></li>
                    <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Add class'), '/admin/classes/add', array('escape' => false));?></li>
                    <li class="divider"></li>
                    <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Manage races'), '/admin/races', array('escape' => false));?></li>
                    <li><?php echo $this->Html->link('<i class="icon-chevron-right"></i> '.__('Add race'), '/admin/races/add', array('escape' => false));?></li>
                </ul>
            </li>
        <?php endif;?>
    </ul>
</div>