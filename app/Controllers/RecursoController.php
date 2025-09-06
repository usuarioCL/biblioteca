<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Recurso;
use App\Models\Categoria;
use App\Models\Editorial;
use App\Models\Subcategoria;

class RecursoController extends BaseController {
    public function index() {
        $recurso = new Recurso();
        $data = [
            'header' => view('Layouts/header'),
            'footer' => view('Layouts/footer'),
            'recursos' => $recurso->getRecursosConDetalles()
        ];
        return view('recursos/index', $data);
    }

    public function crear() {
        $categoria = new Categoria();
        $editorial = new Editorial();
        $data = [
            'header' => view('Layouts/header'),
            'footer' => view('Layouts/footer'),
            'categorias' => $categoria->findAll(),
            'editoriales' => $editorial->findAll()
        ];
        return view('recursos/crear', $data);
    }
    // Devuelve subcategorías según la categoría seleccionada (AJAX)
    public function subcategoriasPorCategoria($idCategoria) {
        $subcategoria = new Subcategoria();
        return $this->response->setJSON($subcategoria->where('idcategoria', $idCategoria)->findAll());
    }
    
    public function verificarISBN($isbn, $excludeId = null) {
        $recurso = new Recurso();
        $query = $recurso->where('isbn', $isbn);
        if ($excludeId) $query->where('idrecurso !=', $excludeId);
        $existe = $query->countAllResults(false) > 0;
        return $this->response->setJSON(['existe' => $existe]);
    }

    public function editar($id) {
        $recurso = new Recurso();
        $categoria = new Categoria();
        $subcategoria = new Subcategoria();
        $editorial = new Editorial();
        $recursoData = $recurso->find($id);
        if (!$recursoData) return redirect()->to(base_url('recursos'))->with('error', 'Recurso no encontrado.');
        $subcategoriaData = $subcategoria->find($recursoData['idsubcategoria']);
        $categoriaActual = $subcategoriaData ? (is_array($subcategoriaData) ? $subcategoriaData['idcategoria'] : $subcategoriaData->idcategoria) : null;
        $subcategorias = $categoriaActual ? $subcategoria->where('idcategoria', $categoriaActual)->findAll() : [];
        $data = [
            'header' => view('Layouts/header'),
            'footer' => view('Layouts/footer'),
            'recurso' => $recursoData,
            'categorias' => $categoria->findAll(),
            'subcategorias' => $subcategorias,
            'editoriales' => $editorial->findAll(),
            'categoriaActual' => $categoriaActual
        ];
        return view('recursos/editar', $data);
    }

    public function actualizar($id)
    {
        $recurso = new Recurso();
        
        // Verificar que el recurso existe
        $recursoExistente = $recurso->find($id);
        if (!$recursoExistente) {
            return redirect()->to(base_url('recursos'))->with('error', 'Recurso no encontrado.');
        }

        // Validaciones del servidor
        $validationRules = [
            'titulo' => 'required|max_length[255]',
            'categoria' => 'required|numeric',
            'subcategoria' => 'required|numeric', 
            'editorial' => 'required|numeric',
            'tipo' => 'required|in_list[Físico,DIGITAL]',
            'estado' => 'required|in_list[Bueno,Regular,Malo]',
            'apublicacion' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[' . date('Y') . ']',
            'isbn' => 'permit_empty|exact_length[13]|numeric',
            'numpaginas' => 'permit_empty|integer|greater_than[0]'
        ];

        $validationMessages = [
            'titulo' => [
                'required' => 'El título es obligatorio.',
                'max_length' => 'El título no puede exceder 255 caracteres.'
            ],
            'apublicacion' => [
                'required' => 'El año de publicación es obligatorio.',
                'integer' => 'El año debe ser un número entero.',
                'greater_than_equal_to' => 'El año debe ser mayor o igual a 1900.',
                'less_than_equal_to' => 'El año no puede ser mayor al año actual.'
            ],
            'isbn' => [
                'exact_length' => 'El ISBN debe tener exactamente 13 dígitos.',
                'numeric' => 'El ISBN debe contener solo números.'
            ]
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isbn = $this->request->getPost('isbn');
        
        // Validar que el ISBN sea único si se proporciona (excluyendo el recurso actual)
        if ($isbn && $recurso->where('isbn', $isbn)->where('idrecurso !=', $id)->countAllResults(false) > 0) {
            return redirect()->back()->withInput()->with('error', 'El ISBN ya existe. Debe ser único para cada libro.');
        }

        // Validar año de publicación específicamente
        $apublicacion = (int)$this->request->getPost('apublicacion');
        if ($apublicacion < 1900 || $apublicacion > date('Y')) {
            return redirect()->back()->withInput()->with('error', 'El año de publicación debe estar entre 1900 y ' . date('Y'));
        }
        
        $data = [
            'titulo' => $this->request->getPost('titulo'),
            'idsubcategoria' => $this->request->getPost('subcategoria'),
            'ideditorial' => $this->request->getPost('editorial'),
            'tipo' => $this->request->getPost('tipo'),
            'estado' => $this->request->getPost('estado'),
            'apublicacion' => $apublicacion,
            'isbn' => $isbn ?: null,
            'numpaginas' => $this->request->getPost('numpaginas') ?: null,
            'modificado' => date('Y-m-d H:i:s') // Forzar actualización de timestamp
        ];

        // Manejo de la subida de la imagen de portada (opcional en edición)
        $portada = $this->request->getFile('rutaportada');
        if ($portada && $portada->isValid() && !$portada->hasMoved()) {
            // Validar tipo de archivo de portada
            $allowedPortadaTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            $portadaExtension = $portada->getClientExtension();
            if (!in_array(strtolower($portadaExtension), $allowedPortadaTypes)) {
                return redirect()->back()->withInput()->with('error', 'La portada debe ser una imagen válida (jpg, jpeg, png, gif, bmp, webp).');
            }

            // Eliminar imagen anterior si existe
            if ($recursoExistente['rutaportada'] && file_exists(FCPATH . $recursoExistente['rutaportada'])) {
                unlink(FCPATH . $recursoExistente['rutaportada']);
            }

            $newPortadaName = $portada->getRandomName();
            $portada->move(FCPATH . 'uploads', $newPortadaName);
            $data['rutaportada'] = 'uploads/' . $newPortadaName;
        }

        // Manejo de la subida del archivo digital (opcional en edición)
        $archivo = $this->request->getFile('rutarecurso');
        if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
            // Validar tipo de archivo
            $allowedArchivoTypes = ['pdf', 'doc', 'docx'];
            $archivoExtension = $archivo->getClientExtension();
            if (!in_array(strtolower($archivoExtension), $allowedArchivoTypes)) {
                return redirect()->back()->withInput()->with('error', 'El archivo del recurso debe ser PDF, DOC o DOCX.');
            }

            // Eliminar archivo anterior si existe
            if ($recursoExistente['rutarecurso'] && file_exists(FCPATH . $recursoExistente['rutarecurso'])) {
                unlink(FCPATH . $recursoExistente['rutarecurso']);
            }

            $newArchivoName = $archivo->getRandomName();
            $archivo->move(FCPATH . 'uploads', $newArchivoName);
            $data['rutarecurso'] = 'uploads/' . $newArchivoName;
        }

        // Validar archivo obligatorio para tipo DIGITAL (solo si no existe uno previamente)
        if ($data['tipo'] === 'DIGITAL' && !isset($data['rutarecurso']) && !$recursoExistente['rutarecurso']) {
            return redirect()->back()->withInput()->with('error', 'El archivo del recurso es obligatorio para recursos digitales.');
        }

        try {
            // Verificar que todos los datos están presentes
            log_message('debug', 'Datos para actualizar: ' . json_encode($data));
            
            // Usar query builder para mayor control de la actualización
            $db = \Config\Database::connect();
            $builder = $db->table('recursos');
            $result = $builder->where('idrecurso', $id)->update($data);
            
            // Verificar si la actualización fue exitosa
            if ($result) {
                // Limpiar cualquier caché de datos
                if (function_exists('opcache_reset')) {
                    opcache_reset();
                }
                
                // Verificar cambios en la base de datos
                $recursoActualizado = $recurso->find($id);
                log_message('debug', 'Recurso después de actualizar: ' . json_encode($recursoActualizado));
                
                return redirect()->to(base_url('recursos'))->with('success', 'Recurso actualizado correctamente.');
            } else {
                // Obtener errores del modelo si existen
                $errors = $recurso->errors();
                $errorMsg = !empty($errors) ? implode(', ', $errors) : 'No se pudo actualizar el recurso.';
                log_message('error', 'Error al actualizar recurso: ' . $errorMsg);
                return redirect()->back()->withInput()->with('error', $errorMsg);
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al actualizar recurso: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el recurso: ' . $e->getMessage());
        }
    }

    public function eliminar($id) {
        $recurso = new Recurso();
        $recursoExistente = $recurso->find($id);
        if (!$recursoExistente) return redirect()->to(base_url('recursos'))->with('error', 'Recurso no encontrado.');
        try {
            if (!empty($recursoExistente['rutaportada']) && file_exists(FCPATH . $recursoExistente['rutaportada'])) unlink(FCPATH . $recursoExistente['rutaportada']);
            if (!empty($recursoExistente['rutarecurso']) && file_exists(FCPATH . $recursoExistente['rutarecurso'])) unlink(FCPATH . $recursoExistente['rutarecurso']);
            $recurso->delete($id);
            return redirect()->to(base_url('recursos'))->with('success', 'Recurso eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->to(base_url('recursos'))->with('error', 'Error al eliminar el recurso: ' . $e->getMessage());
        }
    }

    public function guardar()
    {
        $recurso = new Recurso();
        
        // Validaciones del servidor
        $validationRules = [
            'titulo' => 'required|max_length[255]',
            'categoria' => 'required|numeric',
            'subcategoria' => 'required|numeric', 
            'editorial' => 'required|numeric',
            'tipo' => 'required|in_list[Físico,DIGITAL]',
            'estado' => 'required|in_list[Bueno,Regular,Malo]',
            'apublicacion' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[' . date('Y') . ']',
            'isbn' => 'permit_empty|exact_length[13]|numeric',
            'numpaginas' => 'permit_empty|integer|greater_than[0]'
        ];

        $validationMessages = [
            'titulo' => [
                'required' => 'El título es obligatorio.',
                'max_length' => 'El título no puede exceder 255 caracteres.'
            ],
            'apublicacion' => [
                'required' => 'El año de publicación es obligatorio.',
                'integer' => 'El año debe ser un número entero.',
                'greater_than_equal_to' => 'El año debe ser mayor o igual a 1900.',
                'less_than_equal_to' => 'El año no puede ser mayor al año actual.'
            ],
            'isbn' => [
                'exact_length' => 'El ISBN debe tener exactamente 13 dígitos.',
                'numeric' => 'El ISBN debe contener solo números.'
            ]
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $isbn = $this->request->getPost('isbn');
        
        // Validar que el ISBN sea único si se proporciona
        if ($isbn && $recurso->where('isbn', $isbn)->countAllResults(false) > 0) {
            return redirect()->back()->withInput()->with('error', 'El ISBN ya existe. Debe ser único para cada libro.');
        }

        // Validar año de publicación específicamente para evitar el problema de 0000
        $apublicacion = (int)$this->request->getPost('apublicacion');
        if ($apublicacion < 1900 || $apublicacion > date('Y')) {
            return redirect()->back()->withInput()->with('error', 'El año de publicación debe estar entre 1900 y ' . date('Y'));
        }
        
        $data = [
            'titulo' => $this->request->getPost('titulo'),
            'idsubcategoria' => $this->request->getPost('subcategoria'),
            'ideditorial' => $this->request->getPost('editorial'),
            'tipo' => $this->request->getPost('tipo'),
            'estado' => $this->request->getPost('estado'),
            'apublicacion' => $apublicacion,
            'isbn' => $isbn ?: null,
            'numpaginas' => $this->request->getPost('numpaginas') ?: null,
        ];

        // Manejo de la subida de la imagen de portada (obligatoria)
        $portada = $this->request->getFile('rutaportada');
        if (!$portada || !$portada->isValid()) {
            return redirect()->back()->withInput()->with('error', 'La portada es obligatoria y debe ser válida.');
        }
        
        if ($portada->hasMoved()) {
            return redirect()->back()->withInput()->with('error', 'Error al procesar la portada.');
        }

        // Validar tipo de archivo de portada
        $allowedPortadaTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $portadaExtension = $portada->getClientExtension();
        if (!in_array(strtolower($portadaExtension), $allowedPortadaTypes)) {
            return redirect()->back()->withInput()->with('error', 'La portada debe ser una imagen válida (jpg, jpeg, png, gif, bmp, webp).');
        }

        $newPortadaName = $portada->getRandomName();
        $portada->move(FCPATH . 'uploads', $newPortadaName);
        $data['rutaportada'] = 'uploads/' . $newPortadaName;

        // Manejo de la subida del archivo digital (obligatorio para tipo DIGITAL)
        $archivo = $this->request->getFile('rutarecurso');
        if ($data['tipo'] === 'DIGITAL') {
            if (!$archivo || !$archivo->isValid()) {
                return redirect()->back()->withInput()->with('error', 'El archivo del recurso es obligatorio para recursos digitales.');
            }
        }

        if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
            // Validar tipo de archivo
            $allowedArchivoTypes = ['pdf', 'doc', 'docx'];
            $archivoExtension = $archivo->getClientExtension();
            if (!in_array(strtolower($archivoExtension), $allowedArchivoTypes)) {
                return redirect()->back()->withInput()->with('error', 'El archivo del recurso debe ser PDF, DOC o DOCX.');
            }

            $newArchivoName = $archivo->getRandomName();
            $archivo->move(FCPATH . 'uploads', $newArchivoName);
            $data['rutarecurso'] = 'uploads/' . $newArchivoName;
        }

        try {
            $recurso->insert($data);
            return redirect()->to(base_url('recursos'))->with('success', 'Recurso guardado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar el recurso: ' . $e->getMessage());
        }
    }

}
