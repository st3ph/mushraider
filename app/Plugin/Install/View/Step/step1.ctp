<h3><?php echo __('Database informations');?></h3>
<?php echo $this->Form->create('ConfigDatabase', array('url' => '/install/step/1', 'class' => ''));?>
    <div class="row-fluid">
        <?php echo $this->Form->input('Config.datasource', array('type' => 'select', 'options' => $dbDatasources, 'required' => true, 'label' => __('Database Type'), 'class' => 'span12'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('Config.host', array('type' => 'text', 'required' => false, 'label' => __('Host'), 'placeholder' => 'default : locahost', 'class' => 'span12'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('Config.database', array('type' => 'text', 'required' => true, 'label' => __('Database name'), 'class' => 'span12'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('Config.login', array('type' => 'text', 'required' => true, 'label' => __('Database User'), 'class' => 'span12'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('Config.password', array('type' => 'password', 'label' => __('Database Password'), 'class' => 'span12'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('Config.port', array('type' => 'text', 'label' => __('Port'), 'class' => 'span2'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->input('Config.prefix', array('type' => 'text', 'default' => 'mr_', 'label' => __('Tables prefix'), 'class' => 'span2'));?>
    </div>
    <div class="row-fluid">
        <?php echo $this->Form->submit(__('Next &raquo;'), array('escape' => false, 'class' => 'btn btn-primary pull-right'));?>
    </div>
<?php echo $this->Form->end();?>