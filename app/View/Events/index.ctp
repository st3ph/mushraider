<header>
	<h1>
        <i class="icon-calendar"></i> <?php echo __('Events');?>

        <?php if($user['User']['can']['manage_events'] || $user['User']['can']['full_permissions']):?>
            <div class="pull-right span2" id="createEvent">
                <input type="text" name="eventDate" value="" class="input-mini" placeholder="<?php echo __('date');?>" />
                <button class="btn btn-mini btn-success"><?php echo __('create');?></button>
            </div>
        <?php endif;?>

        <div class="pull-right span2" id="filterEvents">
            <?php echo $this->Form->input(false, array('type' => 'select', 'options' => $gamesList, 'selected' => $filterEventsGameId, 'class' => 'input-medium', 'label' => false, 'div' => null, 'empty' => __('Filter by game')));?>
        </div>
    </h1>
</header>

<?php echo $this->Former->calendar($calendarOptions, $events);?>