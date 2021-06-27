<?php

namespace App\Controllers;

class Hello extends BaseController
{
    public function index()
    {
        echo 'Hello ci4 friends!';
    }
    public function document(){
        echo 'This is a document';
    }
}