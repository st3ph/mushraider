<div class="form-group">
	<?php echo $this->Form->input('Character.classe_id', array('type' => 'select', 'required' => true, 'label' => __('Classe'), 'options' => $classesList, 'empty' => '', 'class' => 'form-control'));?>
</div>

<div class="form-group">
	<?php echo $this->Form->input('Character.race_id', array('type' => 'select', 'required' => true, 'label' => __('Race'), 'options' => $racesList, 'empty' => '', 'class' => 'form-control'));?>
</div>

<div class="form-group">
	<?php echo $this->Form->input('Character.default_role_id', array('type' => 'select', 'required' => true, 'label' => __('Default Role'), 'options' => $rolesList, 'empty' => '', 'class' => 'form-control'));?>
</div>

<div class="form-group">
	<?php echo $this->Form->input('Character.level', array('type' => 'text', 'required' => true, 'label' => __('Level'), 'class' => 'form-control', 'pattern' => '[0-9]{1,3}'));?>
</div>

<div class="form-group">
	<?php echo $this->Form->input('Character.attunement_id', array('type' => 'select',  'label' => __('Attunement'), 'options' => $attunementsList, 'empty' => '', 'class' => 'form-control'));?>
</div>