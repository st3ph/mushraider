<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-list "></i></div>
        <h5><?php echo __('Races list');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('Add race'), '/admin/races/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <?php if(!empty($racesWithoutGame)):?>
            <h4><?php echo __('Races without game');?></h4>
            <table class="table table-bordered table-striped responsive">
                <thead>
                    <tr>
                        <th class="span11"><?php echo __('Title');?></th>
                        <th class="actions span1"><?php echo __('Actions');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($racesWithoutGame as $race):?>
                        <tr>
                            <td><?php echo $race['Race']['title'];?></td>
                            <td class="actions">
                                <?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>', '/admin/races/edit/'.$race['Race']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                <?php echo $this->Html->link('<i class="fa fa-trash"></i>', '/admin/races/delete/'.$race['Race']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the race %s ?', $race['Race']['title']), 'escape' => false))?>
                            </td>
                        </tr>                 
                    <?php endforeach;?>
                </tbody>
            </table>
        <?php endif;?>

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
                    
                    <h4><?php echo $race['Game']['title'];?></h4>
                    <table class="table table-bordered table-striped responsive">
                        <thead>
                            <tr>
                                <th class="span11"><?php echo __('Title');?></th>
                                <th class="actions span1"><?php echo __('Actions');?></th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php $currentGame = $gameId;?>
                    <?php $tableOpen = true;?>
                <?php endif;?>
                            <tr>
                                <td><?php echo $race['Race']['title'];?></td>
                                <td class="actions">
                                    <?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>', '/admin/races/edit/'.$race['Race']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                </td>
                            </tr>               
            <?php endforeach;?>
                    <?php if($tableOpen):?>
                            </tbody>
                        </table>
                    <?php endif;?>
        <?php else:?>
            <h3 class="muted"><?php echo __('You don\'t have any race yet');?></h3>
        <?php endif;?>
    </div>
</div>