<table width="550" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top">
        <td width="550" valign="top" align="left">
            <table cellspacing="0" cellpadding="4" bgcolor="#313131" style="color:#ffffff;">
                <tr>
                    <td>
                        <h2><?php echo __('You are validated to an event =)');?></h2>
                    </td>
                </tr>
            </table>

            <p><?php echo __('You receive this email because you have been validated to an event');?> :</p>
            <p><?php echo __('Title');?> : <strong><?php echo $event['title'];?></strong></p>
            <p><?php echo __('Description');?> : <?php echo $event['description'];?></p>
            <p><?php echo __('Invitation time');?> : <?php echo $this->Former->date($event['time_invitation']);?></p>
            <p><?php echo __('Event start');?> : <?php echo $this->Former->date($event['time_start']);?></p>
            <br />
            <p><?php echo __('To view all the details for this event please follow this link');?> : <?php echo $this->Html->link('http://'.$_SERVER['HTTP_HOST'].$this->webroot.'events/view/'.$event['id'], 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'events/view/'.$event['id'], array('escape' => false));?></p>
            <br />
            <br />
            <p style="font-size:10px"><?php echo __('If you don\'t want to receive those emails anymore please change your settings in your');?> <?php echo $this->Html->link(__('MushRaider account'), 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'account/settings', array('escape' => false));?></p>
            
            <br />
            <br />
        </td>
    </tr>
</table>