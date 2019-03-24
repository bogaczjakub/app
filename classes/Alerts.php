<?php

class Alerts
{
    public $alert_array = array();
    public $new_alert = array('alert_type' => '',
        'alert_title' => '',
        'alert_message' => '',
        'alert_controller' => '',
        'alert_site' => '');

    public function __construct()
    {
    }

    public function newAlert(string $type, string $title, string $message, string $controller, string $global = null)
    {
        if (Tools::checkExisting($controller, 'controller')) {
            $this->setType($type);
            $this->setTitle($title, $controller);
            $this->setMessage($message);
            $this->setController($controller);
            $this->setSite(Page::$collection['type']);
        }
        if (!empty($this->new_alert)) {
            $db = new Db();
            extract($this->new_alert);
            $results = $db->insert("global_alerts")->
                values("'0','{$alert_controller}','{$alert_type}','{$alert_title}','{$alert_message}','{$alert_site}'")->
                execute('bool');
        }
    }

    public function getAlerts(string $controller)
    {
        if (!empty($controller)) {
            $db = new Db();
            $alerts = $db->select("id,alerts_type,alerts_title,alerts_message")->
                from("global_alerts")->
                where("alerts_page_controller='{$controller}'")->
                execute("assoc");
            if (!empty($alerts)) {
                return $alerts;
            }
        }
    }

    private function setType(string $type)
    {
        if (in_array(strtolower($type), array('warning', 'danger', 'success', 'info'))) {
            $this->new_alert['alert_type'] = htmlspecialchars(trim($type));
        } else {
            $this->new_alert['alert_type'] = 'success';
        }
    }

    private function setTitle(string $title, $controller)
    {
        if (!empty($title)) {
            $this->new_alert['alert_title'] = htmlspecialchars(trim($title));
        } else {
            if (!empty($controller)) {
                $this->new_alert['alert_title'] = $controller;
            } else {
                $this->new_alert['alert_title'] = 'Alert';
            }
        }
    }

    private function setMessage(string $message)
    {
        $this->new_alert['alert_message'] = htmlspecialchars(trim($message));
    }

    private function setController(string $controller)
    {
        $this->new_alert['alert_controller'] = $controller;
    }

    private function setSite(string $site)
    {
        $this->new_alert['alert_site'] = $site;
    }

    public static function getAlertsCount($controller)
    {
        if (!empty($controller)) {
            $db = new Db();
            $alerts_count = $db->select("COUNT(*) as count")->
                from("global_alerts")->
                where("alerts_page_controller='{$controller}'")->
                execute("assoc");
            if (!empty($alerts_count)) {
                return $alerts_count;
            }
        }
    }

    public function alertDissmis(array $request)
    {
        if (isset($request['query']['id']) && !empty($request['query']['id'])) {
            $db = new Db();
            return $db->delete()->
                from("global_alerts")->
                where("id={$request['query']['id']}")->
                execute("bool");
        }
    }
}
