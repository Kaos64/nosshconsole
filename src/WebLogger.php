
<?php

Use Psr\Log\AbstractLogger;

class WebLogger extends AbstractLogger
{

        
    public function __construct(){
        $this->log ="";
    }   




    public function log($level, $message, array $context = array()){
        $ext ='';
        foreach ($context  as $k => $v){
            $ext .= "[$k] => $v\r\n";
        }
        $this->log .= "$level : $message " . (strlen ($ext)? $ext: '')."<br>";
    }


    public function toString(){
        return $this->log;
    }
}
