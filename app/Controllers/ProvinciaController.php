<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Provincia;
use App\Models\Distrito;

class ProvinciaController extends BaseController
{
    public function getProvinciasByDepartamento($iddepartamento = "")
    {  
       $this->response->setContentType('application/json');
       // return $this->response->setJSON(['message' => $iddepartamento]);

       $provincia = new Provincia();

       $listaProvincias = $provincia->where('iddepartamento', $iddepartamento)
                                    ->orderBy('provincia', 'ASC')
                                    ->findAll();

      return $this->response->setJSON($listaProvincias);
      
    }

    public function getDistritosByProvincia($idprovincia = "")
    {  
       $this->response->setContentType('application/json');

       $distrito = new Distrito();

       $listaDistritos = $distrito->where('idprovincia', $idprovincia)
                                  ->orderBy('distrito', 'ASC')
                                  ->findAll();

      return $this->response->setJSON($listaDistritos);
      
    }
}