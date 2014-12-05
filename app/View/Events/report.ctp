<header>
    <h1>
        <div class="row">
            <?php $displayAdminButtons = (($user['User']['can']['manage_own_events'] && $user['User']['id'] == $event['User']['id']) || $user['User']['can']['create_reports'] || $user['User']['can']['full_permissions'])?true:false?>
            <div class="span<?php echo $displayAdminButtons?8:11?>">
                <?php echo $this->Html->link('<i class="icon-chevron-left"></i>', '/events/view/'.$event['Event']['id'], array('escape' => false));?>
                <?php echo __('Report');?> : <?php echo $event['Event']['title'];?>
            </div>

            <?php if($displayAdminButtons):?>
                <div class="pull-right text-right span3">
                    <?php echo $this->Html->link('<i class="icon-edit"></i> '.__('Edit'), '/events/close/'.$event['Event']['id'], array('class' => 'btn btn-warning btn-mini', 'escape' => false));?>
                </div>
            <?php endif;?>            
        </div>
    </h1>
</header>

<h3><?php echo __('Description');?></h3>
<div class="well wellWhite"><?php echo $report['Report']['description'];?></div>

<hr />

<h3><?php echo __('Screenshots');?></h3>
<?php $screenshotDisplayed = false;?>
<ul class="inline">
    <?php for($i = 1;$i <= 4;$i++):?>
        <?php if(!empty($report['Report']['screenshot_'.$i])):?>
            <li>
                <?php echo $this->Html->link($this->Html->image('/files/reports/'.$report['Report']['screenshot_'.$i].'_t.png', array('width' => 250)), '/files/reports/'.$report['Report']['screenshot_'.$i].'_m.png', array('class' => 'lb', 'escape' => false));?>
                <div class="text-center"><small><?php echo $this->Html->link('<i class="icon-external-link"></i> '.__('view original'), '/files/reports/'.$report['Report']['screenshot_'.$i].'_o.png', array('target' => '_blank', 'escape' => false));?></small></div>
            </li>
            <?php $screenshotDisplayed = true;?>
        <?php endif;?>
    <?php endfor;?>
</ul>
<?php if(!$screenshotDisplayed):?>
    <p><?php echo __('There is no screenshot attached to this report');?></p>
<?php endif;?>

<h3><?php echo __('Comments');?></h3>
<?php echo $this->Comment->show($report, 'Report', array('connected' => $user));?>