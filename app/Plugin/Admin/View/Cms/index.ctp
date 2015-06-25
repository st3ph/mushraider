<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-list "></i></div>
        <h5><?php echo __('Pages');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('Add page'), '/admin/cms/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <table class="table table-bordered table-striped responsive">
            <thead>
                <tr>
                    <th class="span4"><?php echo __('Title');?></th>
                    <th class="span4"><?php echo __('Link');?></th>
                    <th class="span2"><?php echo __('Visibility');?></th>
                    <th class="span2"><?php echo __('Status');?></th>
                    <th class="span2"><?php echo __('Last update');?></th>
                    <th class="actions span2"><?php echo __('Actions');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($pages)):?>
                    <?php foreach($pages as $page):?>
                        <tr>
                            <td><?php echo $page['Page']['title'];?></td>
                            <td><?php echo Configure::read('Config.appUrl').'/pages/'.$page['Page']['slug'];?></td>
                            <td><?php echo $page['Page']['public']?__('Public'):__('Private');?></td>
                            <td>
                                <?php if($page['Page']['status']):?>
                                    <i class="text-success fa fa-check"></i>
                                <?php else:?>
                                    <i class="text-warning fa fa-exclamation-triangle"></i>
                                <?php endif;?>
                            </td>
                            <td><?php echo $this->Former->date($page['Page']['modified']);?></td>
                            <td class="actions">
                                <?php if(!$page['Page']['status']):?>
                                    <?php echo $this->Html->link('<i class="fa fa-eye"></i>', '/pages/preview/'.$page['Page']['id'], array('class' => 'btn btn-default btn-mini tt', 'title' => __('Preview'), 'target' => '_blank', 'escape' => false))?>
                                <?php endif;?>
                                <?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>', '/admin/cms/edit/'.$page['Page']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                <?php if($page['Page']['status']):?>
                                    <?php echo $this->Html->link('<i class="fa fa-minus-square-o"></i>', '/admin/cms/disable/'.$page['Page']['id'], array('class' => 'btn btn-warning btn-mini tt delete', 'title' => __('Disable'), 'data-confirm' => __('Are you sure you want to disable the page %s ?', $page['Page']['title']), 'escape' => false))?>
                                <?php else:?>
                                    <?php echo $this->Html->link('<i class="fa fa-check"></i>', '/admin/cms/enable/'.$page['Page']['id'], array('class' => 'btn btn-success btn-mini tt', 'title' => __('Publish'), 'escape' => false))?>
                                    <?php echo $this->Html->link('<i class="fa fa-trash"></i>', '/admin/cms/delete/'.$page['Page']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the page %s ?', $page['Page']['title']), 'escape' => false))?>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>