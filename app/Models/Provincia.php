<?php

namespace App\Models;
use CodeIgniter\Model;

class Provincia extends Model
{
    protected $table      = 'provincias';
    protected $primaryKey = 'idprovincia';
    protected $allowedFields = ['iddepartamento', 'provincia'];
}