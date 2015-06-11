<?php
App::uses('AppHelper', 'View/Helper');
App::uses('FormerHelper', 'View/Helper');
class CalendarHelper extends FormerHelper {
    var $helpers = array ('Html', 'Tools');

    private $output = '';
    private $options = '';

    function show($options = array(), $events = array()) {
        $__options = array(
            'month' => date('n'),
            'year' => date('Y'),
            'startDayOfTheWeek' => 0
        );
        $this->options = array_merge($__options, $options);

        $this->output .= '<table id="calendar">';
            $this->getHeader();
            $this->getBody($events);
        $this->output .= '</table>';

        return $this->output;
    }

    private function getHeader() {
        $prev_year = $next_year = $this->options['year'];
        $prev_month = $this->options['month'] - 1;
        $next_month = $this->options['month'] + 1;
         
        if($prev_month == 0) {
            $prev_month = 12;
            $prev_year = $this->options['year'] - 1;
        }
        if ($next_month == 13 ) {
            $next_month = 1;
            $next_year = $this->options['year'] + 1;
        }
        $monthIn2Digits = str_pad($this->options['month'], 2, "0", STR_PAD_LEFT);

        $this->output .= '<tr align="center">';
            $this->output .= '<td>';
                $this->output .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
                    $this->output .= '<tr class="links">';
                        $this->output .= '<td class="prev">'.$this->Html->link('<i class="icon-chevron-left"></i> <span>'.__('Previous').'</span>', '/events/index/m:'.$prev_month.'/y:'.$prev_year, array('escape' => false)).'</td>';
                        $this->output .= '<td class="month">'.ucfirst($this->mois[$monthIn2Digits]).' '.$this->options['year'].'</td>';
                        $this->output .= '<td class="next">'.$this->Html->link('<span>'.__('Next').'</span> <i class="icon-chevron-right"></i>', '/events/index/m:'.$next_month.'/y:'.$next_year, array('escape' => false)).'</td>';
                    $this->output .= '</tr>';
                $this->output .= '</table>';
            $this->output .= '</td>';
        $this->output .= '</tr>';
    }

    private function getBody($events) {
        $datetime = new DateTime($this->options['year'].'-'.$this->options['month'].'-1');
        $firstDayOfTheWeek = $datetime->format('w');
        $emptyCells = $firstDayOfTheWeek > $this->options['startDayOfTheWeek']?$firstDayOfTheWeek - $this->options['startDayOfTheWeek']:7 - ($this->options['startDayOfTheWeek'] - $firstDayOfTheWeek);
        $emptyCells = $emptyCells == 7?0:$emptyCells;

        $firstDay = clone $datetime;
        $nextMonth = clone $datetime;
        $nextMonth->add(new DateInterval('P1M'));
        $datetime->sub(new DateInterval('P'.$emptyCells.'D'));
        
        $this->output .= '<tr>';
            $this->output .= '<td align="center">';
                $this->output .= '<table width="100%" border="0" cellpadding="2" cellspacing="2" class="dates">';
                    $this->output .= '<tr class="days">';
                        foreach($this->jour_semaine as $jour) {
                            $this->output .= '<td>'.$jour.'</td>';
                        }
                    $this->output .= '</tr>';

                    $cellsCounter = 0;
                    while($datetime < $nextMonth) {
                        if(($cellsCounter % 7) == 0) {
                            $this->output .= '<tr>';
                        }

                        if($datetime < $firstDay) {
                            $this->output .= '<td></td>';
                        }else {
                            $this->getCell($datetime, $events);
                        }

                        if(($cellsCounter % 7) == 6) {
                            $this->output .= '</tr>';
                        }

                        $datetime->add(new DateInterval('P1D'));
                        $cellsCounter++;
                    }
                $this->output .= '</table>';
            $this->output .= '</td>';
        $this->output .= '</tr>';
    }

    private function getCell($datetime, $events) {
        $today = new DateTime(date('Y').'-'.date('m').'-'.date('d'));
        $dayClass = $datetime == $today?'currentDay':'';
        $dayClass = $datetime < $today?'pastDay':$dayClass;

        $this->output .= '<td valign="top" class="day '.$dayClass.'">';
            $this->output .= '<div class="clearfix dayNumber">';
                $this->output .= $datetime->format('d');
                if($datetime >= $today && ($this->options['user']['User']['can']['manage_own_events'] || $this->options['user']['User']['can']['manage_events'] || $this->options['user']['User']['can']['full_permissions'])) {
                    $this->output .= $this->Html->link('<i class="icon-plus-sign-alt"></i>', '/events/add/'.$datetime->format('Y-m-d'), array('title' => __('Add event'), 'class' => 'pull-right tt', 'escape' => false));
                }
            $this->output .= '</div>';

            // Check if there is events this day
            if($matchingEvents = $this->extractEvents($events, $datetime->format('Y-m-d'))) {
                $this->output .= '<ul>';
                    foreach($matchingEvents as $matchingEvent) {
                        $tooltip = $this->getTooltip($matchingEvent);

                        $this->output .= '<li>';
                            $this->output .= '<span class="time">';
                                $this->output .= $this->date($matchingEvent['Event'][$this->options['settings']['timeToDisplay']], 'heure');
                            $this->output .= '</span>';
                            if(!empty($matchingEvent['Game']['logo']) && $this->options['settings']['gameIcon']) {
                                $this->output .= $this->Html->image($matchingEvent['Game']['logo'], array('class' => 'logo', 'width' => 16));
                            }
                            if(!empty($matchingEvent['Dungeon']['icon']) && $this->options['settings']['dungeonIcon']) {
                                $this->output .= $this->Html->image($matchingEvent['Dungeon']['icon'], array('class' => 'logo', 'width' => 16));
                            }
                            // Test is the user is registered for this event
                            $registeredClass = '';
                            if($registeredCharacter = $this->Tools->getRegisteredCharacter($this->options['user']['User']['id'], $matchingEvent['EventsCharacter'])) {
                                $registeredClass = 'registered_'.$registeredCharacter['status'];
                            }
                            $eventTitle = (!empty($matchingEvent['Event']['title']) && $this->options['settings']['title'] == 'event')?$matchingEvent['Event']['title']:$matchingEvent['Dungeon']['title'];
                            $this->output .= $this->Html->link($eventTitle, '/events/view/'.$matchingEvent['Event']['id'], array('escape' => false, 'title' => $tooltip, 'class' => 'tt '.$registeredClass));
                            if(!empty($matchingEvent['Report']['id'])) {
                                $this->output .= ' <i class="icon-lock"></i>';
                            }
                        $this->output .= '</li>';
                    }                    
                $this->output .= '</ul>';
            }


        $this->output .= '</td>';
    }

    private function getTooltip($event) {
        $playersNeeded = 0;
        if($event['EventsRole']) {
            foreach($event['EventsRole'] as $eventRoles) {
                $playersNeeded += $eventRoles['count'];
            }
        }

        $tooltip = '<h5>'.$event['Event']['title'].'</h5>';
        $tooltip .= '<div>';
            $tooltip .= $event['Game']['title'].'<br/>';
            $tooltip .= $event['Dungeon']['title'].'<br/>';
            $tooltip .= __('Roster').' : '.count($this->extractUsersWithStatus($event['EventsCharacter'], 2)).'/'.$playersNeeded.'<br/>';
            $tooltip .= __('Start').' : '.$this->date($event['Event']['time_start'], 'heure');
            if($event['Event']['open']) {
                $tooltip .= '<br/>'.__('open event');
            }
        $tooltip .= '<div>';

        return $tooltip;
    }
}