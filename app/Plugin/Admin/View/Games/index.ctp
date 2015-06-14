<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-list "></i></div>
        <h5><?php echo __('Games list');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="fa fa-refresh"></i> '.__('Check for updates'), '', array('id' => 'checkUpdates', 'escape' => false));?></li>
                <li><?php echo $this->Html->link('<i class="fa fa-cloud-download"></i> '.__('Import game'), '/admin/games/import', array('escape' => false));?></li>
                <li><?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('Add game'), '/admin/games/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <table class="table table-bordered table-striped responsive gamesList">
            <thead>
                <tr>
                    <th class="span2"><?php echo __('Title');?></th>                    
                    <th class="span3"><?php echo __('Dungeons');?></th>                    
                    <th class="span2"><?php echo __('Classes');?></th>                    
                    <th class="span2"><?php echo __('Races');?></th>                    
                    <th class="span1"><?php echo __('Events');?></th>                    
                    <th class="span1"><?php echo __('Roster');?></th>
                    <th class="actions span1"><?php echo __('Actions');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($games)):?>
                    <?php foreach($games as $game):?>
                        <tr>
                            <td>
                                <?php if(!empty($game['Game']['logo'])):?>
                                    <?php echo $this->Html->image($game['Game']['logo'], array('width' => 32));?>
                                <?php endif;?>
                                <?php echo $game['Game']['title'];?>
                            </td>
                            <td class="listing">
                                <?php if(!empty($game['Dungeon'])):?>
                                    <?php foreach($game['Dungeon'] as $key => $dungeon):?>
                                        <span>
                                            <?php echo $key?', ':'';?>
                                            <?php if(!empty($dungeon['icon'])):?>
                                                <?php echo $this->Html->image($dungeon['icon'], array('width' => 16));?>
                                            <?php endif;?>
                                            <?php echo $dungeon['title'];?>
                                        </span>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </td>
                            <td class="listing">
                                <?php if(!empty($game['Classe'])):?>
                                    <?php foreach($game['Classe'] as $key => $classe):?>
                                        <span>
                                            <?php echo $key?', ':'';?>
                                            <span style="color:<?php echo $classe['color'];?>">
                                                <?php if(!empty($classe['icon'])):?>
                                                    <?php echo $this->Html->image($classe['icon'], array('width' => 16));?>
                                                <?php endif;?>
                                                <?php echo $classe['title'];?>
                                            </span>
                                        </span>
                                    <?php endforeach;?>
                                <?php endif;?>                                
                            </td>
                            <td class="listing">
                                <?php if(!empty($game['Race'])):?>
                                    <?php foreach($game['Race'] as $key => $race):?>
                                        <span><?php echo $key?', ':'';?><?php echo $race['title'];?></span>
                                    <?php endforeach;?>
                                <?php endif;?>                                
                            </td>
                            <td><?php echo count($game['Event']);?></td>
                            <td><?php echo count($game['Character']);?></td>
                            <td class="actions">
                                <?php if(!empty($game['Game']['import_slug'])):?>
                                    <?php echo $this->Html->link('<i class="fa fa-cloud-download"></i>', '', array('class' => 'btn btn-mini tt updateGame', 'title' => __('Update'), 'data-slug' => $game['Game']['import_slug'], 'escape' => false))?>
                                <?php endif;?>
                                <?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>', '/admin/games/edit/'.$game['Game']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                <?php echo $this->Html->link('<i class="fa fa-trash"></i>', '/admin/games/delete/'.$game['Game']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the game %s ?', $game['Game']['title']), 'escape' => false))?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
        <?php echo $this->Tools->pagination('Game');?>
    </div>
</div>