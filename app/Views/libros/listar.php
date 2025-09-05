<?= $header; ?>

<div class="container mt-2">
  <div class="my-2">
    <h4>Lista de Libros</h4>
    <a href="<?= base_url("libros/crear"); ?>" class="btn btn-sm btn-info">Registrar</a>
    <a href="<?= base_url("libros/buscar"); ?>"  class="btn btn-sm btn-info">buscar</a>
  </div>

  <div class="table-resposive">
    <table class="table table-sm">
      <colgroup>
        <col width="10%">
        <col width="40%">
        <col width="30%">
        <col width="20%">
      </colgroup>
      <thead>
        <tr>
          <th>ID</th>
          <th>Libro</th>
          <th>Imagen</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>

        <?php foreach($libros as $libro): ?>

        <tr class="align-middle">
          <td><?= $libro['id'] ?></td>
          <td><?= $libro['nombre'] ?></td>
          <td>
            <img src="<?= base_url("uploads/") ?><?= $libro['imagen'] ?>" alt="Portada de libro" class="img-thumbnail" style="width: 120px">
          </td>
          <td>
            <a href="<?= base_url('libros/borrar/') ?><?= $libro['id'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
            <a href="<?= base_url('libros/editar/') ?><?= $libro['id'] ?>" class="btn btn-sm btn-info">Editar</a>
          </td>
        </tr>

        <?php endforeach; ?>

      </tbody>
    </table>
  </div>

</div>

<?= $footer; ?>