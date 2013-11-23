<?php
 App::uses('CakeEmail', 'Network/Email');

class EmailingComponent extends Component {

    var $email;

    public function __construct() {
        // Default values
        $this->email = new CakeEmail();
        
        $host = substr_count($_SERVER['HTTP_HOST'], '.') > 1?substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.') + 1):$_SERVER['HTTP_HOST'];
        $this->email->from('mushraider@'.$host);
    }

    function signup($dest) {
        $this->email->to($dest);
        $this->email->subject(__('Welcome to MushRaider'));
        $this->email->template('signup', 'default');

        $this->email->viewVars(array(
            'dest' => $dest
        ));
        $this->email->send();
    }

    function recovery($dest, $hash) {
        $this->email->to($dest);
        $this->email->subject(__('[MushRaider] Recover your password'));
        $this->email->template('recovery', 'default');

        $this->email->viewVars(array(
            'dest' => $dest,
            'token' => $hash
        ));
        $this->email->send();
    }
}