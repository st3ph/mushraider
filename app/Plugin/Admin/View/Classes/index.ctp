<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Classes list');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="icon-plus"></i> '.__('Add class'), '/admin/classes/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <?php if(!empty($classes)):?>
            <?php $currentGame = null;?>
            <?php $tableOpen = false;?>
            <?php foreach($classes as $classe):?>
                <?php $gameId = $classe['Classe']['game_id'];?>
                <?php if($gameId != $currentGame || !$currentGame):?>
                    <?php if($tableOpen):?>
                            </tbody>
                        </table>
                    <?php endif;?>
                    <?php if(!$currentGame && !$gameId):?>
                        <h4><?php echo __('Classes without game');?></h4>
                    <?php else:?>
                        <h4><?php echo $classe['Game']['title'];?></h4>
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
                                <td style="color:<?php echo $classe['Classe']['color'];?>">
                                    <?php echo $classe['Classe']['title'];?>
                                    <?php if(!empty($classe['Classe']['icon'])):?>
                                        <?php echo $this->Html->image($classe['Classe']['icon'], array('width' => 24));?>
                                    <?php endif;?>
                                </td>
                                <td class="actions">
                                    <?php echo $this->Html->link('<i class="icon-edit"></i>', '/admin/classes/edit/'.$classe['Classe']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                    <?php if(!$gameId):?>
                                        <?php echo $this->Html->link('<i class="icon-trash"></i>', '/admin/classes/delete/'.$classe['Classe']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the class %s ?', $classe['Classe']['title']), 'escape' => false))?>
                                    <?php endif;?>
                                </td>
                            </tr>                
            <?php endforeach;?>
                    <?php if($tableOpen):?>
                            </tbody>
                        </table>
                    <?php endif;?>
            <?php echo $this->Tools->pagination('Classe');?>
        <?php else:?>
            <h3 class="muted"><?php echo __('You don\'t have any classe yet');?></h3>
        <?php endif;?>
    </div>
</div>