<?php

class Alerts
{
    public $alert_array = array();
    public $new_alert = array(
        'alert_type' => '',
        'alert_title' => '',
        'alert_message' => '',
        'alert_controller' => '',
        'alert_site' => '',
    );

    public function __construct()
    { }

    public function newAlert(string $type, string $title, string $message, string $controller, string $global = null)
    {
        if (Tools::checkExisting($controller, 'controller')) {
            $this->setType($type);
            $this->setTitle($title, $controller);
            $this->setMessage($message);
            $this->setController($controller);
            $this->setSite(Page::$collection['type']);
            $this->saveAlert();
        }
    }

    public function newAlertFromRaport(string $controller, array $raport)
    {
        $controller_split = preg_split('/(?=[A-Z])/', $controller);
        $table_name = implode(' ', $controller_split);
        if ($raport['status']) {
            $title = sprintf('%s alert', $table_name);
        } else {
            $title = sprintf('There were %d errors during the operation on the %s table', $raport['failures'], $table_name);
            $message = $this->createMessageFromRaport($raport);
            $this->setType('danger');
            $this->setTitle($title, $controller);
            $this->setMessage($message);
            $this->setController($controller);
            $this->setSite(Page::$collection['type']);
            $this->saveAlert();
        }
    }

    public function newPredefinedAlert(string $category, string $event, string $type, string $controller)
    {
        if (!empty($category) && !empty($event) && !empty($type) && !empty($controller)) {
            $alert_message = self::getPredefinedAlert($category, $event, $type);
            $controller_split = preg_split('/(?=[A-Z])/', $controller);
            $title = implode(' ', $controller_split) . ' alert';
            $this->setType($type);
            $this->setTitle($title, $controller);
            $this->setMessage($alert_message[0]['alerts_static_message']);
            $this->setController($controller);
            $this->setSite(Page::$collection['type']);
            $this->saveAlert();
        }
    }

    public function getAlerts(string $controller)
    {
        if (!empty($controller)) {
            $db = new Db();
            $alerts = $db->select("id,alerts_type,alerts_title,alerts_message,alerts_timestamp")->from("global_alerts")->where("alerts_page_controller='{$controller}'")->execute("assoc");
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
            $alerts_count = $db->select("COUNT(*) as count")->from("global_alerts")->where("alerts_page_controller='{$controller}'")->execute("assoc");
            if (!empty($alerts_count)) {
                return $alerts_count;
            }
        }
    }

    public function alertDissmis(array $request)
    {
        if (isset($request['query']['id']) && !empty($request['query']['id'])) {
            $db = new Db();
            $result = $db->delete()->from("global_alerts")->where("id={$request['query']['id']}")->execute("bool");
            if ($result) {
                $predefined_alert = self::getPredefinedAlert('alert', 'remove', 'success');
                echo $predefined_alert[0]['alerts_static_message'];
            }
        }
    }

    private function createMessageFromRaport(array $raport)
    {
        $message = '';
        if ($raport['action_type'] == 'save' || $raport['action_type'] == 'update') {
            foreach ($raport['failure_fields'] as $field_key => $field) {
                $message .= '<p><span><b>' . $field_key . ': </b></span><span>' . $field['failure_message'] . '</span></p>';
            }
        } else if ($raport['action_type'] == 'remove') {
            $message .= $raport['failure_action_message'];
        }
        return $message;
    }

    public static function getPredefinedAlert(string $category, string $event, string $type)
    {
        $db = new Db();
        return $db->select("alerts_static_message")->from("global_alerts_static")->where("alerts_static_category='{$category}' AND alerts_static_event='{$event}' AND alerts_static_type='{$type}'")->execute("assoc");
    }

    private function saveAlert()
    {
        if (!empty($this->new_alert)) {
            $db = new Db();
            extract($this->new_alert);
            $escaped_message = htmlspecialchars($alert_message);
            $results = $db->insert("global_alerts")->columns("alerts_page_controller, alerts_type, alerts_title, alerts_message, alerts_site")->values("'{$alert_controller}','{$alert_type}','{$alert_title}','{$escaped_message}','{$alert_site}'")->execute('bool');
        }
    }
}
