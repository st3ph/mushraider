<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Races list');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="icon-plus"></i> '.__('Add race'), '/admin/races/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <?php if(!empty($races)):?>
            <?php $currentGame = null;?>
            <?php $tableOpen = false;?>
            <?php foreach($races as $race):?>
                <?php $gameId = $race['Race']['game_id'];?>
                <?php if($gameId != $currentGame || !$currentGame):?>
                    <?php if($tableOpen):?>
                            </tbody>
                        </table>
                    <?php endif;?>
                    <?php if(!$currentGame && !$gameId):?>
                        <h4><?php echo __('Races without game');?></h4>
                    <?php else:?>
                        <h4><?php echo $race['Game']['title'];?></h4>
                    <?php endif;?>
                    <table class="table table-bordered table-striped responsive">
                        <thead>
                            <tr>
                                <th><?php echo __('Title');?></th>
                                <th class="actions"><?php echo __('Actions');?></th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php $currentGame = $gameId;?>
                    <?php $tableOpen = true;?>
                <?php endif;?>
                            <tr>
                                <td><?php echo $race['Race']['title'];?></td>
                                <td class="actions">
                                    <?php echo $this->Html->link('<i class="icon-edit"></i>', '/admin/races/edit/'.$race['Race']['id'], array('class' => 'btn btn-warning btn-mini', 'escape' => false))?>
                                    <?php if(!$gameId):?>
                                        <?php echo $this->Html->link('<i class="icon-remove"></i>', '/admin/races/delete/'.$race['Race']['id'], array('class' => 'btn btn-danger btn-mini delete', 'data-confirm' => __('Are you sure you want to completely delete the race %s ?', $race['Race']['title']), 'escape' => false))?>
                                    <?php endif;?>
                                </td>
                            </tr>                
            <?php endforeach;?>
                    <?php if($tableOpen):?>
                            </tbody>
                        </table>
                    <?php endif;?>
            <?php echo $this->Tools->pagination('Race');?>
        <?php else:?>
            <h3 class="muted"><?php echo __('You don\'t have any race yet');?></h3>
        <?php endif;?>
    </div>
</div>