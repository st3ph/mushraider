<?php if(!empty($events)):?>
    <ul class="unstyled widgetEventsIndex">
        <?php foreach($events as $event):?>        
            <li>
                <div class="row-fluid">
                    <div class="span1">
                        <?php echo $this->Html->image($event['Game']['logo'], array('alt' => $event['Game']['title']));?>
                    </div>
                    <div class="span11">
                        <strong><?php echo $event['Event']['title'];?></strong><br />
                        <?php echo $event['Dungeon']['title'];?>
                        <?php if(!empty($event['Dungeon']['icon'])):?>
                            <?php echo $this->Html->image($event['Dungeon']['icon'], array('width' => 16, 'alt' => $event['Dungeon']['title']));?>
                        <?php endif;?>
                        <div class="date"><?php echo $this->Tools->niceDate($event['Event']['time_invitation'], true);?></div>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
<?php endif;?>