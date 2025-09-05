<?= $header; ?>
<style>
  .form-control:focus, .form-select:focus {
    background-color: aliceblue;
  }
</style>

<div class="container mt-2">
  <div class="my-2">
    <h4>Registro de Personas</h4>
    <a href="<?= base_url("personas"); ?>">Volver</a>
  </div>
  <form action="<?= base_url('personas/guardar'); ?>" method="POST" id="formPersona">
    <div class="card">
      <div class="card-body">

        <div class="mb-2">
          <label for="dni">Buscador por DNI</label><small id="searching" class="d-none"> - Buscando datos</small>
          <div class="input-group">
            <input type="text" class="form-control" id="dni" name ="dni" maxlength="8" placeholder="DNI" required>
            <button class="btn btn-sm btn-outline-secondary" type="button" id="buscar-dni">Buscar</button>
          </div>
        </div>
        

        <div class="row g-2">
          <div class="col-md-6 mb-2">
            <label for="nombres">Nombres</label>
            <input type="text" class="form-control" id="nombres" name ="nombres" placeholder="Nombres" required>
          </div>
          <div class="col-md-6 mb-2">
            <label for="apellidos">Apellidos</label>
            <input type="text" class="form-control" id="apellidos" name ="apellidos" placeholder="Apellidos" required>
          </div>
        </div>

        <div class="row g-2">
          <div class="col-md-3 mb-2">
            <label for="telefono">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name ="telefono" placeholder="Teléfono" required>
          </div>
          <div class="col-md-9 mb-2">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name ="direccion" placeholder="Dirección">
          </div>
        </div>

        <div class="row g-2">
          <div class="col-md-4 mb-2">
            <label for="departamentos">Departamentos</label>
            <select name="departamentos" id="departamentos" class="form-select" required>
              <option value="">Seleccione un departamento</option>
              <?php foreach($departamentos as $dep): ?>
                <option value="<?= $dep['iddepartamento']; ?>"><?= $dep['departamento']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4 mb-2">
            <label for="provincias">Provincias</label>
            <select name="provincias" id="provincias" class="form-select" required>
              <option value="">Seleccione una provincia</option>
            </select>
          </div>
          <div class="col-md-4 mb-2">
            <label for="distritos">Distritos</label>
            <select name="distritos" id="distritos" class="form-select" required>
              <option value="">Seleccione un distrito</option>
            </select>
          </div>
        </div>

      </div>

      <div class="card-footer text-end">
    <button class="btn btn-sm btn-outline-secondary" type="button" onclick="window.location.href='<?= base_url('personas'); ?>'">Cancelar</button>
    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
      </div>
    </div>
  </form>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const botonBusqueda = document.querySelector("#buscar-dni");
    const dni = document.querySelector("#dni");
    const nombres = document.querySelector("#nombres");
    const apellidos = document.querySelector("#apellidos");
    const buscando = document.querySelector("#searching");

    //Ubigeo
    const departamentos = document.querySelector("#departamentos");
    const provincias = document.querySelector("#provincias");
    const distritos = document.querySelector("#distritos");
    const formPersona = document.querySelector("#formPersona");

    formPersona.addEventListener('submit', function(e) {
      e.preventDefault();
      
      Swal.fire({
        title: '¿Confirmar registro?',
        text: "¿Está seguro de registrar esta persona?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, registrar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          // Mostrar loading
          Swal.fire({
            title: 'Guardando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading()
            }
          });
          
          // Enviar formulario
          formPersona.submit();
        }
      });
    });

    departamentos.addEventListener('change', async () =>{
      const iddepartamento = departamentos.value;

      if(!iddepartamento){
        provincias.innerHTML = '<option value="">Seleccione una provincia</option>';
        distritos.innerHTML = '<option value="">Seleccione un distrito</option>';
        return;
      }
      try{
        const response = await fetch(`<?= base_url('api/ubigeo/provincias/') ?>${iddepartamento}`,{
          method: 'GET',
          headers: {
           'content-type': 'application/json'
          }
        })

        if (!response.ok) {
          throw new Error('Error en la solicitud: ' + response.status);
        }

        const data = await response.json();
        if(data.length){
          provincias.innerHTML = '<option value="">Seleccione una provincia</option>';
          data.forEach(provincia => {
            provincias.innerHTML += `<option value="${provincia.idprovincia}">${provincia.provincia}</option>`;
          });
        }
      }
      catch(error){
        console.error('Error al cargar las provincias:', error)
      }
    })

    provincias.addEventListener('change', async () =>{
      const idprovincia = provincias.value;

      if(!idprovincia){
        distritos.innerHTML = '<option value="">Seleccione un distrito</option>';
        return;
      }
      try{
        const response = await fetch(`<?= base_url('api/ubigeo/distritos/') ?>${idprovincia}`,{
          method: 'GET',
          headers: {
           'content-type': 'application/json'
          }
        })

        if (!response.ok) {
          throw new Error('Error en la solicitud: ' + response.status);
        }

        const data = await response.json();
        if(data.length){
          distritos.innerHTML = '<option value="">Seleccione un distrito</option>';
          data.forEach(distrito => {
            distritos.innerHTML += `<option value="${distrito.iddistrito}">${distrito.distrito}</option>`;
          });
        }
      }
      catch(error){
        console.error('Error al cargar los distritos:', error)
      }
    })

    botonBusqueda.addEventListener("click", async() =>{
      if (!dni.value) {
        alert('Por favor, ingrese un DNI válido.');
        return;
      }
      try{
        buscando.classList.remove('d-none');
        const response = await fetch(`<?= base_url('api/personas/buscardni/') ?>${dni.value}`,{
          method: 'GET',
          headers: {
            'Authorization': 'Bearer sk_10072.iEPfTIhBGJtcLVrHEelCO3CluNuwejz0',
            'Content-Type': 'application/json'
          }
        })
        if (!response.ok) {
          throw new Error('Error en la solicitud: ' + response.status);
        }
        const data = await response.json();
        buscando.classList.add('d-none');
        console.log(data);

        if(data.success){
          apellidos.value = `${data.data.first_last_name || ''} ${data.data.second_last_name || ''}`.trim();
          nombres.value = data.data.first_name || '';
        }
      
      }
      catch(error){
        console.error('Error al buscar el DNI:', error)
      }
  });
    
    dni.addEventListener('keydown', function(event) {
      if (event.key === 'Enter') {
        event.preventDefault(); 
        botonBusqueda.click();
      }
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?= $footer; ?>