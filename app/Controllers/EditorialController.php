<?php

namespace App\Controllers;
use App\Controllers\BaseController;

class EditorialController extends BaseController
{

  public function index(): string
  {
    //Solicitar las secciones: HEADER+FOOTER
    $datos['header'] = view('Layouts/header');
    $datos['footer'] = view('Layouts/footer');

    return view('editoriales/index', $datos);
  }

  public function editar(): string
  {
    return view('editoriales/editar');
  }

  public function crear(): string
  {
    return view('editoriales/crear');
  }



}