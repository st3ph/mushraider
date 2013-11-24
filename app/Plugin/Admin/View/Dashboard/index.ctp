<div class="tac">
    <h3><?php echo __('Welcome to the MushRaider admin panel');?></h3>
    <p>
        <?php echo __('Use the left menu to set your raid planner as you wish, like adding the games you play, the list of dungeons, classes etc...');?>
    </p>

    <hr>

    <?php $userHtml = '<i class="icon-user icon-2x"></i>';?>
    <?php $userHtml .= '<span>'.__('Users').'</span>';?>
    <?php $userHtml .= '<span class="label mabem-inverse">'.$totalUsers.'</span>';?>
    <?php echo $this->Html->link($userHtml, '/admin/users', array('class' => 'quick-btn', 'escape' => false));?>

    <?php $charsHtml = '<i class="icon-shield icon-2x"></i>';?>
    <?php $charsHtml .= '<span>'.__('Roster').'</span>';?>
    <?php $charsHtml .= '<span class="label mabem-inverse">'.$totalCharacters.'</span>';?>
    <?php echo $this->Html->link($charsHtml, '/admin/rosters', array('class' => 'quick-btn', 'escape' => false));?>

    <?php $dungeonsHtml = '<i class="icon-home icon-2x"></i>';?>
    <?php $dungeonsHtml .= '<span>'.__('Dungeons').'</span>';?>
    <?php $dungeonsHtml .= '<span class="label mabem-inverse">'.$totalDungeons.'</span>';?>
    <?php echo $this->Html->link($dungeonsHtml, '/admin/dungeons', array('class' => 'quick-btn', 'escape' => false));?>

    <?php $gamesHtml = '<i class="icon-gamepad icon-2x"></i>';?>
    <?php $gamesHtml .= '<span>'.__('Games').'</span>';?>
    <?php $gamesHtml .= '<span class="label mabem-inverse">'.$totalGames.'</span>';?>
    <?php echo $this->Html->link($gamesHtml, '/admin/games', array('class' => 'quick-btn', 'escape' => false));?>

    <?php $eventsHtml = '<i class="icon-calendar icon-2x"></i>';?>
    <?php $eventsHtml .= '<span>'.__('Events').'</span>';?>
    <?php $eventsHtml .= '<span class="label mabem-inverse">'.$totalEvents.'</span>';?>
    <?php echo $this->Html->link($eventsHtml, '/events', array('class' => 'quick-btn', 'escape' => false));?>
</div>