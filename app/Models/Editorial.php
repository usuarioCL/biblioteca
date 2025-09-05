<?php

namespace App\Models;
use CodeIgniter\Model;

class Editorial extends Model
{
    protected $table      = 'editoriales';
    protected $primaryKey = 'ideditorial';



    protected $allowedFields = ['editorial','nacionalidad'];
}