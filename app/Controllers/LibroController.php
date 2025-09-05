<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Libro;

class LibroController extends BaseController
{

  public function index(): string
  {
    $libro = new Libro();

    $datos['libros'] = $libro->orderBy('id', 'ASC')->findAll();

    //Solicitar las secciones: HEADER+FOOTER
    $datos['header'] = view('Layouts/header');
    $datos['footer'] = view('Layouts/footer');

    return view('libros/listar', $datos);
  }

  public function crear(): string
  {
    $datos['header'] = view('Layouts/header');
    $datos['footer'] = view('Layouts/footer');

    return view('libros/crear', $datos);
  }

  public function editar($id = null)
  {
    $libro = new Libro();

    $datos['header'] = view('Layouts/header');
    $datos['footer'] = view('Layouts/footer');
    $result = $libro->where('id', $id)->first();

    if (!$result){ 
      //Redirección (cambiara la URL manualmente - NAVEGACIÓN)
      return $this->response->redirect(base_url('libros'));
    }else{
      //Agrego una nueva clave conteniendo los datos del libro
      $datos['libro'] = $result;
      return view('libros/editar', $datos);
    }
  }

  //Recibe los datos desde la vista y los guarda en la BD
  public function guardar() {
    $libro = new Libro();

    $nombre = $this->request->getVar('nombre'); //<tag name="">
    
    //Subir archivo imagen
    if ($imagen = $this->request->getFile('imagen')){
      $nuevoNombre = $imagen->getRandomName();
      $imagen->move('../public/uploads/', $nuevoNombre);

      $registro = [
        'nombre'    => $nombre,
        'imagen'    => $nuevoNombre
      ];

      $libro->insert($registro);
      return $this->response->redirect(base_url('libros'));
    }

    //$libro->insert();
  }

  public function borrar($id = null){
    $libro = new Libro();

    //Eliminar el archivo físico (imagen)
    //Utilizar ID (PK) > obtener el nombre físico del archivo de imagen
    $datosLibro = $libro->where('id', $id)->first();
    $rutaImagen = '../public/uploads/' . $datosLibro['imagen'];

    //Eliminarlo en caso exista...
    if (file_exists($rutaImagen)){ unlink($rutaImagen); }

    //Eliminar el registro de la tabla "libros"
    $libro->where('id', $id)->delete($id);

    return $this->response->redirect(base_url('libros'));
  }

  public function buscar(){
    $datos['header'] = view('Layouts/header');
    $datos['footer'] = view('Layouts/footer');
    
  }

  public function buscarLibro(){

    $libro = new Libro();

    $this->response->setContentType('application/json');

    $input = $this->request->getJSON();

    if(empty($input->id)){
      return $this->response->setJSON([
        'success' => false,
        'message' => 'debe indicar el ID'
      ]);
    }

    $id = $input->id;

    $registro = $libro->where('id', $id)->first();

    if(!$registro){
      return $this->response->setJSON([
        'success' => false,
        'message' =>'no  existe el libro'  
      ]);
    }


    return $this->response->setJSON([
      'seccess' =>  true,
      'nombre'  =>  $registro['nombre']  
    ]);


  }



  public function actualizar(){
    $libro = new Libro();

    //Actualización requiere:
    //1. Campos a ser actualizados (arreglo asociativo)
    $datos = [
      'nombre'    => $this->request->getVar('nombre') //<input name="nombre" type="text">
    ];

    //2. Clave primaria
    $id = $this->request->getVar('id'); //<input name="id" type="hidden">

    //Actualizar el registro
    $libro->update($id, $datos);

    //Validación del archivo binario (jpg, pdf, docx, xlsx)
    $validacion = $this->validate([
      'imagen' => [
        'uploaded[imagen]',
        'mime_in[imagen,image/png,image/jpg,image/jpeg]',
        'max_size[imagen,1024]'
      ]
    ]);

    //¡La imagen es correcta!, podemos actualizarla...
    if ($validacion){
      if ($imagen = $this->request->getFile('imagen')){
        
        //1. Obtener el nombre de la imagen anterior
        $datosLibro = $libro->where('id', $id)->first(); //Obtenemos los datos del libro
        $rutaImagen = '../public/uploads/' . $datosLibro['imagen'];
  
        //2. Eliminar la imagen anterior
        if (file_exists($rutaImagen)) { unlink($rutaImagen); }
  
        //3. Reemplazar por la nueva imagen
        $nuevoNombre = $imagen->getRandomName();
        $imagen->move('../public/uploads/', $nuevoNombre);
  
        //4. Actualizar la BD con el nuevo nombre aleatorio
        $datos = ["imagen" => $nuevoNombre];
        $libro->update($id, $datos);  //Solo se actualizará el nombre de la imagen
      }
    }

    return $this->response->redirect(base_url('libros'));
  }

}