<?php

namespace App\Models;
use CodeIgniter\Model;

class Recurso extends Model
{
    protected $table      = 'recursos';
    protected $primaryKey = 'idrecurso';

    protected $allowedFields = [ 
      'idsubcategoria', 'ideditorial', 'tipo', 'titulo', 'apublicacion',
      'isbn', 'numpaginas', 'rutaportada', 'rutarecurso', 'estado', 'modificado'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'creado';
    protected $updatedField  = 'modificado';
    protected $dateFormat    = 'datetime';

    // Validaciones del modelo
    protected $validationRules = [
        'titulo' => 'required|max_length[255]',
        'idsubcategoria' => 'required|integer',
        'ideditorial' => 'required|integer',
        'tipo' => 'required|in_list[Físico,DIGITAL]',
        'estado' => 'required|in_list[Bueno,Regular,Malo]',
        'apublicacion' => 'required|integer',
    ];

    protected $validationMessages = [
        'titulo' => [
            'required' => 'El título es obligatorio',
            'max_length' => 'El título no puede exceder 255 caracteres'
        ]
    ];

    protected $skipValidation = true; // Desactivar validaciones del modelo para update

    // Opcional: Habilitar soft deletes si es necesario
    // protected $useSoftDeletes = true;
    // protected $deletedField  = 'deleted_at';

  // Método para obtener recursos con joins a subcategoría, categoría y editorial
  public function getRecursosConDetalles()
  {
    return $this->select('recursos.*, c.categoria, s.subcategoria, e.editorial')
      ->join('subcategorias s', 'recursos.idsubcategoria = s.idsubcategoria', 'left')
      ->join('categorias c', 's.idcategoria = c.idcategoria', 'left')
      ->join('editoriales e', 'recursos.ideditorial = e.ideditorial', 'left')
      ->orderBy('recursos.modificado', 'DESC')
      ->findAll();
  }
}

