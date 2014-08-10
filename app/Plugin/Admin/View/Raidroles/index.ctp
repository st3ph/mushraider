<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Player roles list');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="icon-plus"></i> '.__('Add player role'), '/admin/raidroles/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <table class="table table-bordered table-striped responsive">
            <thead>
                <tr>
                    <th><?php echo __('Title');?></th>
                    <th><?php echo __('Display order');?></th>
                    <th class="actions"><?php echo __('Actions');?></th>
                </tr>
            </thead>
            <tbody class="sortableTbody" data-model="RaidsRole">
                <?php if(!empty($raidRoles)):?>
                    <?php foreach($raidRoles as $raidRole):?>
                        <tr id="orderdata_<?php echo $raidRole['RaidsRole']['id'];?>">
                            <td><?php echo $raidRole['RaidsRole']['title'];?></td>
                            <td>
                                <?php echo $raidRole['RaidsRole']['order'];?>
                                <span class="icon-move muted pull-right"></span>
                            </td>
                            <td class="actions">
                                <?php echo $this->Html->link('<i class="icon-edit"></i>', '/admin/raidroles/edit/'.$raidRole['RaidsRole']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                <?php echo $this->Html->link('<i class="icon-trash"></i>', '/admin/raidroles/delete/'.$raidRole['RaidsRole']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the role %s ?', $raidRole['RaidsRole']['title']), 'escape' => false))?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
        <p class="text-right jsonMsg"></p>
    </div>
</div>