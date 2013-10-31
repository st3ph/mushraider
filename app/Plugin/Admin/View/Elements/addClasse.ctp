<h3><?php echo __('Add new class');?></h3>
<?php echo $this->Form->create('Classe', array('url' => '/admin/classes/add', 'class' => ''));?>
    <div class="form-group">
        <?php echo $this->Form->input('Classe.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
    </div>
    <?php if(!empty($gamesList)):?>
        <div class="form-group">
            <?php echo $this->Form->input('Classe.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5'));?>
        </div>
    <?php endif;?>
    <div class="form-group">
        <?php echo $this->Form->input('Classe.color', array('type' => 'text', 'required' => true, 'label' => __('Color'), 'class' => 'span1 colorpicker'));?>
    </div>
    <div class="form-group">                
         <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
    </div>
<?php echo $this->Form->end();?>