<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Roster');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Roster', array('url' => '/admin/rosters', 'class' => 'form-inline'));?>
            <h4><?php echo __('Select a game');?></h4>
            <?php echo $this->Form->input('Roster.game_id', array('type' => 'select', 'label' => false, 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5', 'div' => false));?>
            <?php echo $this->Form->input('Roster.type', array('type' => 'select', 'label' => false, 'default' => 'all', 'options' => $characterTypes, 'class' => 'span2', 'div' => false));?>
            <?php echo $this->Form->submit(__('View roster'), array('class' => 'btn btn-success', 'div' => false));?>
        <?php echo $this->Form->end();?>

        <?php if(!empty($this->data['Roster'])):?>
            <h3><?php echo __('Roster of');?> <?php echo $gamesList[$this->data['Roster']['game_id']];?></h3>
        <?php endif;?>

        <?php if(!empty($characters)):?>
            <div id="summary">
                <h4><?php echo __('Summary');?></h4>
                <table class="table table-bordered table-striped responsive">
                    <thead>
                        <tr>
                            <?php foreach($rolesList as $role):?>
                                <th><?php echo $role;?></th>
                            <?php endforeach;?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php foreach($rolesList as $roleId => $role):?>
                                <?php $total = count(Set::extract('/Character[default_role_id='.$roleId.']', $characters));?>
                                <th class="text-center"><?php echo $total;?></th>
                            <?php endforeach;?>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h4><?php echo __('Roster');?></h4>
            <table class="table table-bordered table-striped responsive" id="datatable">
                <thead>
                    <tr>
                        <th><?php echo __('Character');?></th>                    
                        <th><?php echo __('Level');?></th>                    
                        <th><?php echo __('Default Role');?></th>                    
                        <th><?php echo __('Classe');?></th>                    
                        <th><?php echo __('Race');?></th>                    
                        <th><?php echo __('Type');?></th>                    
                        <th><?php echo __('User');?></th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach($characters as $character):?>
                            <tr>
                                <td>
                                    <?php echo $character['Character']['title'];?>
                                </td>
                                <td>
                                    <?php echo $character['Character']['level'];?>
                                </td>
                                <td>
                                    <?php echo $character['RaidsRole']['title'];?>
                                </td>
                                <td>
                                    <span style="color:<?php echo $character['Classe']['color'];?>"><?php echo $character['Classe']['title'];?></span>
                                </td>
                                <td>
                                    <?php echo $character['Race']['title'];?>
                                </td>
                                <td>
                                    <?php if($character['Character']['main']):?>
                                        <?php echo __('Main');?>
                                    <?php else:?>
                                        <?php echo __('Reroll');?>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?php echo $character['User']['username'];?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                </tbody>
            </table>

            <br/>
            <br/>
            <h4><?php echo __('Incoming absences');?></h4>
            <table class="table table-bordered table-striped responsive" id="datatable">
                <thead>
                    <tr>
                        <th><?php echo __('User');?></th>
                        <th><?php echo __('Character(s)');?></th>                    
                        <th><?php echo __('From');?></th>                    
                        <th><?php echo __('To');?></th>                    
                        <th><?php echo __('Comment');?></th>                    
                    </tr>
                </thead>            
                <tbody>
                    <?php if(!empty($absents)):?>
                        <?php foreach($absents as $absent):?>
                            <tr>
                                <td><?php echo $absent['User']['username'];?></td>
                                <td>
                                    <?php $i = 0;?>
                                    <?php foreach($absent['User']['Character'] as $character):?>
                                        <?php echo $i?', ':'';?>
                                        <span class="character" style="color:<?php echo $character['Classe']['color'];?>">
                                            <?php echo $character['title'];?>
                                            (<?php echo (empty($character['Classe']['icon'])?$character['Classe']['title'].' ':'').$character['level'];?>)
                                        </span>
                                        <?php $i++;?>
                                    <?php endforeach;?>
                                </td>
                                <td><?php echo $this->Former->date($absent['Availability']['start'], 'jour');?></td>
                                <td><?php echo $this->Former->date($absent['Availability']['end'], 'jour');?></td>
                                <td><?php echo $absent['Availability']['comment'];?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                </tbody>
            </table>
        <?php else:?>
            <h3 class="muted"><?php echo __('You don\'t have any player yet');?></h3>
        <?php endif;?>
    </div>
</div>