<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-eye"></i></div>
        <h5><?php echo __('Add user group');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Role', array('url' => '/admin/roles/add', 'class' => 'span12'));?>
            <div class="form-group">
                <?php echo $this->Form->input('Role.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Role.description', array('type' => 'text', 'label' => __('Description'), 'class' => 'span5'));?>
            </div>
            <?php if(!empty($rolePermissions)):?>
                <table class="table table-bordered table-striped responsive">
                    <thead>
                        <tr>
                            <th><?php echo __('Title');?></th>
                            <th><?php echo __('Description');?></th>
                            <th><?php echo __('Enable');?></th>
                        </tr>
                    </thead>
                    <?php foreach($rolePermissions as $rolePermission):?>
                        <tr>
                            <td><?php echo $rolePermission['RolePermission']['title'];?></td>
                            <td><?php echo $rolePermission['RolePermission']['description'];?></td>
                            <td><?php echo $this->Form->input('Role.permission.'.$rolePermission['RolePermission']['id'], array('type' => 'checkbox', 'label' => false));?></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            <?php endif;?>
      
            <div class="form-group">
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>