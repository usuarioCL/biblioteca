<?php 
namespace App\Controllers;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\Persona;
class PersonaController extends BaseController
{

    public function index()
    {   
        $persona = new Persona();

        $datos['personas'] = $persona->select('personas.*, 
                                                departamentos.departamento, 
                                                provincias.provincia, 
                                                distritos.distrito')
                                     ->join('distritos', 'distritos.iddistrito = personas.iddistrito')
                                     ->join('provincias', 'provincias.idprovincia = distritos.idprovincia')
                                     ->join('departamentos', 'departamentos.iddepartamento = provincias.iddepartamento')
                                     ->orderBy('personas.idpersona','ASC')
                                     ->findAll();

        $datos['header'] = view('Layouts/header');
        $datos['footer'] = view('Layouts/footer');
        return view('personas/index', $datos);
    }

    public function crear()
    {       $departamento = new Departamento();

            $datos['departamentos'] = $departamento->orderBy('departamento','ASC')->findAll();
            $datos['header'] = view('Layouts/header');
            $datos['footer'] = view('Layouts/footer');
            return view('personas/crear', $datos);
    }
    public function searchByDNI($dni = ""){
      
      //parametros sensibles API
      $api_endpoint = "https://api.decolecta.com/v1/reniec/dni?numero=".$dni;
      $api_token = "sk_10072.iEPfTIhBGJtcLVrHEelCO3CluNuwejz0";
      $content_type = "application/json";   

      //Configuracion de cURL para realizacion peticion
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $api_endpoint);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Authorization: Bearer ' . $api_token,
          'Content-Type: ' . $content_type
      ]);

      //Ejecutar peticion cURL
      $api_response = curl_exec($ch);
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      //Error en el servicio
      if ($api_response === false) {
          return $this->response->setJSON([
              'success' => false,
              'message' => 'No se pudo realizar la consulta',
          ]);
      } 

      //Decodificar la consulta
      $decoded_response = json_decode($api_response, true);
      
      if ($http_code === 404) {
          return $this->response->setJSON([
              'success' => false,
              'message' => 'No se encontró información para el DNI proporcionado',
          ]);
      }

      return $this->response->setJSON(
        [
          'success' => true,
          'data' => $decoded_response
        ]
      );
    } //searchByDNI

    public function editar($idpersona = null)
    {   
        $departamento = new Departamento();
        $persona = new Persona();

        $datos['departamentos'] = $departamento->orderBy('departamento','ASC')->findAll();
     
        // Obtener la persona con información de ubicación completa
        $datos['persona'] = $persona->select('personas.*, 
                                                departamentos.iddepartamento, 
                                                departamentos.departamento, 
                                                provincias.idprovincia,
                                                provincias.provincia, 
                                                distritos.iddistrito,
                                                distritos.distrito')
                                     ->join('distritos', 'distritos.iddistrito = personas.iddistrito')
                                     ->join('provincias', 'provincias.idprovincia = distritos.idprovincia')
                                     ->join('departamentos', 'departamentos.iddepartamento = provincias.iddepartamento')
                                     ->where('personas.idpersona', $idpersona)
                                     ->first();

        $datos['header'] = view('Layouts/header');
        $datos['footer'] = view('Layouts/footer');
        return view('personas/editar', $datos);
    }
    public function actualizar($idpersona = null)
    {
        $persona = new Persona();

        $datos = [
            'dni' => $this->request->getVar('dni'),
            'nombres' => $this->request->getVar('nombres'),
            'apellidos' => $this->request->getVar('apellidos'),
            'telefono' => $this->request->getVar('telefono'),
            'direccion' => $this->request->getVar('direccion'),
            'iddistrito' => $this->request->getVar('distritos'),
        ];

        try {
            $persona->update($idpersona, $datos);
            session()->setFlashdata('success', 'Persona actualizada exitosamente');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error al actualizar la persona: ' . $e->getMessage());
        }
        
        return redirect()->to(base_url('personas'));
    }

    public function guardar()
    {
        $persona = new Persona();

        $datos = [
            'dni' => $this->request->getVar('dni'),
            'nombres' => $this->request->getVar('nombres'),
            'apellidos' => $this->request->getVar('apellidos'),
            'telefono' => $this->request->getVar('telefono'),
            'direccion' => $this->request->getVar('direccion'),
            'iddistrito' => $this->request->getVar('distritos'),
        ];

        try {
            $persona->insert($datos);
            session()->setFlashdata('success', 'Persona registrada exitosamente');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error al registrar la persona: ' . $e->getMessage());
        }
        
        return redirect()->to(base_url('personas'));
    }

    public function eliminar($idpersona = null)
    {
        $persona = new Persona();

        try {
            $persona->delete($idpersona);
            session()->setFlashdata('success', 'Persona eliminada exitosamente');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error al eliminar la persona: ' . $e->getMessage());
        }
        
        return redirect()->to(base_url('personas'));
    }

    public function buscar()
    {
        $persona = new Persona();
        $dni = $this->request->getVar('dni');
        
        if ($dni && !empty(trim($dni))) {
            // Buscar personas que contengan el DNI (búsqueda parcial)
            $datos['personas'] = $persona->select('personas.*, 
                                                    departamentos.departamento, 
                                                    provincias.provincia, 
                                                    distritos.distrito')
                                         ->join('distritos', 'distritos.iddistrito = personas.iddistrito')
                                         ->join('provincias', 'provincias.idprovincia = distritos.idprovincia')
                                         ->join('departamentos', 'departamentos.iddepartamento = provincias.iddepartamento')
                                         ->like('personas.dni', $dni)
                                         ->orderBy('personas.idpersona','ASC')
                                         ->findAll();
            
            $datos['searchTerm'] = $dni;
        } else {
            // Si no hay DNI o está vacío, mostrar todas las personas sin mensaje
            $datos['personas'] = $persona->select('personas.*, 
                                                    departamentos.departamento, 
                                                    provincias.provincia, 
                                                    distritos.distrito')
                                         ->join('distritos', 'distritos.iddistrito = personas.iddistrito')
                                         ->join('provincias', 'provincias.idprovincia = distritos.idprovincia')
                                         ->join('departamentos', 'departamentos.iddepartamento = provincias.iddepartamento')
                                         ->orderBy('personas.idpersona','ASC')
                                         ->findAll();
            $datos['searchTerm'] = '';
        }

        $datos['header'] = view('Layouts/header');
        $datos['footer'] = view('Layouts/footer');
        return view('personas/index', $datos);
    }

}
