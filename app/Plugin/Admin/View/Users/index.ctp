<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-list "></i></div>
        <h5><?php echo __('Users list');?></h5>
        <div class="toolbar">
        <ul class="nav">
            <li>
                <?php echo $this->Form->create('Search', array('url' => $this->here, 'class' => 'form-inline'));?>                    
                    <div class="input-append">
                        <?php echo $this->Form->input('Search.needle', array('type' => 'text', 'required' => false, 'label' => false, 'placeholder' => __('Search...'), 'div' => false, 'class' => 'span10'));?>
                        <button class="btn btn-primary" type="submit"><span class="fa fa-search"></span></button>
                    </div>
                <?php echo $this->Form->end();?>
            </li>
        </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <table class="table table-bordered table-striped responsive">
            <thead>
                <tr>
                    <th class="span2">
                        <?php $directionIcon = 'fa fa-sort-alpha-asc';?>
                        <?php $directionIcon = ($this->Paginator->sortKey('User') == 'User.username' && $this->Paginator->sortDir('User') == 'desc')?'fa fa-sort-alpha-asc-alt':$directionIcon;?>
                        <?php echo $this->Paginator->sort('User.username', __('Username').' <i class="'.$directionIcon.'"></i>', array('escape' => false));?>
                    </th>
                    <th class="span3">
                        <?php $directionIcon = 'fa fa-sort-alpha-asc';?>
                        <?php $directionIcon = ($this->Paginator->sortKey('User') == 'User.email' && $this->Paginator->sortDir('User') == 'desc')?'fa fa-sort-alpha-asc-alt':$directionIcon;?>
                        <?php echo $this->Paginator->sort('User.email', __('Email').' <i class="'.$directionIcon.'"></i>', array('escape' => false));?>
                    </th>                    
                    <th class="span1"><?php echo __('Status');?></th>                    
                    <th class="span3">
                        <?php $directionIcon = 'fa fa-sort-numeric-asc';?>
                        <?php $directionIcon = ($this->Paginator->sortKey('User') == 'User.created' && $this->Paginator->sortDir('User') == 'desc')?'fa fa-sort-numeric-asc-alt':$directionIcon;?>
                        <?php echo $this->Paginator->sort('User.created', __('Registration date').' <i class="'.$directionIcon.'"></i>', array('escape' => false));?>
                    </th>                    
                    <th class="span1"><?php echo __('Characters');?></th>
                    <th class="span1"><?php echo __('Role');?></th>
                    <th class="actions span1"><?php echo __('Actions');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($users)):?>
                    <?php foreach($users as $user):?>
                        <tr>
                            <td><?php echo $user['User']['username'];?></td>
                            <td><?php echo $user['User']['email'];?></td>
                            <td>
                                <?php if($user['User']['status']):?>
                                    <i class="text-success fa fa-check"></i>
                                <?php else:?>
                                    <i class="text-warning fa fa-exclamation-triangle"></i>
                                <?php endif;?>
                            </td>
                            <td><?php echo $this->Former->date($user['User']['created']);?></td>
                            <td><?php echo count($user['Character']);?></td>
                            <td><?php echo $user['Role']['title'];?></td>
                            <td class="actions">
                                <?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>', '/admin/users/edit/'.$user['User']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                <?php if(!$user['User']['status']):?>
                                    <?php echo $this->Html->link('<i class="fa fa-check"></i>', '/admin/users/enable/'.$user['User']['id'], array('class' => 'btn btn-success btn-mini tt', 'title' => __('Enable'), 'escape' => false))?>
                                    <?php echo $this->Html->link('<i class="fa fa-trash"></i>', '/admin/users/delete/'.$user['User']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the user %s ?', $user['User']['username']), 'escape' => false))?>
                                <?php else:?>
                                    <?php echo $this->Html->link('<i class="fa fa-minus-square-o"></i>', '/admin/users/disable/'.$user['User']['id'], array('class' => 'btn btn-warning btn-mini tt delete', 'title' => __('Disable'), 'data-confirm' => __('Are you sure you want to disable the user %s ?', $user['User']['username']), 'escape' => false))?>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
        <?php echo $this->Tools->pagination('User');?>
    </div>
</div>