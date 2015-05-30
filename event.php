<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of event
 *
 * @author Gabe
 */
class event {
    private $type;
    private $chung;
    private $hong;
    private $eventName;
    private $eventId;
    
    
    function __construct($eventType,$evName){
        $this->type = $eventType;
        $this->eventId = $evName;
    }
    
    
    
}

?>
