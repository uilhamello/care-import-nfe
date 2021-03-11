<?php

namespace App\Controllers;

use Core\Controllers\Controller;
use App\Models\Nfe;
use Core\Views\Viewer;

class HomeController extends Controller
{
    public function index()
    {

        echo $this->display('index');
    }
}
