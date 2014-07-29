<header>
	<div class="pull-right" id="createEvent">
        <input type="text" name="eventDate" value="" class="input-mini" />
        <button class="btn btn-mini btn-success"><?php echo __('create');?></button>
    </div>
	<h1>
        <i class="icon-calendar"></i> <?php echo __('Events');?>
    </h1>
    
</header>

<?php echo $this->Former->calendar($calendarOptions, $events);?>