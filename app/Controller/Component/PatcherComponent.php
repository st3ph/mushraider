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
			$this->controller->redirect('/admin/patcher/apply/beta-2');
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
}