<?php

namespace App\Utils\Interfaces;

interface ResourceUri
{
    public function create();
    public function read();
    public function update();
    public function delete();
}
