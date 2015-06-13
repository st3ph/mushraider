<div id="page404">
    <h2><?php echo __('Oups, something fail but it\'s not me !');?></h2>
    <small><?php echo __('or maybe yes but it\'s not my fault...');?></small>
</div>
<?php if(Configure::read('debug') > 0):?>
    <h2><?php echo $error->getMessage();?></h2>
	<?php echo $this->element('exception_stack_trace');?>
<?php endif;?>
