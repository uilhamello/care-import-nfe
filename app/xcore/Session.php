<?php

namespace Core;

class Session
{
    public function __construct()
    {
        $this->start();
    }

    public function logged()
    {
        return isset($_SESSION['user']);
    }
    public function start()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function all()
    {
        return $_SESSION;
    }

    public function destroy()
    {
        $this->start();
        session_destroy();
    }
}
