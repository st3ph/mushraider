<table width="550" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top">
        <td width="550" valign="top" align="left">
            <table cellspacing="0" cellpadding="4" bgcolor="#313131" style="color:#ffffff;">
                <tr>
                    <td>
                        <h2><?php echo __('Event updated');?></h2>
                    </td>
                </tr>
            </table>

            <p><?php echo __('You receive this email because an event has been updated');?> :</p>
            <p><?php echo __('Title');?> : <strong><?php echo $event['Event']['title'];?></strong></p>
            <p><?php echo __('Description');?> : <?php echo $event['Event']['description'];?></p>
            <p><?php echo __('Invitation time');?> : <?php echo $this->Former->date($event['Event']['time_invitation']);?></p>
            <p><?php echo __('Event start');?> : <?php echo $this->Former->date($event['Event']['time_start']);?></p>
            <br />
            <p><?php echo __('To view all the new details about this event please follow this link');?> : <?php echo $this->Html->link('http://'.$_SERVER['HTTP_HOST'].$this->webroot.'events/view/'.$event['Event']['id'], 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'events/view/'.$event['Event']['id'], array('escape' => false));?></p>

            <br />
            <br />
        </td>
    </tr>
</table>