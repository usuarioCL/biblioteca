<?= $header; ?>
<style>
    .form-control.is-valid {
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.8-.8-.8-.8 1.48-1.48L7 7.48-2.3 6.73z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 2.4 2.4m0-2.4L5.8 7'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        display: none;
    }

    .required-field {
        color: #dc3545;
    }
</style>
<div class="container mt-2">
  <div class=" my-4">
    <h4>Agregar Nuevo Recurso</h4>
    <a href="<?= base_url('recursos'); ?>">Volver al Listado</a>
  </div>
  <div class="card">
    <div class="card-body">
  <form id="formRecurso" method="post" action="<?= base_url('recursos/guardar'); ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
        <!-- Sección: Información General -->
        <h5 class="mt-3">Información General</h5>
        <hr>
        <div class="mb-3">
          <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="titulo" name="titulo" required value="<?= old('titulo') ?>">
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
            <select class="form-select" id="categoria" name="categoria" required>
              <option value="">Seleccione una categoría</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['idcategoria']; ?>"><?= $cat['categoria']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label for="subcategoria" class="form-label">SubCategoría <span class="text-danger">*</span></label>
            <select class="form-select" id="subcategoria" name="subcategoria" required>
              <option value="">Seleccione una subcategoría</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label for="editorial" class="form-label">Editorial <span class="text-danger">*</span></label>
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
            <label for="apublicacion" class="form-label">Año de Publicación <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="apublicacion" name="apublicacion" required maxlength="4" min="1900" max="<?= date('Y'); ?>" value="<?= old('apublicacion', date('Y')); ?>" oninput="if(this.value.length>4)this.value=this.value.slice(0,4);" pattern="\\d{4}" title="Ingrese un año válido de 4 dígitos entre 1900 y <?= date('Y'); ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" id="isbn" name="isbn" maxlength="13" title="El ISBN debe tener exactamente 13 dígitos numéricos" value="<?= old('isbn') ?>">
          </div>
          <div class="col-md-4 mb-3">
            <label for="numpaginas" class="form-label">Número de Páginas</label>
            <input type="number" class="form-control" id="numpaginas" name="numpaginas" min="1" step="1" pattern="\\d+" title="Ingrese un número de páginas válido (mayor a 0)">
          </div>
        </div>

        <!-- Sección: Archivos -->
        <h5 class="mt-4">Archivos</h5>
        <hr>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="rutaportada" class="form-label">Portada <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="rutaportada" name="rutaportada" accept="image/*" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="rutarecurso" class="form-label">Archivo del Recurso</label>
            <input type="file" class="form-control" id="rutarecurso" name="rutarecurso" accept=".pdf,.doc,.docx">
          </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button type="button" class="btn btn-secondary me-md-2" onclick="window.location.href='<?= base_url('recursos'); ?>'">
            <i class="fas fa-arrow-left"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar Recurso
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $footer; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del formulario
    const form = document.getElementById('formRecurso');
    const categoriaSelect = document.getElementById('categoria');
    const subcategoriaSelect = document.getElementById('subcategoria');
    const anioInput = document.getElementById('apublicacion');
    const isbnInput = document.getElementById('isbn');
    const paginasInput = document.getElementById('numpaginas');
    const portadaInput = document.getElementById('rutaportada');
    const archivoInput = document.getElementById('rutarecurso');
    const tipoInput = document.getElementById('tipo');

    // Función para mostrar toasts
    const showToast = (message, type = 'info') => {
        const bgColor = {
            'success': 'linear-gradient(to right, #00b09b, #96c93d)',
            'error': 'linear-gradient(to right, #ff5f6d, #ffc371)', 
            'warning': 'linear-gradient(to right, #f093fb, #f5576c)',
            'info': 'linear-gradient(to right, #4facfe, #00f2fe)'
        };

        Toastify({
            text: message,
            duration: 2000,
            close: true,
            gravity: "top",
            position: "right",
            style: {
                background: bgColor[type] || bgColor['info'],
            }
        }).showToast();
    };

    // Cargar subcategorías por categoría usando async/await
    categoriaSelect.addEventListener('change', async function() {
        const categoriaId = this.value;
        
        if (!categoriaId) {
            subcategoriaSelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';
            return;
        }

        subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
        subcategoriaSelect.disabled = true;

        try {
            const response = await fetch(`<?= base_url('recursos/subcategoriasPorCategoria'); ?>/${categoriaId}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            let options = '<option value="">Seleccione una subcategoría</option>';
            data.forEach(subcat => {
                options += `<option value="${subcat.idsubcategoria}">${subcat.subcategoria}</option>`;
            });
            
            subcategoriaSelect.innerHTML = options;
            showToast('Subcategorías cargadas correctamente', 'success');
            
        } catch (error) {
            console.error('Error al cargar subcategorías:', error);
            subcategoriaSelect.innerHTML = '<option value="">Error al cargar</option>';
            showToast('Error al cargar las subcategorías', 'error');
        } finally {
            subcategoriaSelect.disabled = false;
        }
    });

    // Validación de ISBN duplicado usando async/await
    isbnInput.addEventListener('blur', async function() {
        const isbn = this.value.trim();
        
        if (!isbn) return;
        
        // Validar formato ISBN
        if (!/^\d{13}$/.test(isbn)) {
            await Swal.fire({
                icon: 'warning',
                title: 'ISBN Inválido',
                text: 'El ISBN debe tener exactamente 13 dígitos numéricos.',
                confirmButtonText: 'Entendido'
            });
            this.value = '';
            this.focus();
            return;
        }

        try {
            const response = await fetch(`<?= base_url('recursos/verificarISBN'); ?>/${isbn}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.existe) {
                await Swal.fire({
                    icon: 'error',
                    title: 'ISBN Duplicado',
                    text: 'Este ISBN ya existe en la base de datos. Debe ser único para cada libro.',
                    confirmButtonText: 'Cambiar ISBN'
                });
                this.value = '';
                this.focus();
                showToast('ISBN duplicado detectado', 'error');
            } else {
                showToast('ISBN válido y disponible', 'success');
            }
            
        } catch (error) {
            console.error('Error al verificar ISBN:', error);
            showToast('Error al verificar ISBN', 'error');
        }
    });

    // Función de validación completa
    const validateForm = async () => {
        const errors = [];

        // Validar título
        if (!form.titulo.value.trim()) {
            errors.push('El título es obligatorio');
        }

        // Validar categoría y subcategoría
        if (!categoriaSelect.value) {
            errors.push('La categoría es obligatoria');
        }
        if (!subcategoriaSelect.value) {
            errors.push('La subcategoría es obligatoria');
        }

        // Validar editorial
        if (!form.editorial.value) {
            errors.push('La editorial es obligatoria');
        }

        // Validar tipo y estado
        if (!tipoInput.value) {
            errors.push('El tipo es obligatorio');
        }
        if (!form.estado.value) {
            errors.push('El estado es obligatorio');
        }

        // Validar año de publicación
        const anio = parseInt(anioInput.value);
        const anioActual = new Date().getFullYear();
        if (!anioInput.value || isNaN(anio) || anio < 1900 || anio > anioActual) {
            errors.push(`El año de publicación debe estar entre 1900 y ${anioActual}`);
        }

        // Validar ISBN si está presente
        if (isbnInput.value && !/^\d{13}$/.test(isbnInput.value)) {
            errors.push('El ISBN debe tener exactamente 13 dígitos numéricos');
        }

        // Validar número de páginas
        if (paginasInput.value && (isNaN(paginasInput.value) || parseInt(paginasInput.value) < 1)) {
            errors.push('El número de páginas debe ser mayor a 0');
        }

        // Validar archivos
        if (!portadaInput.files[0]) {
            errors.push('La portada es obligatoria');
        } else {
            const portadaExt = portadaInput.files[0].name.split('.').pop().toLowerCase();
            if (!['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(portadaExt)) {
                errors.push('La portada debe ser una imagen válida (jpg, jpeg, png, gif, bmp, webp)');
            }
        }

        // Validar archivo de recurso para tipo DIGITAL
        if (tipoInput.value === 'DIGITAL' && !archivoInput.files[0]) {
            errors.push('El archivo del recurso es obligatorio para recursos digitales');
        }

        if (archivoInput.files[0]) {
            const archivoExt = archivoInput.files[0].name.split('.').pop().toLowerCase();
            if (!['pdf', 'doc', 'docx'].includes(archivoExt)) {
                errors.push('El archivo del recurso debe ser PDF, DOC o DOCX');
            }
        }

        return errors;
    };

    // Envío del formulario usando async/await
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Mostrar loading
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

        try {
            // Validar formulario
            const errors = await validateForm();
            
            if (errors.length > 0) {
                await Swal.fire({
                    icon: 'warning',
                    title: 'Errores de Validación',
                    html: '<ul style="text-align: left;">' + errors.map(error => `<li>${error}</li>`).join('') + '</ul>',
                    confirmButtonText: 'Corregir'
                });
                return;
            }

            // Confirmar envío
            const result = await Swal.fire({
                title: '¿Confirmar Guardado?',
                text: '¿Está seguro de guardar este recurso?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            });

            if (!result.isConfirmed) return;

            // Preparar datos del formulario
            const formData = new FormData(form);

            // Enviar datos
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Verificar si la respuesta es una redirección
            if (response.redirected) {
                await Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Recurso guardado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                showToast('Recurso guardado correctamente', 'success');
                window.location.href = response.url;
            } else {
                // Si hay errores del servidor
                const responseText = await response.text();
                throw new Error('Error en el servidor');
            }

        } catch (error) {
            console.error('Error al guardar:', error);
            
            await Swal.fire({
                icon: 'error',
                title: 'Error al Guardar',
                text: 'Ocurrió un error inesperado. Por favor, intente nuevamente.',
                confirmButtonText: 'Entendido'
            });
            
            showToast('Error al guardar el recurso', 'error');
            
        } finally {
            // Restaurar botón
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });

    // Validación en tiempo real para el año
    anioInput.addEventListener('input', function() {
        const anio = parseInt(this.value);
        const anioActual = new Date().getFullYear();
        
        this.classList.remove('is-valid', 'is-invalid');
        
        if (this.value && (!isNaN(anio) && anio >= 1900 && anio <= anioActual)) {
            this.classList.add('is-valid');
        } else if (this.value) {
            this.classList.add('is-invalid');
        }
    });

    // Validación en tiempo real para número de páginas
    paginasInput.addEventListener('input', function() {
        const pages = parseInt(this.value);
        
        this.classList.remove('is-valid', 'is-invalid');
        
        if (this.value && (!isNaN(pages) && pages > 0)) {
            this.classList.add('is-valid');
        } else if (this.value) {
            this.classList.add('is-invalid');
        }
    });

    // Toast de bienvenida
    showToast('Formulario de recursos cargado correctamente', 'info');
});
</script>