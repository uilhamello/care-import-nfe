<?php

namespace Core\Helpers;

use Core\Env;
use Exception;

class Transfer
{

    private $host;
    private $user;
    private $password;
    private $connect;
    private $protocol;

    public function __construct($company = "")
    {
        $env = new Env();

        if ($company) {
            $this->host      = $env->get('file_transfer', $company, 'host');
            $this->user      = $env->get('file_transfer', $company, 'user');
            $this->password  = $env->get('file_transfer', $company, 'password');
            $this->protocol  = $env->get('file_transfer', $company, 'protocol');
        }
    }


    public function connect()
    {
        if ($this->protocol == 'ftp') {
            $this->connect_ftp();
        } elseif ($this->protocol == 'sftp') {
            $this->connect_sftp();
        }
    }

    public function close()
    {
        if ($this->protocol == 'ftp') {
            $this->close_ftp();
        } elseif ($this->protocol == 'sftp') {
            $this->close_sftp();
        }
    }

    public function connect_ftp()
    {
        try {
            $con = ftp_connect($this->getHost());
            if (false === $con) {
                throw new Exception('Unable to connect');
            }
            $this->setConnect($con);

            $loggedIn = ftp_login($this->getConnect(), $this->getUser(), $this->getPassword());
            if (true !== $loggedIn) {
                throw new Exception('Unable to log in');
            }
            ftp_pasv($this->getConnect(), true);
        } catch (Exception $e) {
            echo "Failure: " . $e->getMessage();
        }
    }

    public function connect_sftp()
    {
    }

    public function close_ftp()
    {
        ftp_close($this->getConnect());
    }

    public function close_sftp()
    {
    }

    public function put($remoteFile, $localFile)
    {
        $this->connect();
        try {
            $result = ftp_put($this->getConnect(), $remoteFile, $localFile, FTP_BINARY);
        } catch (Exception $e) {
            die($e->getMessage());
        }
        $this->close();
        return $result;
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
     * Get the value of pass
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of pass
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of connect
     */
    public function getConnect()
    {
        return $this->connect;
    }

    /**
     * Set the value of connect
     *
     * @return  self
     */
    public function setConnect($connect)
    {
        $this->connect = $connect;

        return $this;
    }

    /**
     * Get the value of protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set the value of protocol
     *
     * @return  self
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;

        return $this;
    }
}
