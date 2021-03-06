<?php

class Db
{
    private $connection_data;
    public $connection;
    private $query = array('function' => array(), 'string' => array());

    public function __construct()
    {
        $this->db_data = Tools::getConnectionData();
        @$this->connection = new mysqli($this->db_data->host, $this->db_data->login, $this->db_data->password, $this->db_data->database);
        try {
            if ($this->connection->connect_errno) {
                throw new CustomException('Could not connect to the database. Error code: ' . $this->connection->connect_errno);
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
            if (end($this->query['function']) == 'select' || end($this->query['function']) == 'delete') {
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
        array_push($this->query['string'], "INSERT INTO $chunk");
        return $this;
    }

    public function delete()
    {
        array_push($this->query['function'], __FUNCTION__);
        array_push($this->query['string'], "DELETE");
        return $this;
    }

    public function update($chunk)
    {
        array_push($this->query['function'], __FUNCTION__);
        array_push($this->query['string'], "UPDATE $chunk");
        return $this;
    }

    public function set($chunk)
    {
        try {
            if (end($this->query['function']) == 'update' || end($this->query['function']) == 'on') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "SET $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function columns($chunk)
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
            if (end($this->query['function']) == 'columns' || end($this->query['function']) == 'insert') {
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
            if (end($this->query['function']) == 'from' || end($this->query['function']) == 'on' || end($this->query['function']) == 'set') {
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

    public function innerJoin($chunk)
    {
        try {
            if (end($this->query['function']) == 'from' || end($this->query['function']) == 'on' || end($this->query['function']) == 'update') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "INNER JOIN $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function leftJoin()
    {
        try {
            if (end($this->query['function']) == 'from') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "LEFT JOIN $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function rightJoin()
    {
        try {
            if (end($this->query['function']) == 'from') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "RIGHT JOIN $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function crossJoin()
    {
        try {
            if (end($this->query['function']) == 'from') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "CROSS JOIN $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function naturalJoin()
    {
        try {
            if (end($this->query['function']) == 'from') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "NATURAL JOIN $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function on($chunk)
    {
        try {
            if (end($this->query['function']) == 'innerJoin') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "ON $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function limit($chunk)
    {
        try {
            if (end($this->query['function']) == 'where' || end($this->query['function']) == 'from' || end($this->query['function']) == 'orderBy') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "LIMIT $chunk ");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function orderBy($chunk)
    {
        try {
            if (end($this->query['function']) == 'where' || end($this->query['function']) == 'from' || end($this->query['function']) == 'on') {
                array_push($this->query['function'], __FUNCTION__);
                array_push($this->query['string'], "ORDER BY $chunk");
                return $this;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public function execute($type)
    {
        $joined = '';
        if (implode('', $this->query['function']) != 'customQuery') {
            $joined = join(' ', $this->query['string']);
        } elseif ($this->query['function'] != 'customQuery') {
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
                            while ($object = $exec->fetch_object()) {
                                array_push($fetch_array, $object);
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
            case 'print':
                $fetch_array = array();
                try {
                    if (!empty($joined)) {
                        echo $joined;
                        die();
                    } else {
                        throw new CustomException('Query string is empty.');
                    }
                } catch (CustomException $e) {
                    echo $e->getCustomMessage($e);
                    exit();
                }
                break;
            case 'bool':
                $fetch_array = array();
                try {
                    if (!empty($joined)) {
                        $exec = $this->connection->query($joined);
                        if (!$exec) {
                            throw new CustomException('Invalid or empty query results.');
                        } else {
                            array_push($fetch_array, $exec);
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
                $this->query['string'] = $custom_query;
            } else {
                throw new CustomException('Incorrect database query string');
            }
        } catch (customException $e) {
            echo $e->getCustomMessage($e);
        }
        return $this;
    }

    public function escapeString(string $input)
    {
        if (!empty($input)) {
            $input = $this->connection->real_escape_string($input);
            $input = trim($input);
        }
        return $input;
    }

}
