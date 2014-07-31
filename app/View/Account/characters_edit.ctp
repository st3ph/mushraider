<header>
    <h1><i class="icon-edit"></i> <?php echo __('Edit character');?></h1>
</header>

<?php echo $this->Form->create('Character', array('url' => '/account/characters/edit/c:'.$this->request->params['named']['c'], 'enctype' => 'multipart/form-data'));?>
    <div class="form-group">
        <?php echo $this->Form->input('Character.title', array('type' => 'text', 'required' => true, 'label' => __('Character Name'), 'class' => 'form-control'));?>
    </div>
    <div class="form-group">
        <?php echo $this->Form->input('Character.game_id', array('type' => 'select', 'required' => true, 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'class' => 'form-control'));?>
    </div>

    <div id="objectsPlaceholder">
        <?php echo $this->element('char_form_elements');?>
    </div>

    <div class="form-group">                
         <?php echo $this->Form->submit(__('Edit'), array('class' => 'btn btn-primary'));?>
    </div>
    <?php echo $this->Form->input('Character.id', array('type' => 'hidden'));?>
<?php echo $this->Form->end();?>        
