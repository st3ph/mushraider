<header>
    <h1><i class="icon-calendar"></i> <?php echo __('Manage calendar');?></h1>
</header>

<div class="row">
    <div class="span2">
        <?php echo $this->element('account_menu');?>
    </div>
    <div class="span9">
        <h3><?php echo __('Export to ical (Google, Outlook...)');?></h3>
        <div id="export">
            <?php echo $this->Form->input('Export.game', array('options' => $games, 'empty' => __('All games'), 'class' => 'form-control', 'label' => __('Filter the export'), 'div' => false));?>
            <button class="btn btn-success"><?php echo __('Export');?></button>

            <div class="url well wellWhite">
                <p class="text-success"><?php echo __('User the link below to export MushRaider events to your personnal calendar (Google, Outlook...)');?></p>
                <?php echo $this->Html->link('', '', array('escpae' => false));?>
            </div>
        </div>
    </div>
</div>