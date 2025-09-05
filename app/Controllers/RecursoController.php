<?php

namespace App\Controllers;  
use App\Controllers\BaseController;
use App\Models\Recurso;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Editorial;

class RecursoController extends BaseController
{
    public function index()
    {
        $recurso = new Recurso();
        $data = ['header' => view('Layouts/header'),
                 'footer' => view('Layouts/footer'),
                 'recursos' => $recurso->findAll()]; 
        return view('recursos/index', $data);
    }

    public function crear()
    {   
        $recurso = new Recurso(); 
        $categoria = new Categoria();
        $editorial = new Editorial();

        $data = ['header' => view('Layouts/header'),
                 'footer' => view('Layouts/footer'),
                 'categorias' => $categoria->findAll(),
                 'recursos' => $recurso->findAll(),
                 'editoriales' => $editorial->findAll()];
        return view('recursos/crear', $data);
    }
    // Devuelve subcategorías según la categoría seleccionada (AJAX)
    public function subcategoriasPorCategoria($idCategoria)
    {
        $subcategoria = new Subcategoria();
        $result = $subcategoria->where('idcategoria', $idCategoria)->findAll();
        return $this->response->setJSON($result);
    }

    public function guardar()
    {
        $recurso = new Recurso();
        $data = [
            'titulo' => $this->request->getPost('titulo'),
            'idcategoria' => $this->request->getPost('categoria'),
            'idsubcategoria' => $this->request->getPost('subcategoria'),
            'ideditorial' => $this->request->getPost('editorial'),
            'tipo' => $this->request->getPost('tipo'),
            'estado' => $this->request->getPost('estado'),
            'apublicacion' => $this->request->getPost('apublicacion'),
            'isbn' => $this->request->getPost('isbn'),
            'numpaginas' => $this->request->getPost('numpaginas'),
        ];

        // Manejo de la subida de la imagen de portada
        $portada = $this->request->getFile('rutaportada');
        if ($portada && !$portada->hasMoved()) {
            $newName = $portada->getRandomName();
            $portada->move(FCPATH . 'uploads', $newName);
            $data['rutaportada'] = 'uploads/' . $newName;
        }
        // Manejo de la subida del archivo digital
        $archivo = $this->request->getFile('rutarecurso');
        if ($archivo && !$archivo->hasMoved()) {
            $newName = $archivo->getRandomName();
            $archivo->move(FCPATH . 'uploads', $newName);
            $data['rutarecurso'] = 'uploads/' . $newName;
        }

        $recurso->insert($data);
        return redirect()->to(base_url('recursos'));
    }
}
