<?php

namespace Core;

class Env
{
    private $root = 'sistema';
    private $dir;
    private $rootConfig;
    private $data;

    public function __construct()
    {
        $app_root = $_SERVER['DOCUMENT_ROOT'] . "/";
        $this->setRoot($app_root);
        $this->setRootConfig($this->getRoot() . 'app/config/');
        $this->loadEnv();
    }

    public function loadEnv()
    {
        if (!isset($_SESSION['_env_'])) {
            $this->setData(['root' => $this->getRoot()], true);
            foreach (scandir($this->getRootConfig()) as $filename) {
                $path = $this->getRootConfig() . '/' . $filename;
                if (is_file($path)) {
                    require_once $path;
                    $this->setData(${substr($filename, 0, strpos($filename, '.php'))}, true);
                }
            }
            $_SESSION['_env_'] = $this->data;
        }
    }

    public function get($config = "", $company = "", $data = "")
    {
        if (empty($config)) {
            return $this->getData();
        } elseif (empty($company)) {
            if (isset($this->getData()[$config][$this->getData()['envioment']['env']])) {
                return $this->getData()[$config][$this->getData()['envioment']['env']];
            } elseif (isset($this->getData()[$config])) {
                return $this->getData()[$config];
            }
        } elseif (empty($data)) {
            if (isset($this->getData()[$config][$this->getData()['envioment']['env']][$company])) {
                return $this->getData()[$config][$this->getData()['envioment']['env']][$company];
            }
        } elseif (isset($this->getData()[$config][$this->getData()['envioment']['env']][$company][$data])) {
            return $this->getData()[$config][$this->getData()['envioment']['env']][$company][$data];
        }
    }

    /**
     * Get the value of root
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set the value of root
     *
     * @return  self
     */
    private function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get the value of rootConfig
     */
    public function getRootConfig()
    {
        return $this->rootConfig;
    }

    /**
     * Set the value of rootConfig
     *
     * @return  self
     */
    private function setRootConfig($rootConfig)
    {
        $this->rootConfig = $rootConfig;

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
    private function setDir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $_SESSION['_env_'];
    }

    /**
     * Set the value of data
     *
     * @return  self
     */
    private function setData($data, $add = false)
    {
        if ($add) {
            if (is_array($data)) {
                if (!is_array($this->data)) {
                    $this->data = [];
                }
                $this->data = array_merge($this->data, $data);
            }
        } else {
            $this->data = $data;
        }
        return $this;
    }
}
