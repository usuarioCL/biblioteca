<?= $header; ?>
<div class="container mt-2">
  <div class=" my-4">
    <h4>Agregar Nuevo Recurso</h4>
    <a href="<?= base_url('recursos'); ?>">Volver al Listado</a>
  </div>
  <div class="card">
    <div class="card-body">
  <form id="formRecurso" method="post" action="<?= base_url('recursos/guardar'); ?>" enctype="multipart/form-data">
        <!-- Sección: Información General -->
        <h5 class="mt-3">Información General</h5>
        <hr>
        <div class="mb-3">
          <label for="titulo" class="form-label">Título</label>
          <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <select class="form-select" id="categoria" name="categoria" required>
              <option value="">Seleccione una categoría</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['idcategoria']; ?>"><?= $cat['categoria']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label for="subcategoria" class="form-label">SubCategoría</label>
            <select class="form-select" id="subcategoria" name="subcategoria" required>
              <option value="">Seleccione una subcategoría</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label for="editorial" class="form-label">Editorial</label>
            <select class="form-select" id="editorial" name="editorial" required>
              <option value="">Seleccione una editorial</option>
              <?php foreach ($editoriales as $ed): ?>
                <option value="<?= $ed['ideditorial']; ?>"><?= $ed['editorial']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Sección: Detalles del Recurso -->
        <h5 class="mt-4">Detalles del Recurso</h5>
        <hr>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select class="form-select" id="tipo" name="tipo" required>
              <option value="">Seleccione un tipo</option>
              <option value="Físico">Físico</option>
              <option value="DIGITAL">DIGITAL</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select" required>
              <option value="">Seleccione un estado</option>
              <option value="Bueno">Bueno</option>
              <option value="Regular">Regular</option>
              <option value="Malo">Malo</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label for="apublicacion" class="form-label">Año de Publicación</label>
            <input type="number" class="form-control" id="apublicacion" name="apublicacion" required min="1900" max="<?= date('Y'); ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" id="isbn" name="isbn">
          </div>
          <div class="col-md-4 mb-3">
            <label for="numpaginas" class="form-label">Número de Páginas</label>
            <input type="number" class="form-control" id="numpaginas" name="numpaginas">
          </div>
        </div>

        <!-- Sección: Archivos -->
        <h5 class="mt-4">Archivos</h5>
        <hr>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="rutaportada" class="form-label">Portada</label>
            <input type="file" class="form-control" id="rutaportada" name="rutaportada">
          </div>
          <div class="col-md-6 mb-3">
            <label for="rutarecurso" class="form-label">Archivo del Recurso</label>
            <input type="file" class="form-control" id="rutarecurso" name="rutarecurso">
          </div>
        </div>
          <button type="submit" class="btn btn-primary mt-3">Guardar Recurso</button>
      </form>
    </div>
  </div>
</div>
<?= $footer; ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
  const categoriaSelect = document.getElementById('categoria');
  const subcategoriaSelect = document.getElementById('subcategoria');

  categoriaSelect.addEventListener('change', function() {
    const categoriaId = this.value;
    subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
    if (categoriaId) {
      fetch('<?= base_url('recursos/subcategoriasPorCategoria'); ?>/' + categoriaId)
        .then(response => response.json())
        .then(data => {
          let options = '<option value="">Seleccione una subcategoría</option>';
          data.forEach(function(subcat) {
            options += `<option value="${subcat.idsubcategoria}">${subcat.subcategoria}</option>`;
          });
          subcategoriaSelect.innerHTML = options;
        })
        .catch(() => {
          subcategoriaSelect.innerHTML = '<option value="">Error al cargar</option>';
        });
    } else {
      subcategoriaSelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';
    }
  });
});
</script>