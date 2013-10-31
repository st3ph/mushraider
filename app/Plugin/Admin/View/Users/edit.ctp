<div class="box dark">
    <header>
        <div class="icons"><i class="icon-user"></i></div>
        <h5><?php echo __('Edit user');?> : <?php echo $this->data['User']['username'];?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('User', array('url' => '/admin/users/edit/'.$this->data['User']['id'], 'class' => 'span12'));?>
            <div class="form-group">
                <?php echo $this->Form->input('User.status', array('type' => 'select', 'options' => array(0  => __('Disabled'), 1 => __('Enabled')), 'label' => __('Account status'), 'class' => 'span5'));?>
            </div>
            
            <?php if($user['User']['isAdmin']):?>
                <div class="form-group">
                    <?php echo $this->Form->input('User.role_id', array('type' => 'select', 'label' => __('Role & permissions'), 'options' => $rolesList, 'empty' => '', 'class' => 'span5'));?>
                    <?php if(!empty($roles)):?>
                        <div class="muted">
                            <h4><i class="icon-info-sign"></i> <?php echo __('Roles details');?></h4>
                            <ul class="unstyled">
                                <?php foreach($roles as $role):?>
                                    <li>
                                        <strong><?php echo $role['Role']['title'];?> :</strong>
                                        <?php echo $role['Role']['description'];?>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    <?php endif;?>
                </div>
            <?php endif;?>

            <div class="form-group">
                <?php echo $this->Form->input('User.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>