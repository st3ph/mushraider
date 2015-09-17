<table width="550" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top">
        <td width="550" valign="top" align="left">
            <table cellspacing="0" cellpadding="4" bgcolor="#313131" style="color:#ffffff;">
                <tr>
                    <td>
                        <h2><?php echo __('New event comment');?></h2>
                    </td>
                </tr>
            </table>

            <p><?php echo __('You receive this email because there is a new comment on the event');?> : <b><?php echo $comment['Event']['title'];?></b></p>
            <br/>
            <p><?php echo __('Author');?> : <?php echo $comment['User']['username'];?></p>
            <p><?php echo __('Comment');?> : <?php echo $comment['Comment']['comment'];?></p>

            <br />
            <br />
            <p><?php echo __('To view it use the following link : ');?> :</p>
            <p><?php echo $this->Html->link('http://'.$_SERVER['HTTP_HOST'].$this->webroot.'events/view/'.$comment['Event']['id'], 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'events/view/'.$comment['Event']['id'], array('escape' => false));?></p>
            
            <br />
            <br />
        </td>
    </tr>
</table>