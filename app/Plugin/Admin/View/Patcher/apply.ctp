<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-list "></i></div>
        <h5><?php echo __('Apply patch');?> "<?php echo $patch;?>"</h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo __('The last update of MushRaider require to <strong>patch the Database</strong> to work. Click the button below to patch now.');?>
        <?php echo $this->Form->create('Patcher', array('url' => '/admin/patcher/apply/'.$patch));?>
            <?php echo $this->Form->input('Patcher.apply', array('type' => 'hidden'));?>
            <?php echo $this->Form->submit(__('Patch now !'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit')));?>
        <?php echo $this->Form->end();?>
    </div>
</div>