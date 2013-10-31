<h3><?php echo __('General informations');?></h3>
<?php echo $this->Form->create('ConfigDatabase', array('url' => '/install/step/2', 'class' => ''));?>
    <div class="row-fluid">
        <?php echo $this->Form->input('Config.sitetitle', array('type' => 'text', 'required' => true, 'label' => __('Site title'), 'class' => 'span12'));?>
    </div>

    <div class="row-fluid">
        <?php echo $this->Form->input('Config.sitelang', array('type' => 'select', 'options' => $languages, 'required' => true, 'label' => __('Site language'), 'class' => 'span6'));?>
    </div>

    <div class="row-fluid">
        <?php echo $this->Form->input('Config.adminemail', array('type' => 'text', 'required' => true, 'label' => __('Admin email'), 'class' => 'span12'));?>
    </div>

    <div class="row-fluid">
        <?php echo $this->Form->input('Config.adminlogin', array('type' => 'text', 'required' => true, 'label' => __('Admin username'), 'class' => 'span12'));?>
    </div>

    <div class="row-fluid">
        <?php echo $this->Form->input('Config.adminpassword', array('type' => 'password', 'required' => true, 'label' => __('Admin password'), 'class' => 'span12', 'pattern' => '.{6,}'));?>
    </div>

    <div class="row-fluid">
        <?php echo $this->Form->submit(__('Install MushRaider'), array('class' => 'btn btn-primary pull-right'));?>
    </div>
<?php echo $this->Form->end();?>