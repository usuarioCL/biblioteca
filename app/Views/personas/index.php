<?= $header; ?>

<div class="container mt-2">
  <div class="my-2">
    <h4>Lista de Personas</h4>
    <div class="row">
      <div class="col-2">
        <a href="<?= base_url("personas/crear"); ?>" class="btn btn-sm btn-info">Registrar</a>
      </div>
      <div class="col-10 d-flex justify-content-end align-items-center"> 
        <input type="text" id="searchInput" placeholder="Buscar por DNI" class="form-control form-control-sm" style="width: 200px; display: inline-block; margin-left: 10px;" maxlength="8" pattern="\d{8}" inputmode="numeric" value="<?= isset($searchTerm) ? esc($searchTerm) : '' ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,8);">
        <button id="searchButton" class="btn btn-sm btn-primary ms-2" onclick="window.location.href='<?= base_url('personas/buscar'); ?>?dni=' + document.getElementById('searchInput').value;">Buscar</button>
        <?php if (isset($searchTerm) && !empty($searchTerm)): ?>
          <a href="<?= base_url('personas'); ?>" class="btn btn-sm btn-secondary ms-2">Ver todos</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-sm table-striped align-middle">
      <thead class="table-light"></thead>
        <tr>
          <th>#</th>
          <th>Nombres</th>
          <th>Apellidos</th>
          <th>DNI</th>
          <th>Telefono</th>
          <th>Departamento</th>
          <th>Provincia</th>
          <th>Distrito</th>
          <th>Ubigeo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($personas as $persona) { ?>
          <tr>
            <td><?= $persona['idpersona']; ?></td>
            <td><?= $persona['nombres']; ?></td>
            <td><?= $persona['apellidos']; ?></td>
            <td><?= $persona['dni']; ?></td>
            <td><?= $persona['telefono']; ?></td>
            <td><?= $persona['departamento']; ?></td>
            <td><?= $persona['provincia']; ?></td>
            <td><?= $persona['distrito']; ?></td>
            <td><?= $persona['iddistrito']; ?></td>
            <td>
              <a href="<?= base_url('personas/editar/'.$persona['idpersona']); ?>" class="btn btn-sm btn-warning mb-1">Editar</a>
              <button onclick="confirmarEliminacion(<?= $persona['idpersona']; ?>, '<?= $persona['nombres']; ?>', '<?= $persona['apellidos']; ?>')" class="btn btn-sm btn-danger mb-1">Eliminar</button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmarEliminacion(idpersona, nombres, apellidos) {
    Swal.fire({
      title: '¿Estás seguro?',
      text: `¿Deseas eliminar a ${nombres} ${apellidos}? Esta acción no se puede deshacer.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        // Mostrar loading
        Swal.fire({
          title: 'Eliminando...',
          text: 'Por favor espere',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
          }
        });
        
        // Redirigir a la URL de eliminación
        window.location.href = '<?= base_url('personas/eliminar/') ?>' + idpersona;
      }
    });
  }

  <?php if(session()->getFlashdata('success')): ?>
    Swal.fire({
      title: '¡Éxito!',
      text: '<?= session()->getFlashdata('success') ?>',
      icon: 'success',
      confirmButtonText: 'OK'
    });
  <?php endif; ?>

  <?php if(session()->getFlashdata('error')): ?>
    Swal.fire({
      title: '¡Error!',
      text: '<?= session()->getFlashdata('error') ?>',
      icon: 'error',
      confirmButtonText: 'OK'
    });
  <?php endif; ?>

  document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      document.getElementById('searchButton').click();
    }
  });
  
</script>

<?= $footer; ?>