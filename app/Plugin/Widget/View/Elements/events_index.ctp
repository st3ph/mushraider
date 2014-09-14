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
    <?php echo $this->Form->input('Widget.params.days', array('type' => 'select', 'label' => __('Period'), 'options' => array('1' => '1 '.__('day'), '3' => '3 '.__('days'), '5' => '5 '.__('days'), '7' => '7 '.__('days'), '10' => '10 '.__('days'), '15' => '15 '.__('days'), '30' => '30 '.__('days')), 'default' => 7, 'class' => 'span5'));?>
</div>