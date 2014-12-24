<div class="box dark">
    <header>
        <div class="icons"><i class="icon-calendar"></i></div>
        <h5><?php echo __('Edit event template');?> : <?php echo $this->data['EventsTemplate']['title'];?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('EventsTemplate', array('url' => '/admin/events/template_edit/'.$this->data['EventsTemplate']['id'], 'class' => 'span12'));?>
            <div class="form-group">
                <?php echo $this->Form->input('EventsTemplate.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('EventsTemplate.event_title', array('type' => 'text', 'required' => true, 'label' => __('Event title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('EventsTemplate.event_description', array('type' => 'textarea', 'required' => false, 'label' => __('Event description'), 'class' => 'span5 wysiwyg'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('EventsTemplate.dungeon_id', array('options' => $dungeonsList, 'required' => true, 'label' => __('Dungeon'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('EventsTemplate.character_level', array('type' => 'text', 'required' => true, 'label' => __('Character minimum level'), 'class' => 'span5', 'pattern' => '[0-9]{1,3}'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('EventsTemplate.time_invitation', array('type' => 'time', 'timeFormat' => 24, 'interval' => 15, 'required' => true, 'label' => __('Invitations start'), 'class' => 'span2'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('EventsTemplate.time_start', array('type' => 'time', 'timeFormat' => 24, 'interval' => 15, 'required' => true, 'label' => __('Event start'), 'class' => 'span2'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('EventsTemplate.open', array('type' => 'checkbox', 'label' => __('Open event')));?>
            </div>

            <h5><?php echo __('Roles');?></h5>
            <ul class="unstyled">
                <?php foreach($this->data['EventsTemplatesRole'] as $role):?>
                    <div class="form-group">
                        <?php echo $this->Form->input('EventsTemplatesRole.id.'.$role['id'], array('type' => 'text', 'value' => $role['count'], 'required' => true, 'label' => $role['RaidsRole']['title'], 'class' => 'span5', 'pattern' => '[0-9]{1,3}'));?>
                    </div>
                <?php endforeach;?>
            </ul>
      
            <div class="form-group">
                <?php echo $this->Form->input('EventsTemplate.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success pull-right'));?>
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>