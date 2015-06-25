<h3><?php echo __('Add new race');?></h3>
<?php echo $this->Form->create('Race', array('url' => '/admin/races/add', 'class' => ''));?>
    <div class="form-group">
        <?php echo $this->Form->input('Race.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
    </div>
    <?php if(!empty($gamesList)):?>
        <div class="form-group">
            <?php echo $this->Form->input('Race.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5'));?>
        </div>
    <?php endif;?>
    <div class="form-group">                
         <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-large btn-success', 'div' => array('class' => 'submit pull-right')));?>               
    </div>
<?php echo $this->Form->end();?>