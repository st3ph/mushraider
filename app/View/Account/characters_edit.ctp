<header>
    <h1><i class="fa fa-pencil-square-o"></i> <?php echo __('Edit character');?></h1>
</header>

<div class="row">
    <div class="span2">
        <?php echo $this->element('account_menu');?>
    </div>
    <div class="span9">
        <?php echo $this->Form->create('Character', array('url' => '/account/characters/edit/c:'.$this->request->params['named']['c']));?>
            <div class="form-group">
                <?php echo $this->Form->input('Character.title', array('type' => 'text', 'required' => true, 'label' => __('Character Name'), 'class' => 'span5'));?>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('Character.game_id', array('type' => 'select', 'required' => true, 'label' => __('Game'), 'options' => $gamesList, 'data-error' => __('An error occur while loading'), 'empty' => '', 'class' => 'span5'));?>
            </div>

            <div id="objectsPlaceholder">
                <?php echo $this->element('char_form_elements');?>
            </div>

            <div class="form-group">                
                 <?php echo $this->Form->submit(__('Edit'), array('class' => 'btn btn-primary'));?>
            </div>
            <?php echo $this->Form->input('Character.id', array('type' => 'hidden'));?>
        <?php echo $this->Form->end();?>        
    </div>
</div>