<?php if(!empty($characters)):?>
    <table class="table table-striped responsive widgetRosterIndex">
        <thead>
            <tr>
                <th><?php echo __('Name');?></th>
                <th><?php echo __('Level');?></th>
                <th><?php echo __('Role');?></th>
                <th><?php echo __('Classe');?></th>
                <th><?php echo __('Race');?></th>
                <th><?php echo __('User');?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($characters as $character):?>
                <tr>
                    <td>
                        <?php if(!empty($character['Game']['logo'])):?>
                            <?php echo $this->Html->image($character['Game']['logo'], array('width' => 16));?>
                        <?php endif;?>
                        <?php echo $character['Character']['title'];?>
                    </td>
                    <td><?php echo $character['Character']['level'];?></td>
                    <td><?php echo $character['RaidsRole']['title'];?></td>
                    <td style="color:<?php echo $character['Classe']['color'];?>">
                        <?php echo $character['Classe']['title'];?>
                        <?php if(!empty($character['Classe']['icon'])):?>
                            <?php echo $this->Html->image($character['Classe']['icon'], array('width' => 16));?>
                        <?php endif;?>
                    </td>
                    <td><?php echo $character['Race']['title'];?></td>
                    <td><?php echo $character['User']['username'];?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
<?php endif;?>