<?php

class Db
{
    private $connection_data;
    public $connection;
    public $query = array('function' => array(), 'string' => array());

    public function __construct()
    {
        $this->db_data = Tools::getConnectionData();
        @$this->connection = new mysqli($this->db_data->host, $this->db_data->login, $this->db_data->password, $this->db_data->database);
        try {
            if ($this->connection->connect_errno) {
                throw new CustomException('Could not connect to the database.');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }

    }

    public function select($chunk)
    {
        array_push($this->query['function'], __FUNCTION__);
        array_push($this->query['string'], "SELECT $chunk");
        return $this;
    }

    public function from($chunk)
    {
        try {
            if (end($this->query['function']) == 'select') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "FROM $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function insert($chunk)
    {
        array_push($this->query['function'], __FUNCTION__);
        array_push($this->query['string'], "INSERT INTO ($chunk)");
        return $this;
    }

    public function columns()
    {
        try {
            if (end($this->query['function']) == 'insert') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "($chunk)");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }

    }

    public function values($chunk)
    {
        try {
            if (end($this->query['function']) == 'columns') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "VALUES ($chunk)");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }

    }

    public function where($chunk)
    {
        try {
            if (end($this->query['function']) == 'from') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "WHERE $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function innerJoin()
    {

    }

    public function join()
    {

    }

    public function leftJoin()
    {

    }

    public function rightJoin()
    {

    }

    public function execute($type)
    {
        $joined = '';
        if (implode('', $this->query['function']) !== 'customQuery') {
            $joined = join(' ', $this->query['string']);
        } elseif ($this->query['function'] !== 'customQuery') {
            $joined = $this->query['string'];
        }
        switch ($type) {
            case 'array':
                $fetch_array = array();
                try {
                    if (!empty($joined)) {
                        $exec = $this->connection->query($joined);
                        if (!$exec) {
                            throw new CustomException('Invalid or empty query results.');
                        } else {
                            while ($assoc = $exec->fetch_array()) {
                                array_push($fetch_array, $assoc);
                            }
                        }
                        $this->query['string'] = $this->query['function'] = array();
                    } else {
                        throw new CustomException('Query string is empty.');
                    }
                } catch (CustomException $e) {
                    echo $e->getCustomMessage($e);
                    exit();
                }
                break;
            case 'assoc':
                $fetch_array = array();
                try {
                    if (!empty($joined)) {
                        $exec = $this->connection->query($joined);
                        if (!$exec) {
                            throw new CustomException('Invalid or empty query results.');
                        } else {
                            while ($assoc = $exec->fetch_assoc()) {
                                array_push($fetch_array, $assoc);
                            }
                        }
                        $this->query['string'] = $this->query['function'] = array();
                    } else {
                        throw new CustomException('Query string is empty.');
                    }
                } catch (CustomException $e) {
                    echo $e->getCustomMessage($e);
                    exit();
                }
                break;
            case 'object':
                $fetch_array = array();
                try {
                    if (!empty($joined)) {
                        $exec = $this->connection->query($joined);
                        if (!$exec) {
                            throw new CustomException('Invalid or empty query results.');
                        } else {
                            while ($assoc = $exec->fetch_assoc()) {
                                array_push($fetch_array, $assoc);
                            }
                        }
                        $this->query['string'] = $this->query['function'] = array();
                    } else {
                        throw new CustomException('Query string is empty.');
                    }
                } catch (CustomException $e) {
                    echo $e->getCustomMessage($e);
                    exit();
                }
                break;
            case 'row':
                $fetch_array = array();
                echo $joined;
                try {
                    if (!empty($joined)) {
                        $exec = $this->connection->query($joined);
                        if (!$exec) {
                            throw new CustomException('Invalid or empty query results.');
                        } else {
                            while ($row = $exec->fetch_row()) {
                                array_push($fetch_array, $row);
                            }
                        }
                        $this->query['string'] = $this->query['function'] = array();
                    } else {
                        throw new CustomException('Query string is empty.');
                    }
                } catch (CustomException $e) {
                    echo $e->getCustomMessage($e);
                    exit();
                }
                break;
            case 'default':
        }
        return $fetch_array;
    }

    public function customQuery($custom_query)
    {
        try {
            if (empty($this->query['function'])) {
                array_push($this->query['function'], __FUNCTION__);
                $this->query['string'] = $this->cleaner($custom_query);
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (customException $e) {
            echo $e->getCustomMessage($e);
        }
        return $this;
    }

    private function cleanAfterSend()
    {
        return $chunks;

    }

}
