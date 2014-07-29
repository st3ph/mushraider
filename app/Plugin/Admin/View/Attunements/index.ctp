<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Attunements list');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="icon-plus"></i> '.__('Add attunement'), '/admin/attunements/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <?php if(!empty($attunements)):?>
            <table class="table table-bordered table-striped responsive">
                        <thead>
                            <tr>
                                <th><?php echo __('Title');?></th>                    
                                <th><?php echo __('Rank');?></th>
                                <th class="actions"><?php echo __('Actions');?></th>
                            </tr>
                        </thead>
                        <tbody>
            <?php foreach($attunements as $attunement):?>
                            <tr>
                                <td><?php echo $attunement['Attunement']['title'];?></td>
                                <td><?php echo $attunement['Attunement']['rank'];?></td>
                                <td class="actions">
                                    <?php echo $this->Html->link('<i class="icon-edit"></i>', '/admin/attunements/edit/'.$attunement['Attunement']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                    <?php echo $this->Html->link('<i class="icon-trash"></i>', '/admin/attunements/delete/'.$attunement['Attunement']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the attunement %s ?', $attunement['Attunement']['title']), 'escape' => false))?>
                                </td>
                            </tr>                
            <?php endforeach;?>
                            </tbody>
                        </table>
            <?php echo $this->Tools->pagination('Attunement');?>
        <?php else:?>
            <h3 class="muted"><?php echo __('You don\'t have any attunement yet');?></h3>
        <?php endif;?>
    </div>
</div>