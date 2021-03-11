<?php

namespace Core\Models;

class Model
{
    private $db;
    private $child;
    private $where;
    private $field;
    private $table;
    private $sql;
    private $limit;
    private $page;


    public function __construct($child = false)
    {
        $this->setDb(new Database());
        $this->setChild($child);
    }

    public function select($fields = null)
    {
        $this->field($fields);
        return $this;
    }

    public function query($sql, $isAQuery = true)
    {
        $this->sql = $sql;
        return $this->db->query($sql, $isAQuery);
    }

    public function columnsName()
    {
        $result = $this->query("SELECT `COLUMN_NAME` 
                        FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                        WHERE `TABLE_NAME`='" . $this->getTable() . "';");
        $column = [];
        while ($row = $result->next()) {
            $column[] = array_shift($row);
        }

        return $column;
    }

    public function insert($datas)
    {
        $fields = '';
        $first = true;
        $values = "";
        $columns = $this->columnsName();
        foreach ($columns as $val) {
            if (isset($datas[$val])) {
                if (!$first) {
                    $fields .= ',';
                    $values .= ',';
                }
                $fields .= $val;
                $values .= "'" . $datas[$val] . "'";
                $first = false;
            }
        }

        $insert = "insert into " . $this->getTable() . " (" . $fields . ") values (" . $values . ");";

        return $this->query($insert, false);
    }

    public function get()
    {
        $sql = "select " . $this->getField() . " from " . $this->getTable() . " " . $this->getWhere() . " " . $this->getLimit() . " " . $this->getPage();
        return $this->query($sql);
    }

    public function getTable($table = null)
    {
        $table = '';
        if (!empty($this->table)) {
            $table = $this->table;
        }
        if (isset($this->getChild()->table)) {
            $table = $this->getChild()->table;
        } else {
            $class = explode("\\", get_class($this->getChild()));
            $table = strtolower(end($class));
        }
        $table = $this->getDb()->getAlias() . $table;
        return $table;
    }

    public function table($table)
    {
        $this->table = $table;
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
     * Get the value of child
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set the value of child
     *
     * @return  self
     */
    public function setChild($child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get the value of where
     */
    public function getWhere()
    {
        if (empty($this->where)) {
            return '';
        }
        if (!is_array($this->where)) {
            return "where " . $this->where;
        }
        if (!is_array($this->where[0])) {
            return "where " . $this->where[0] . $this->where[1] . $this->where[2];
        }
        $strWhere = "";
        foreach ($this->where as $val) {
            if (count($val) > 1) {
                $strWhere .= " " . $val[0] . $val[1] . $val[2];
            } else {
                $strWhere .= " " . $val;
            }
        }
        return "where " . $strWhere;
    }

    public function where($where)
    {
        $this->where[] = $where;

        return $this;
    }

    /**
     * Get the value of field
     */
    public function getField()
    {
        if (!empty($this->field)) {
            return $this->field;
        } else {
            return '*';
        }
    }

    /**
     * Set the value of field
     *
     * @return  self
     */
    public function field($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get the value of sql
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * Get the value of limit
     */
    public function getLimit()
    {
        if (!empty($this->limit)) {
            $this->limit  = " LIMIT " . $this->limit;
        }

        return $this->limit;
    }

    /**
     * Set the value of limit
     *
     * @return  self
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the value of page
     */
    public function getPage()
    {
        if (!empty($this->page)) {
            $this->page  = " OFFSET " . $this->page;
        }
        return $this->page;
    }

    /**
     * Set the value of page
     *
     * @return  self
     */
    public function page($page)
    {
        $this->page = $page;

        return $this;
    }
}
