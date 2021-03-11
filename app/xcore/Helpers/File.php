<?php

namespace Core\Helpers;

use Core\Env;

class File
{
    private $name;
    private $dir;
    private $header;
    private $connection;
    private $footer;
    private $data;
    private $mode;
    private $endLine;
    private $typeEncode;

    public function __construct()
    {
        $env = new Env();

        /**
         * Basic mode: just read
         */
        $this->setMode('r');
        $this->setEndLine("\n"); // Unix
        $this->setTypeEncode('unix'); // Default application
        $this->setDir($env->get('root') . 'storage/cache/');
    }

    public function open()
    {
        try {
            $con = fopen($this->getDir() . $this->getName(), $this->getMode()) or die('tesafsa');
            $this->setConnection($con);
        } catch (Exception $e) {
            echo "impossible to open a file";
            echo $e->getMessage();
        }
    }

    public function move($file, $destiny)
    {
        $file = array_shift($file);
        if (empty($destiny)) {
            $destiny = 'storage';
        }
        $destiny = $_SERVER['DOCUMENT_ROOT'] . '/' . $destiny;
        $newname = date("YmdHis") . $file['name'];
        $target = $destiny . $newname;
        try {
            move_uploaded_file($file['tmp_name'], $target);
            return $target;
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        return false;
    }

    public function create($mode = 'a+')
    {
        $this->setMode($mode);
        $this->open();
        $this->fwrite($this->getHeader() . $this->getEndLine());
        if (is_array($this->getData())) {
            $this->createByArray();
        } else {
            $this->createByDB();
        }
        $create = $this->fwrite($this->footer . $this->getEndLine());
        fclose($this->connection);
        return $create;
    }

    public function createByArray()
    {
        foreach ($this->getData() as $row) {
            $columns = array_keys($row);
            $line = "";
            $comma = "";
            foreach ($columns as $col) {
                $line .= $comma . $row[$col];
                // $line .= $comma.$this->removeSpecialCharacter($row[$col]);
                $comma = ";";
            }
            $this->fwrite($line . $this->getEndLine());
        }
    }

    public function createByDB()
    {
        while ($row = $this->getData()->next()) {
            $columns = array_keys($row);
            $line = "";
            $comma = "";
            foreach ($columns as $col) {
                $line .= $comma . $row[$col];
                // $line .= $comma.$this->removeSpecialCharacter($row[$col]);
                $comma = ";";
            }
            $this->fwrite($line . $this->getEndLine());
        }
    }

    public function delete()
    {
        unlink($this->getDir() . $this->getName());
    }

    public function fwrite($string)
    {
        /*if($this->getTypeEncode() == 'windows')
        {
            $string = mb_convert_encoding($string, "UTF-8", "auto");
            $string = iconv( mb_detect_encoding( $string ), 'Windows-1252//TRANSLIT', $string );
        }*/
        fwrite($this->getConnection(), $string);
    }

    /**
     * Remove specials characters
     *
     * @return  self
     */
    public function removeSpecialCharacter($str)
    {
        $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $to = "aaaaeeiooouucAAAAEEIOOOUUC";
        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        return strtr($str, $mapping);
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name, $type = "", $currentDate = false)
    {
        $data = '';
        if ($currentDate) {
            $data = date("Y-m-d_H-i-s", time());
        }
        $this->name = $name . $data . $type;

        return $this;
    }

    /**
     * Get the value of dir
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Set the value of dir
     *
     * @return  self
     */
    public function setDir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Get the value of header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set the value of header
     *
     * @return  self
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get the value of connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set the value of connection
     *
     * @return  self
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Get the value of footer
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * Set the value of footer
     *
     * @return  self
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;

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
     * Get the value of mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the value of mode
     *
     * @return  self
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get the value of mode
     */
    public function getEndLine()
    {
        return $this->endLine;
    }

    /**
     * Set the value of end line
     *
     * @return  self
     */
    public function setEndLine($el)
    {
        $this->endLine = $el;

        return $this;
    }

    /**
     * Get the value of type encode
     */
    public function getTypeEncode()
    {
        return $this->typeEncode;
    }

    /**
     * Set the value of type encode
     *
     * @param   unix / windows
     * @return  self
     */
    public function setTypeEncode($type)
    {
        $this->typeEncode = $type;

        return $this;
    }
}
