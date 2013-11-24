<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Roster');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Roster', array('url' => '/admin/rosters'));?>
            <div class="form-group filter">
                <?php echo $this->Form->input('Roster.game_id', array('type' => 'select', 'label' => __('Select a game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => 'span5', 'div' => false));?>
                <?php echo $this->Form->submit(__('View roster'), array('class' => 'btn btn-success', 'div' => false));?>
            </div>
        <?php echo $this->Form->end();?>

        <?php if(!empty($characters)):?>
            <h3><?php echo __('Roster of');?> <?php echo $gamesList[$this->data['Roster']['game_id']];?></h3>

            <div id="summary">
                <h5><?php echo __('Summary');?></h5>
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

            <h5><?php echo __('Roster');?></h5>
            <table class="table table-bordered table-striped responsive" id="datatable">
                <thead>
                    <tr>
                        <th><?php echo __('Title');?></th>                    
                        <th><?php echo __('Level');?></th>                    
                        <th><?php echo __('Default Role');?></th>                    
                        <th><?php echo __('Classe');?></th>                    
                        <th><?php echo __('Race');?></th>                    
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
                                    <?php echo $character['User']['username'];?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                </tbody>
            </table>
        <?php endif;?>
    </div>
</div>