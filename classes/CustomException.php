<?php

class CustomException extends Exception
{
    public $message = '';
    
    public function getCustomMessage($exception_object)
    {
        $this->message = $exception_object->getMessage();
        echo $this->message;
        echo '<br>';
    }

}
