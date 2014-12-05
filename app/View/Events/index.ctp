<header>
	<h1>
        <i class="icon-calendar"></i> <?php echo __('Events');?>

        <?php if($user['User']['can']['manage_own_events'] || $user['User']['can']['manage_events'] || $user['User']['can']['full_permissions']):?>
            <div class="pull-right span3" id="createEvent">
                <div class="input-prepend input-append">
                    <span class="add-on"><span class="icon-calendar"></span></span>
                    <input type="text" name="eventDate" value="" class="input-medium" placeholder="<?php echo __('Quick event creation');?>" />
                    <button class="btn btn-success"><?php echo __('create');?></button>
                </div>
            </div>
        <?php endif;?>

        <div class="pull-right span2" id="filterEvents">
            <div class="input-prepend input-append">
                <span class="add-on"><span class="icon-filter"></span></span>
                <?php echo $this->Form->input(false, array('type' => 'select', 'options' => $gamesList, 'selected' => $filterEventsGameId, 'class' => 'input-medium', 'label' => false, 'div' => null, 'default' => '0'));?>
            </div>
        </div>
    </h1>
</header>

<?php echo $this->Former->calendar($calendarOptions, $events);?>