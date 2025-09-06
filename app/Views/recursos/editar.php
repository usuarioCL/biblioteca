<?= $header; ?>
<div class="container mt-2">
  <div class="my-4">
    <h4><i class="fas fa-edit"></i> Editar Recurso</h4>
    <a href="<?= base_url('recursos'); ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
  </div>
  
  <div class="card">
    <div class="card-body">
      <?php if (session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <?php if (session('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Errores de validación:</strong>
          <ul class="mb-0 mt-2">
            <?php foreach (session('errors') as $error): ?>
              <li><?= $error ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= base_url('recursos/actualizar/' . $recurso['idrecurso']); ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <!-- Información General -->
        <div class="row mb-4">
          <div class="col-12">
            <h5 class="border-bottom pb-2"><i class="fas fa-info-circle"></i> Información General</h5>
          </div>
          
          <div class="col-12 mb-3">
            <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="titulo" name="titulo" required 
                   value="<?= old('titulo', $recurso['titulo']) ?>" maxlength="255">
          </div>
          
          <div class="col-md-4 mb-3">
            <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
            <select class="form-select" id="categoria" name="categoria" required>
              <option value="">Seleccione una categoría</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['idcategoria']; ?>" <?= ($categoriaActual == $cat['idcategoria']) ? 'selected' : '' ?>>
                  <?= esc($cat['categoria']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div class="col-md-4 mb-3">
            <label for="subcategoria" class="form-label">SubCategoría <span class="text-danger">*</span></label>
            <select class="form-select" id="subcategoria" name="subcategoria" required>
              <option value="">Seleccione una subcategoría</option>
              <?php foreach ($subcategorias as $subcat): ?>
                <option value="<?= $subcat['idsubcategoria']; ?>" <?= ($recurso['idsubcategoria'] == $subcat['idsubcategoria']) ? 'selected' : '' ?>>
                  <?= esc($subcat['subcategoria']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div class="col-md-4 mb-3">
            <label for="editorial" class="form-label">Editorial <span class="text-danger">*</span></label>
            <select class="form-select" id="editorial" name="editorial" required>
              <option value="">Seleccione una editorial</option>
              <?php foreach ($editoriales as $ed): ?>
                <option value="<?= $ed['ideditorial']; ?>" <?= ($recurso['ideditorial'] == $ed['ideditorial']) ? 'selected' : '' ?>>
                  <?= esc($ed['editorial']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Detalles del Recurso -->
        <div class="row mb-4">
          <div class="col-12">
            <h5 class="border-bottom pb-2"><i class="fas fa-cog"></i> Detalles del Recurso</h5>
          </div>
          
          <div class="col-md-4 mb-3">
            <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
            <select class="form-select" id="tipo" name="tipo" required>
              <option value="">Seleccione un tipo</option>
              <option value="Físico" <?= ($recurso['tipo'] == 'Físico') ? 'selected' : '' ?>>Físico</option>
              <option value="DIGITAL" <?= ($recurso['tipo'] == 'DIGITAL') ? 'selected' : '' ?>>Digital</option>
            </select>
          </div>
          
          <div class="col-md-4 mb-3">
            <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
            <select name="estado" id="estado" class="form-select" required>
              <option value="">Seleccione un estado</option>
              <option value="Bueno" <?= ($recurso['estado'] == 'Bueno') ? 'selected' : '' ?>>Bueno</option>
              <option value="Regular" <?= ($recurso['estado'] == 'Regular') ? 'selected' : '' ?>>Regular</option>
              <option value="Malo" <?= ($recurso['estado'] == 'Malo') ? 'selected' : '' ?>>Malo</option>
            </select>
          </div>
          
          <div class="col-md-4 mb-3">
            <label for="apublicacion" class="form-label">Año de Publicación <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="apublicacion" name="apublicacion" required 
                   min="1900" max="<?= date('Y'); ?>" value="<?= old('apublicacion', $recurso['apublicacion']); ?>">
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" id="isbn" name="isbn" maxlength="13" 
                   value="<?= old('isbn', $recurso['isbn']) ?>" placeholder="Opcional - 13 dígitos">
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="numpaginas" class="form-label">Número de Páginas</label>
            <input type="number" class="form-control" id="numpaginas" name="numpaginas" min="1" 
                   value="<?= old('numpaginas', $recurso['numpaginas']) ?>" placeholder="Opcional">
          </div>
        </div>

        <!-- Archivos -->
        <div class="row mb-4">
          <div class="col-12">
            <h5 class="border-bottom pb-2"><i class="fas fa-file"></i> Archivos</h5>
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="rutaportada" class="form-label">Portada</label>
            <?php if (!empty($recurso['rutaportada'])): ?>
              <div class="mb-2 p-2 bg-light rounded">
                <small class="text-muted">Portada actual:</small><br>
                <img src="<?= base_url($recurso['rutaportada']); ?>" alt="Portada actual" 
                     class="img-thumbnail mt-1" style="max-width: 150px; max-height: 200px;">
              </div>
            <?php endif; ?>
            <input type="file" class="form-control" id="rutaportada" name="rutaportada" accept="image/*">
            <small class="form-text text-muted">Opcional - Dejar vacío para mantener la portada actual</small>
          </div>
          
          <div class="col-md-6 mb-3">
            <label for="rutarecurso" class="form-label">Archivo del Recurso</label>
            <?php if (!empty($recurso['rutarecurso'])): ?>
              <div class="mb-2 p-2 bg-light rounded">
                <small class="text-muted">Archivo actual:</small><br>
                <a href="<?= base_url($recurso['rutarecurso']); ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-1">
                  <i class="fas fa-download"></i> Ver archivo actual
                </a>
              </div>
            <?php endif; ?>
            <input type="file" class="form-control" id="rutarecurso" name="rutarecurso" accept=".pdf,.doc,.docx">
            <small class="form-text text-muted">Opcional - Dejar vacío para mantener el archivo actual</small>
          </div>
        </div>
        
        <div class="row">
          <div class="col-12">
            <hr>
            <div class="d-flex justify-content-end gap-2">
              <a href="<?= base_url('recursos'); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Recurso
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $footer; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoriaSelect = document.getElementById('categoria');
    const subcategoriaSelect = document.getElementById('subcategoria');
    
    // Cargar subcategorías cuando cambie la categoría
    categoriaSelect.addEventListener('change', function() {
        const categoriaId = this.value;
        
        if (!categoriaId) {
            subcategoriaSelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';
            return;
        }

        subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
        subcategoriaSelect.disabled = true;

        fetch(`<?= base_url('recursos/subcategoriasPorCategoria'); ?>/${categoriaId}`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Seleccione una subcategoría</option>';
                data.forEach(subcat => {
                    options += `<option value="${subcat.idsubcategoria}">${subcat.subcategoria}</option>`;
                });
                subcategoriaSelect.innerHTML = options;
            })
            .catch(error => {
                console.error('Error:', error);
                subcategoriaSelect.innerHTML = '<option value="">Error al cargar</option>';
            })
            .finally(() => {
                subcategoriaSelect.disabled = false;
            });
    });
});
</script>