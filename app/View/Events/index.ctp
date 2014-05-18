<header>
	<h1>
        <i class="icon-calendar"></i> <?php echo __('Events');?>

        <div class="pull-right span4" id="createEvent">
            <input type="text" name="eventDate" value="" class="input-mini" />
            <button class="btn btn-mini btn-success"><?php echo __('create');?></button>
        </div>
    </h1>
</header>

<?php echo $this->Former->calendar($calendarOptions, $events);?>