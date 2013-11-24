<?php $gameId = '';?>
<div class="box dark">
    <header>
        <div class="icons"><i class="icon-bar-chart icon-white"></i></div>
        <h5><?php echo __('Stats');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Stats', array('url' => '/admin/stats'));?>
            <div class="form-group filter">
                <?php echo $this->Form->input('Stats.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5', 'div' => false));?>
                <?php echo $this->Form->submit(__('Filter'), array('class' => 'btn btn-success', 'div' => false));?>
            </div>
        <?php echo $this->Form->end();?>


        <table class="table table-bordered table-striped responsive" id="datatable">
            <thead>
                <tr>
                    <th><?php echo __('Character');?></th>                    
                    <th><?php echo __('User');?></th>                    
                    <th><?php echo __('Classe');?></th>                    
                    <th><?php echo __('Role');?></th>                    
                    <th><?php echo __('% validated');?></th>
                    <th><?php echo __('% not validated');?></th>                    
                    <th><?php echo __('% absence');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($characters)):?>
                    <?php foreach($characters as $character):?>
                        <tr>
                            <td><?php echo $character['Character']['title'];?></td>
                            <td><?php echo $character['User']['username'];?></td>
                            <td><span style="color:<?php echo $character['Classe']['color'];?>"><?php echo $character['Classe']['title'];?></span></td>
                            <td><?php echo $character['RaidsRole']['title'];?></td>
                            <td>
                                <?php $stat = $character['stats']['total'] > 0?round(($character['stats']['status_2'] / $character['stats']['total']) * 100, 2):0;?>
                                <?php echo $stat;?>%
                                <small class="muted">(<?php echo $character['stats']['status_2'];?> / <?php echo $character['stats']['total'];?>)</small>
                            </td>
                            <td>
                                <?php $stat = $character['stats']['total'] > 0?round(($character['stats']['status_1'] / $character['stats']['total']) * 100, 2):0;?>
                                <?php echo $stat;?>%
                                <small class="muted">(<?php echo $character['stats']['status_1'];?> / <?php echo $character['stats']['total'];?>)</small>
                            </td>
                            <td>
                                <?php $stat = $character['stats']['total'] > 0?round(($character['stats']['status_0'] / $character['stats']['total']) * 100, 2):0;?>
                                <?php echo $stat;?>%
                                <small class="muted">(<?php echo $character['stats']['status_0'];?> / <?php echo $character['stats']['total'];?>)</small>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>