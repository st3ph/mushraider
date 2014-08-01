<h3><?php echo __('System check');?></h3>

<p><?php echo __('MushRaider have a minimum system requirement to work correctly like PHP 5.3, a MySQL database and write permission on some directories.');?></p>
<p><?php echo __('Below is a list of automatic check to ensure that your installation will works correctly.');?></p>

<hr />

<ul class="unstyled">
    <li>
        <div class="row">
            <div class="span3"><?php echo __('PHP 5.3 or greater');?></div>
            <div class="span2"><span class="text-<?php echo $systemCheck['php']['passed']?'success':'error';?>"><?php echo $systemCheck['php']['passed']?__('passed'):__('failed');?> (<?php echo $systemCheck['php']['version'];?>)</span></div>
        </div>
    </li>
    <li>
        <div class="row">
            <div class="span3"><?php echo __('MySQL driver');?></div>
            <div class="span2"><span class="text-<?php echo $systemCheck['mysql']['passed']?'success':'error';?>"><?php echo $systemCheck['mysql']['passed']?__('passed'):__('failed');?></span></div>
        </div>
    </li>
    <li>
        <div class="row">
            <div class="span3"><?php echo __('Apache mod_rewrite');?></div>
            <?php if(isset($systemCheck['rewrite']['warning'])):?>
                <div class="span2"><span class="text-warning"><?php echo __('???');?></span></div>
            <?php else:?>
                <div class="span2"><span class="text-<?php echo $systemCheck['rewrite']['passed']?'success':'error';?>"><?php echo $systemCheck['rewrite']['passed']?__('passed'):__('failed');?></span></div>
            <?php endif;?>
        </div>
    </li>
    <li>
        <div class="row">
            <div class="span3">/app/Config <?php echo __('writable');?></div>
            <div class="span2"><span class="text-<?php echo $systemCheck['config']['passed']?'success':'error';?>"><?php echo $systemCheck['config']['passed']?__('passed'):__('failed');?></span></div>
        </div>
    </li>
    <li>
        <div class="row">
            <div class="span3">/app/tmp <?php echo __('writable');?></div>
            <div class="span2"><span class="text-<?php echo $systemCheck['tmp']['passed']?'success':'error';?>"><?php echo $systemCheck['tmp']['passed']?__('passed'):__('failed');?></span></div>
        </div>
    </li>
    <li>
        <div class="row">
            <div class="span3">/app/webroot/files <?php echo __('writable');?></div>
            <div class="span2"><span class="text-<?php echo $systemCheck['files']['passed']?'success':'error';?>"><?php echo $systemCheck['files']['passed']?__('passed'):__('failed');?></span></div>
        </div>
    </li>
</ul>

<?php if($systemCheckPassed):?>
    <?php echo $this->Html->link(__('Next &raquo;'), '/install/step/2', array('class' => 'btn btn-primary pull-right', 'escape' => false));?>
<?php else:?>
    <?php echo $this->Html->link(__('Next &raquo;'), '#', array('class' => 'btn btn-inverse disabled pull-right', 'escape' => false));?>
<?php endif;?>
<div class="clearfix"></div>