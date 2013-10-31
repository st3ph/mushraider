<header>
	<h1><i class="icon-calendar"></i> <?php echo __('Events');?></h1>
</header>

<?php echo $this->Former->calendar($calendarOptions, $events);?>