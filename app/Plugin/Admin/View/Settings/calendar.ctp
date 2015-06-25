<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-cog"></i></div>
        <h5><?php echo __('Configure MushRaider');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Setting', array('url' => '/admin/settings/calendar', 'class' => 'span12', 'enctype' => 'multipart/form-data'));?>
            <h3><?php echo __('Calendar');?></h3>
            <div class="well well-white">
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.weekStartDay', array('options' => $this->Former->jour_semaine, 'label' => __('Week start day')));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.title', array('options' => array('event' => 'Event title', 'dungeon' => 'Dungeon title'), 'label' => __('Title to display')));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.timeToDisplay', array('options' => array('time_invitation' => 'Invitation time', 'time_start' => 'Event start time'), 'label' => __('Time to display')));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.gameIcon', array('type' => 'checkbox', 'label' => __('Display game icon')));?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('Setting.dungeonIcon', array('type' => 'checkbox', 'label' => __('Display dungeon icon')));?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-large btn-success pull-right'));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>    
</div>