<header>
    <h1><i class="icon-time"></i> <?php echo __('Manage absences');?></h1>
</header>

<div class="row">
    <div class="span2">
        <?php echo $this->element('account_menu');?>
    </div>
    <div class="span9">
        <?php echo $this->Form->create('User', array('url' => '/account/availabilities', 'class' => 'form-inline dates'));?>
            <table class="table table-striped" id="absenceTab">
                <thead>
                    <tr>
                        <th><?php echo __('From');?></th>
                        <th><?php echo __('To');?></th>
                        <th><?php echo __('Comment');?></th>
                        <th><?php echo __('Actions');?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="addForm">
                        <td class="start">
                            <div class="input-append">
                                <?php echo $this->Form->input('Availability.start', array('type' => 'text', 'required' => true, 'label' => false, 'div' => false, 'class' => 'input-small startDate', 'placeholder' => __('From'), 'error' => false));?>
                                <span class="add-on"><span class="icon-calendar"></span></span>
                            </div>
                            <?php echo $this->Form->error('Availability.start');?>
                        </td>
                        <td class="end">
                            <div class="input-append">
                                <?php echo $this->Form->input('Availability.end', array('type' => 'text', 'required' => true, 'label' => false, 'div' => false, 'class' => 'input-small endDate', 'placeholder' => __('To'), 'error' => false));?>
                                <span class="add-on"><span class="icon-calendar"></span></span>
                            </div>
                            <?php echo $this->Form->error('Availability.end');?>
                        </td>
                        <td class="comment">
                            <?php echo $this->Form->input('Availability.comment', array('type' => 'text', 'required' => true, 'label' => false, 'div' => false, 'class' => 'span4', 'placeholder' => __('Absence reason'), 'error' => false));?>
                            <?php echo $this->Form->error('Availability.comment');?>
                        </td>
                        <td class="submit">
                            <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-warning', 'div' => false));?>
                            <?php echo $this->Form->input('Availability.id', array('type' => 'hidden', 'error' => false));?>
                        </td>
                    </tr>
                    <?php if(!empty($availabilities)):?>
                        <?php foreach($availabilities as $availability):?>
                            <tr>
                                <td class="span2 start"><?php echo $this->Former->date($availability['Availability']['start'], 'jour');?></td>
                                <td class="span2 end"><?php echo $this->Former->date($availability['Availability']['end'], 'jour');?></td>
                                <td class="span6 comment"><?php echo $availability['Availability']['comment'];?></td>
                                <td class="span2 text-right buttons">
                                    <?php if($availability['Availability']['start'] >= date('Y-m-d')):?>
                                        <?php echo $this->Html->link('<i class="icon-edit"></i>', '/account/availabilities/edit/'.$availability['Availability']['id'], array('class' => 'btn btn-info btn-mini tt edit', 'title' => __('Edit'), 'data-id' => $availability['Availability']['id'], 'escape' => false));?>
                                    <?php endif;?>
                                    <?php echo $this->Html->link('<i class="icon-trash"></i>', '/account/availabilities/delete/'.$availability['Availability']['id'], array('class' => 'btn btn-danger btn-mini tt confirm', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to delete this absence ?'), 'escape' => false))?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?> 
                </tbody>
            </table>
        <?php echo $this->Form->end();?>
    </div>
</div>