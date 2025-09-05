<?php

namespace App\Models;
use CodeIgniter\Model;

class Departamento extends Model
{
    protected $table      = 'departamentos';
    protected $primaryKey = 'iddepartamento';
    protected $allowedFields = ['departamento'];
}