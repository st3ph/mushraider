<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Templates list');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php if(!empty($templates)):?>
            <?php $currentGame = null;?>
            <?php $tableOpen = false;?>
            <?php foreach($templates as $template):?>
                <?php $gameId = $template['EventsTemplate']['game_id'];?>
                <?php if($gameId != $currentGame || !$currentGame):?>
                    <?php if($tableOpen):?>
                            </tbody>
                        </table>
                    <?php endif;?>
                    <?php if(!$currentGame && !$gameId):?>
                        <h4><?php echo __('Dungeons without game');?></h4>
                    <?php else:?>
                        <h4><?php echo $template['Game']['title'];?></h4>
                    <?php endif;?>
                    <table class="table table-bordered table-striped responsive">
                        <thead>
                            <tr>
                                <th><?php echo __('Title');?></th>                    
                                <th><?php echo __('Event title');?></th>
                                <th><?php echo __('Dungeon');?></th>
                                <th><?php echo __('Mininum level');?></th>
                                <th><?php echo __('Roles');?></th>
                                <th class="actions"><?php echo __('Actions');?></th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php $currentGame = $gameId;?>
                    <?php $tableOpen = true;?>
                <?php endif;?>
                            <tr>
                                <td><?php echo $template['EventsTemplate']['title'];?></td>
                                <td><?php echo $template['EventsTemplate']['event_title'];?></td>
                                <td><?php echo $template['Dungeon']['title'];?></td>
                                <td><?php echo $template['EventsTemplate']['character_level'];?></td>
                                <td>
                                    <?php if(!empty($template['EventsTemplatesRole'])):?>
                                        <ul class="unstyled">
                                            <?php foreach($template['EventsTemplatesRole'] as $role):?>
                                                <li><?php echo $role['RaidsRole']['title'];?> : <?php echo $role['count'];?></li>
                                            <?php endforeach;?>
                                        </ul>
                                    <?php endif;?>
                                </td>
                                <td class="actions">
                                    <?php echo $this->Html->link('<i class="icon-trash"></i>', '/admin/events/template_delete/'.$template['EventsTemplate']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the template %s ?', $template['EventsTemplate']['title']), 'escape' => false))?>
                                </td>
                            </tr>                
            <?php endforeach;?>
                    <?php if($tableOpen):?>
                            </tbody>
                        </table>
                    <?php endif;?>
        <?php else:?>
            <h3 class="muted"><?php echo __('You don\'t have any template yet');?></h3>
        <?php endif;?>
    </div>
</div>