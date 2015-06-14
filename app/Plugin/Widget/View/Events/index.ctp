<?php if(!empty($events)):?>
    <ul class="unstyled widgetEventsIndex">
        <?php foreach($events as $event):?> 
            <?php $attendies = 0;?>
            <?php if(!empty($event['EventsRole'])):?>
                <?php foreach($event['EventsRole'] as $eventRole):?>
                    <?php $attendies += $eventRole['count'];?>
                <?php endforeach;?>
            <?php endif;?>


            <li>
                <div class="row-fluid">
                    <div class="span1">
                        <?php echo $this->Html->image($event['Game']['logo'], array('alt' => $event['Game']['title']));?>
                    </div>
                    <div class="span11">
                        <strong><?php echo $this->Html->link(!empty($event['Event']['title'])?$event['Event']['title']:$event['Dungeon']['title'], '/events/view/'.$event['Event']['id'], array('target' => '_blank', 'escape' => false));?></strong><br />
                        <?php echo $event['Dungeon']['title'];?>
                        <?php if(!empty($event['Dungeon']['icon'])):?>
                            <?php echo $this->Html->image($event['Dungeon']['icon'], array('width' => 16, 'alt' => $event['Dungeon']['title']));?>
                        <?php endif;?>
                        <small>(<?php echo count($this->Former->extractUsersWithStatus($event['EventsCharacter'], 2));?> / <?php echo $attendies;?>)</small>
                        <div class="roster">
                            <small>
                                <?php echo __('Roster');?> :
                                <?php echo count($this->Former->extractUsersWithStatus($event['EventsCharacter'], 2));?> / <?php echo $attendies;?>
                                <?php if($user):?>
                                    <?php if($registeredCharacter = $this->Tools->getRegisteredCharacter($user['User']['id'], $event['EventsCharacter'])):?>
                                        <span class="text-success"><?php echo __('Registered as');?> <?php echo $registeredCharacter['title'];?>
                                    <?php elseif($availibleCharacters = $this->Tools->getAvailableCharacter($user, $event)):?>
                                        <?php echo $this->Html->link(__('Register').' <i class="fa fa-external-link"></i>', '/events/view/'.$event['Event']['id'], array('class' => '', 'target' => '_blank', 'escape' => false));?>
                                    <?php endif;?>
                                <?php endif;?>
                            </small>
                        </div>
                        <div class="date"><?php echo $this->Tools->niceDate($event['Event']['time_invitation'], true);?></div>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
<?php else:?>
    <p class="text-center"><?php echo __('There is no incoming event');?></p>
<?php endif;?>