<table width="550" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top">
        <td width="550" valign="top" align="left">
            <table cellspacing="0" cellpadding="4" bgcolor="#313131" style="color:#ffffff;">
                <tr>
                    <td>
                        <h2><?php echo __('Event cancelled :(');?></h2>
                    </td>
                </tr>
            </table>

            <p><?php echo __('You receive this email because you have signed in to an event that have been cancelled');?> :</p>
            <p>"<strong><?php echo $event['title'];?></strong>" <?php echo __('intended the');?> <?php echo $this->Former->date($event['time_start']);?> <?php echo __('has been cancelled');?></p>
            <br />
            <br />
            <p style="font-size:10px"><?php echo __('If you don\'t want to receive those emails anymore please change your settings in your');?> <?php echo $this->Html->link(__('MushRaider account'), 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'account/settings', array('escape' => false));?></p>
            
            <br />
            <br />
        </td>
    </tr>
</table>