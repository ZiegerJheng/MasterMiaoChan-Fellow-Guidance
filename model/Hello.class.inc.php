<?php
Class Hello extends Model
{
    public function __construct()
    {
        $this->useLibs = array(
            array('name' => 'hello', 'type' => 'func')
        );
    }

    public function run()
    {
        $welcomeMsg = $this->input['_bid_'] . ', Welcome to MVC. Your role is: ' . $this->input['_brole_'];

        $this->toView('msgToUser', array('msgToUser' => $welcomeMsg));
    }
}
?>
