<?= $header; ?>
<div class="container mt-2">
  <div class=" my-4">
    <h4>Listado de Recursos</h4>
    <a href="<?= base_url('recursos/crear'); ?>">Agregar Recurso</a>
  </div>
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Título</th>
            <th>Tipo</th>
            <th>Año Publicación</th>
            <th>ISBN</th>
            <th>Portada</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($recursos) && is_array($recursos)): ?>
            <?php foreach($recursos as $recurso): ?>
              <tr>
                <td><?= $recurso['idrecurso']; ?></td>
                <td><?= $recurso['titulo']; ?></td>
                <td><?= $recurso['tipo']; ?></td>
                <td><?= $recurso['apublicacion']; ?></td>
                <td><?= $recurso['isbn']; ?></td>
                <td>
                  <?php if (!empty($recurso['rutaportada'])): ?>
                    <img src="<?= base_url($recurso['rutaportada']); ?>" alt="Portada" style="max-width: 80px; max-height: 100px;">
                  <?php else: ?>
                    Sin imagen
                  <?php endif; ?>
                </td>
                <td>
                  <a href="<?= base_url('recursos/edit/' . $recurso['idrecurso']); ?>" class="btn btn-warning">Editar</a>
                  <a href="<?= base_url('recursos/delete/' . $recurso['idrecurso']); ?>" class="btn btn-danger">Eliminar</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4">No hay recursos disponibles.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?= $footer; ?>