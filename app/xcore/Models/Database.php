<?php

namespace Core\Models;

use Core\Env;

class Database
{
    private $host;
    private $user;
    private $password;
    private $database;
    private $port;
    private $db;
    private $data;
    private $total;
    private $alias;

    public function __construct($database = 'default')
    {
        $env = new Env();
        $this->setHost($env->get('database', $database, 'host'));
        $this->setUser($env->get('database', $database, 'user'));
        $this->setPassword($env->get('database', $database, 'password'));
        $this->setDatabase($env->get('database', $database, 'database'));
        $this->setPort($env->get('database', $database, 'port'));
        $this->setAlias($env->get('database', $database, 'alias'));
        $this->connection();
    }

    public function connection()
    {
        $connection = mysqli_connect($this->getHost(), $this->getUser(), $this->getPassword(), $this->getDatabase());
        if (!$connection) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        $connection->query("SET @@session.sql_mode= ''");
        $this->setDb($connection);
    }

    public function query($sql, $isAQuery = true)
    {
        $result = mysqli_query($this->getDb(), $sql);
        $this->setData($result);
        if ($isAQuery) {
            if ($result) {
                $this->setTotal(mysqli_num_rows($result));
            } else {
                $this->setTotal(0);
            }
        }
        return $this;
    }

    public function next()
    {
        if ($this->getData()) {
            $array = mysqli_fetch_array($this->getData(), MYSQLI_ASSOC);
            return $array;
        }
        return false;
    }

    /**
     * Get the value of host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set the value of host
     *
     * @return  self
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of database
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Set the value of database
     *
     * @return  self
     */
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * Get the value of port
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set the value of port
     *
     * @return  self
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get the value of db
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Set the value of db
     *
     * @return  self
     */
    public function setDb($db)
    {
        $this->db = $db;

        return $this;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @return  self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of total
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * Set the value of total
     *
     * @return  self
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the value of alias
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set the value of alias
     *
     * @return  self
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }
}
