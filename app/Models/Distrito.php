<?php

namespace App\Models;
use CodeIgniter\Model;

class Distrito extends Model
{
    protected $table      = 'distritos';
    protected $primaryKey = 'iddistrito';
    protected $allowedFields = ['idprovincia', 'distrito'];
}