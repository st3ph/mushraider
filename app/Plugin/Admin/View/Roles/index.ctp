<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Roles list');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="icon-plus"></i> '.__('Add user role'), '/admin/roles/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <table class="table table-bordered table-striped responsive">
            <thead>
                <tr>
                    <th class="span2"><?php echo __('Title');?></th>
                    <th class="span2"><?php echo __('Alias');?></th>
                    <th class="span3"><?php echo __('Description');?></th>
                    <th class="span3"><?php echo __('Permissions');?></th>
                    <th class="actions span2"><?php echo __('Actions');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($roles)):?>
                    <?php foreach($roles as $role):?>
                        <tr>
                            <td><?php echo $role['Role']['title'];?></td>
                            <td><?php echo $role['Role']['alias'];?></td>
                            <td><?php echo $role['Role']['description'];?></td>
                            <td>
                                <?php if(!empty($role['RolePermissionRole'])):?>
                                    <ul>
                                        <?php foreach($role['RolePermissionRole'] as $rolePermissionRole):?>
                                            <li><?php echo $rolePermissionRole['RolePermission']['title'];?></li>
                                        <?php endforeach;?>
                                    </ul>
                                <?php endif;?>
                            </td>
                            <td class="actions">
                                <?php if($role['Role']['id'] != 1):?>
                                    <?php echo $this->Html->link('<i class="icon-edit"></i>', '/admin/roles/edit/'.$role['Role']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>