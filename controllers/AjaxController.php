<?php

class AjaxController
{

    public function __construct(object $collector)
    {
        $url = new Url();
        $request = $url->requestToArray();
        $this->respondConstructor($request);
    }

    private function respondConstructor(array $request)
    {
        if (isset($request['query']['class']) && !empty($request['query']['class']) && Tools::checkExisting($request['query']['class'], 'class')) {
            $ucfirst_name = ucfirst($request['query']['class']);
            $request_class = new $ucfirst_name;
            if (isset($request['action']) && !empty($request['action'])) {
                $methods = get_class_methods(get_class($request_class));
                foreach ($methods as $method) {
                    if ($method == $request['action']) {
                        return $request_class->{$method}($request);
                        break;
                    }
                }
            }
        }
    }
}
