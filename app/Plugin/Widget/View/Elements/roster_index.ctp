<?php echo $this->Html->css('Widget.styles', null, array('inline' => true));?>
<?php echo $this->Html->css('Widget.widgets/roster', null, array('inline' => true));?>
<?php $this->Html->script('Widget.scripts', array('inline' => false, 'block' => 'scriptBottom'));?>
<?php $this->Html->script('Widget.widgets/roster', array('inline' => false, 'block' => 'scriptBottom'));?>

<div class="form-group">
    <?php echo $this->Form->input('Widget.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
</div>
<div class="form-group">
    <?php echo $this->Form->input('Widget.params.game_id', array('type' => 'select', 'label' => __('Filter by Game'), 'options' => $gamesList, 'empty' => __('All'), 'class' => 'span5'));?>
</div>