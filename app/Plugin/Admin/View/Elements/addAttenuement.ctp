<h3><?php echo __('Add new attenuement');?></h3>

<?php echo $this->Form->create('Attenuement', array('url' => '/admin/attenuements/add', 'class' => ''));?>
    <div class="form-group">
        <?php echo $this->Form->input('Attenuement.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
        <?php echo $this->Form->input('Attenuement.rank', array('type' => 'text', 'required' => true, 'label' => __('Rank'), 'class' => 'span5'));?>
    </div>

    <div class="form-group">
        <?php if(isset($attenuementEdit)):?>
            <?php echo $this->Form->input('Attenuement.id', array('type' => 'hidden'));?>
        <?php endif;?>
        <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
    </div>
<?php echo $this->Form->end();?>