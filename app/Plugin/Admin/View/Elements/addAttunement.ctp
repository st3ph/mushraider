<h3><?php echo __('Add new attunement');?></h3>

<?php echo $this->Form->create('Attunement', array('url' => '/admin/attunements/add', 'class' => ''));?>
    <div class="form-group">
        <?php echo $this->Form->input('Attunement.title', array('type' => 'text', 'required' => true, 'label' => __('Title'), 'class' => 'span5'));?>
        <?php echo $this->Form->input('Attunement.rank', array('type' => 'text', 'required' => true, 'label' => __('Rank'), 'class' => 'span5'));?>
    </div>

    <div class="form-group">
        <?php if(isset($attunementEdit)):?>
            <?php echo $this->Form->input('Attunement.id', array('type' => 'hidden'));?>
        <?php endif;?>
        <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-success', 'div' => array('class' => 'submit pull-right')));?>               
    </div>
<?php echo $this->Form->end();?>