<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Widgets');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="icon-plus"></i> '.__('Add widget'), '/admin/widgets/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <?php if(!empty($widgets)):?>
            <div class="alert alert-info">
                <h4><i class="icon-info-sign"></i> <?php echo __('How to display widget to my website ?');?></h4>
                <p><?php echo __('Simply copy the "integration code" in any html page and the widget will display in place.');?></p>
                <p><?php echo __('Be aware if you enable the domain restriction the widget will appears only in pages under the selected domain.');?></p>
            </div>
        <?php endif;?>
        <table class="table table-bordered table-striped responsive">
            <thead>
                <tr>
                    <th class="span4"><?php echo __('Title');?></th>
                    <th class="span4"><?php echo __('Integration code');?></th>
                    <th class="span2"><?php echo __('Status');?></th>
                    <th class="actions span2"><?php echo __('Actions');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($widgets)):?>
                    <?php foreach($widgets as $widget):?>
                        <tr>
                            <td><?php echo $widget['Widget']['title'];?></td>
                            <td class="integrationCode">
                                <?php $widgetUrl = Configure::read('Config.appUrl').'/widget/'.$widget['Widget']['controller'].'/'.$widget['Widget']['action'].'/'.$widget['Widget']['id'];?>
                                <?php $integrationCode = '<iframe src="'.$widgetUrl.'" width="100%" height="100%" frameborder="0"></iframe>';?>
                                <textarea><?php echo $integrationCode;?></textarea>
                            </td>
                            <td>                                
                                <?php if($widget['Widget']['status']):?>
                                    <i class="text-success icon-ok"></i>
                                <?php else:?>
                                    <i class="text-warning icon-warning-sign"></i>
                                <?php endif;?>
                            </td>
                            <td class="actions">
                                <?php echo $this->Html->link('<i class="icon-edit"></i>', '/admin/widgets/edit/'.$widget['Widget']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                <?php if($widget['Widget']['status']):?>
                                    <?php echo $this->Html->link('<i class="icon-collapse-alt"></i>', '/admin/widgets/disable/'.$widget['Widget']['id'], array('class' => 'btn btn-warning btn-mini tt delete', 'title' => __('Disable'), 'data-confirm' => __('Are you sure you want to disable the widget %s ?', $widget['Widget']['title']), 'escape' => false))?>
                                <?php else:?>
                                    <?php echo $this->Html->link('<i class="icon-check"></i>', '/admin/widgets/enable/'.$widget['Widget']['id'], array('class' => 'btn btn-success btn-mini tt', 'title' => __('Enable'), 'escape' => false))?>
                                    <?php echo $this->Html->link('<i class="icon-trash"></i>', '/admin/widgets/delete/'.$widget['Widget']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the widget %s ?', $widget['Widget']['title']), 'escape' => false))?>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>