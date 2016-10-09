<?php

namespace App\Utils\Uri\Interfaces;

interface ResourceUri
{
    const CREATE = 'create';
    const READ = '';
    const UPDATE = 'edit';
    const DELETE = 'delete';

    public function create($parameters = []);
    public function read($parameters = []);
    public function update($parameters = []);
    public function delete($parameters = []);
}
