<h3><?php echo __('Add new class');?></h3>
<?php echo $this->Form->create('Classe', array('url' => '/admin/classes/add', 'class' => '', 'enctype' => 'multipart/form-data'));?>
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
    <?php if(isset($isNotAjax)):?>
        <div class="form-group">
            <?php echo $this->Form->input('Classe.icon', array('type' => 'file', 'label' => __('Icon').' (64px / 64px)', 'class' => 'span5 imageupload'));?>
            <div id="previewcanvascontainer">
                <canvas id="previewcanvas" class="w64"></canvas>
            </div>
        </div>
    <?php endif;?>
    <div class="form-group">                
         <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
    </div>
<?php echo $this->Form->end();?>