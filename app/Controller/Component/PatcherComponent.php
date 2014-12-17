<?php
class PatcherComponent extends Component {
	var $controller;

	public function initialize(Controller $controller) {
		$this->controller = &$controller;
	}

	public function patchNeeded() {
		// bêta 2
		$sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND COLUMN_NAME='default_role_id' AND TABLE_NAME='".Configure::read('Database.prefix')."characters'";
		if(!$this->controller->Character->query($sql)) {
			return $this->redirectToPatch('/admin/patcher/apply/beta-2');
		}

        // bêta 3
        $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND COLUMN_NAME='status' AND TABLE_NAME='".Configure::read('Database.prefix')."dungeons'";
        if(!$this->controller->User->query($sql)) {
            return $this->redirectToPatch('/admin/patcher/apply/beta-3');
        }

        // v1.1
        $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND COLUMN_NAME='status' AND TABLE_NAME='".Configure::read('Database.prefix')."characters'";
        if(!$this->controller->User->query($sql)) {
            return $this->redirectToPatch('/admin/patcher/apply/v-1.1');
        }

        // v1.3
        $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND COLUMN_NAME='bridge' AND TABLE_NAME='".Configure::read('Database.prefix')."users'";
        if(!$this->controller->User->query($sql)) {
            return $this->redirectToPatch('/admin/patcher/apply/v-1.3');
        }

        // v1.3.5
        App::uses('EventsTemplate', 'Model');
        $EventsTemplate = new EventsTemplate();
        $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND COLUMN_NAME='time_invitation' AND TABLE_NAME='".Configure::read('Database.prefix')."events_templates'";        
        if(!$EventsTemplate->query($sql)) {
            return $this->redirectToPatch('/admin/patcher/apply/v-1.3.5');
        }

        // v1.4
        App::uses('RaidsRole', 'Model');
        $RaidsRole = new RaidsRole();
        $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND COLUMN_NAME='order' AND TABLE_NAME='".Configure::read('Database.prefix')."raids_roles'";
        if(!$RaidsRole->query($sql)) {
            return $this->redirectToPatch('/admin/patcher/apply/v-1.4');
        }

        // v1.4.1
        App::uses('EventsCharacter', 'Model');
        $EventsCharacter = new EventsCharacter();
        $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND COLUMN_NAME='last_notification' AND TABLE_NAME='".Configure::read('Database.prefix')."events_characters'";
        if(!$EventsCharacter->query($sql)) {
            return $this->redirectToPatch('/admin/patcher/apply/v-1.4.1');
        }

        // v1.5
        App::uses('Event', 'Model');
        $Event = new Event();
        $sql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND COLUMN_NAME='open' AND TABLE_NAME='".Configure::read('Database.prefix')."events'";
        if(!$Event->query($sql)) {
            return $this->redirectToPatch('/admin/patcher/apply/v-1.5');
        }
	}

    public function run_sql_file($mysqlLink, $location, $prefix = '') {
        //load file
        $commands = file_get_contents($location);

        //delete comments
        $lines = explode("\n",$commands);
        $commands = '';
        foreach($lines as $line) {
            $line = trim($line);
            if( $line && !$this->startsWith($line,'--') ) {
                $commands .= $line . "\n";
            }
        }

        //convert to array
        $commands = explode(";", $commands);

        //run commands
        $total = $success = 0;
        foreach($commands as $command) {
            if(trim($command)) {
                $command = $this->addPrefix($command, $prefix);
                $success += (@mysqli_query($mysqlLink, $command) == false?0:1);
                $total += 1;
            }
        }

        //return number of successful queries and total number of queries found
        return array(
            "success" => $success,
            "total" => $total
        );
    }

    private function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    private function addPrefix($command = null, $prefix = '') {
        if($command) {
            return str_replace('{prefix}', $prefix, $command);
        }
    }  

    private function redirectToPatch($redirect = null) {
        if(!empty($redirect)) {
            if(!$this->controller->user || !$this->controller->user['User']['can']['full_permissions']) {
                $redirect = '/auth/logout/info/'.__('MushRaider is down for maintenance (patching up), please come back later !');
            }

            return $this->controller->redirect($redirect);
        }
    }
}