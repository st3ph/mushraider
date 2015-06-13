BEGIN:VCALENDAR<?php echo "\r\n";?>
PRODID:-//MushRaider//MushRaider Calendar v<?php echo Configure::read('mushraider.version');?>//EN<?php echo "\r\n";?>
VERSION:2.0<?php echo "\r\n";?>
CALSCALE:GREGORIAN<?php echo "\r\n";?>
X-WR-CALNAME:MushRaider<?php echo "\r\n";?>
X-WR-TIMEZONE:<?php echo $timezone;?><?php echo "\r\n";?>
X-PUBLISHED-TTL:PT12H<?php echo "\r\n";?>
<?php if(!empty($events)):?><?php foreach($events as $event):?>
BEGIN:VEVENT<?php echo "\r\n";?>
TRANSP:TRANSPARENT<?php echo "\r\n";?>
UID:<?php echo Security::hash('MushRaider-'.$event['Event']['id']);?><?php echo "\r\n";?>
STATUS:CONFIRMED<?php echo "\r\n";?>
SUMMARY:<?php echo (!empty($event['Event']['title']) && $calendarOptions->title == 'event')?$event['Event']['title']:$event['Dungeon']['title'];?><?php echo "\r\n";?>
LOCATION:<?php echo $event['Game']['title'];?><?php echo "\r\n";?>
DTSTART:<?php echo gmdate('Ymd\THis\Z', strtotime($event['Event']['time_invitation']));?><?php echo "\r\n";?>
DTEND:<?php echo gmdate('Ymd\THis\Z', strtotime($event['Event']['time_invitation']) + 3600);?><?php echo "\r\n";?>
DESCRIPTION:<?php echo $this->Tools->escapeCalendarString(strip_tags($this->Tools->br2nl($event['Event']['description'])));?><?php echo "\r\n";?>
URL;VALUE=URI:<?php echo $this->Tools->escapeCalendarString(Router::url('/', true).'events/view/'.$event['Event']['id']);?><?php echo "\r\n";?>
END:VEVENT<?php echo "\r\n";?>
<?php endforeach;?><?php endif;?>
END:VCALENDAR<?php echo "\r\n";?>