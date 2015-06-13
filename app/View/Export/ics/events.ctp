BEGIN:VCALENDAR
PRODID:-//MushRaider//MushRaider Calendar v<?php echo Configure::read('mushraider.version');?>//EN
VERSION:2.0
CALSCALE:GREGORIAN
X-WR-CALNAME:MushRaider
X-WR-TIMEZONE:<?php echo $timezone;?>
X-PUBLISHED-TTL:PT12H
<?php if(!empty($events)):?>
    <?php foreach($events as $event):?>
        BEGIN:VEVENT
        TRANSP:TRANSPARENT
        UID:<?php echo Security::hash('MushRaider-'.$event['Event']['id']).PHP_EOL;?>
        STATUS:CONFIRMED<?php echo PHP_EOL;?>
        SUMMARY:<?php echo ((!empty($event['Event']['title']) && $calendarOptions->title == 'event')?$event['Event']['title']:$event['Dungeon']['title']).PHP_EOL;?>
        LOCATION:<?php echo $event['Game']['title'].PHP_EOL;?>
        DTSTART:<?php echo gmdate('Ymd\THis\Z', strtotime($event['Event']['time_invitation'])).PHP_EOL;?>
        DTEND:<?php echo gmdate('Ymd\THis\Z', strtotime($event['Event']['time_invitation'])).PHP_EOL;?>
        DESCRIPTION:<?php echo $this->Tools->escapeCalendarString(strip_tags($this->Tools->br2nl($event['Event']['description']))).PHP_EOL;?>
        URL;VALUE=URI:<?php echo $this->Tools->escapeCalendarString(Router::url('/', true).'events/view/'.$event['Event']['id']);?>
        END:VEVENT
    <?php endforeach;?>
<?php endif;?>
END:VCALENDAR