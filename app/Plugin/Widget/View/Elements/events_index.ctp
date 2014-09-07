<?php echo $this->Html->css('Widget.styles', null, array('inline' => true));?>
<?php echo $this->Html->css('Widget.widgets/events', null, array('inline' => true));?>
<?php $this->Html->script('Widget.scripts', array('inline' => false, 'block' => 'scriptBottom'));?>
<?php $this->Html->script('Widget.widgets/events', array('inline' => false, 'block' => 'scriptBottom'));?>

<div class="form-group">
    <?php echo $this->Form->input('Widget.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
</div>
<div class="form-group">
    <?php echo $this->Form->input('Widget.params.game_id', array('type' => 'select', 'label' => __('Filter by Game'), 'options' => $gamesList, 'empty' => __('All'), 'class' => 'span5'));?>
</div>
<div class="form-group">
    <?php echo $this->Form->input('Widget.params.days', array('type' => 'select', 'label' => __('Period'), 'options' => array('1' => '1', '3' => '3', '5' => '5', '7' => '7', '10' => '10', '15' => '15', '30' => '30'), 'default' => 7, 'class' => 'span5'));?>
</div>