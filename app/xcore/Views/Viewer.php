<?php

namespace Core\Views;

use Exception;

trait Viewer
{
    public function display($path, $data = [])
    {
        $viewExtention = '.html.php';
        $layout = '';

        if (is_file($_SERVER['DOCUMENT_ROOT'] . "/app/Views/layout/top" . $viewExtention)) {
            require_once $_SERVER['DOCUMENT_ROOT'] . "/app/Views/layout/top" . $viewExtention;
        }
        try {
            if (is_file($_SERVER['DOCUMENT_ROOT'] . "/app/Views/" . $path . $viewExtention)) {
                require_once $_SERVER['DOCUMENT_ROOT'] . "/app/Views/" . $path . $viewExtention;
            } else {
                die("Error: View File '" . $path . $viewExtention . "' not exist");
            }

            if (is_file($_SERVER['DOCUMENT_ROOT'] . "/app/Views/layout/bottom" . $viewExtention)) {
                require_once $_SERVER['DOCUMENT_ROOT'] . "/app/Views/layout/bottom" . $viewExtention;
            }
            exit();
        } catch (Exception $e) {
            die($e);
        }
    }

    public function contentReplace($content, $data)
    {
        foreach ($data as $key => $value) {
            $content = preg_replace("/{{" . $key . "}}/", $value, $content, 1);
        }
        return $content;
    }
}
