<?= $header; ?>

<div class="container mt-2">
  <div class="my-2">
    <h4>Registro de Libros</h4>
    <a href="<?= base_url("libros"); ?>">Volver</a>
  </div>

  <form method="POST" action="<?= base_url('libros/guardar') ?>" enctype="multipart/form-data">
    <div class="card">
      <div class="card-body">
        <div class="mb-3">
          <label for="nombre">Nombre del libro</label>
          <input type="text" class="form-control" name="nombre" id="nombre" autofocus required>
        </div>
        <div>
          <label for="imagen">Adjuntar imagen de portada</label>
          <input type="file" class="form-control" name="imagen" id="imagen">
        </div>
      </div>
      <div class="card-footer text-end">
        <button type="reset" class="btn btn-sm btn-outline-secondary">Cancelar</button>
        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
      </div>
    </div>
  </form>

</div>

<?= $footer; ?>