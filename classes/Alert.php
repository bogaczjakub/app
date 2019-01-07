<?php

class Alert
{
    public $alert_array = array();
    public $new_alert = array('alert_type' => '', 'alert_title' => '', 'alert_message' => '');

    public function __construct($alert)
    {
        if (!empty($alert)) {
            $this->alert_array = $alert;
        }
    }

    public function getAlert(string $type = '', string $title = '', string $message = '')
    {
        if (!isset($this->alert_array)) {
            $this->alert_array['type'] = $type;
            $this->alert_array['title'] = $title;
            $this->alert_array['message'] = $message;
        }
        if ($this->setType($this->alert_array['type'])) {
            $this->setTitle($this->alert_array['title']);
            $this->setMessage($this->alert_array['message']);
        }
        return $this->new_alert;
    }

    private function setType(string $type)
    {
        if (in_array(strtolower($type), array('warning', 'danger', 'success', 'info'))) {
            $this->new_alert['alert_type'] = $type;
            return true;
        }
    }

    private function setTitle(string $title)
    {
        $this->new_alert['alert_title'] = $title;
    }

    private function setMessage(string $message)
    {
        $this->new_alert['alert_message'] = $message;
    }

}
