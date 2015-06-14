<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-home"></i></div>
        <h5><?php echo __('Edit Class');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Classe', array('url' => '/admin/classes/edit/'.$this->data['Classe']['id'], 'class' => '', 'enctype' => 'multipart/form-data'));?>
            <div class="form-group">
                <?php echo $this->Form->input('Classe.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Classe.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Classe.color', array('type' => 'text', 'required' => true, 'label' => __('Color'), 'class' => 'span1 colorpicker'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Classe.icon', array('type' => 'file', 'label' => __('Icon').' (64px / 64px)', 'class' => 'span5 imageupload'));?>
                <?php echo $this->Form->input('Classe.deleteIcon', array('type' => 'hidden', 'value' => '0'));?>
                <div id="previewcanvascontainer">
                    <canvas id="previewcanvas" class="w64"></canvas>
                    <?php if(!empty($this->data['Classe']['icon'])):?>
                        <div id="currentImage">
                            <h6><?php echo __('Current icon');?> :</h6>
                            <?php echo $this->Html->image($this->data['Classe']['icon'], array('class' => ''));?>
                            <button class="btn btn-mini btn-danger" type="button"><i class="fa fa-trash"></i> <?php echo __('delete');?></button>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Classe.id', array('type' => 'hidden'));?>
                <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
            </div>
        <?php echo $this->Form->end();?>
    </div>
</div>