<?= $header; ?>
<div class="container-fluid py-4">
  <!-- Header Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center mb-2 mb-md-0">
          <div class="icon-bg-primary me-3">
            <i class="bi bi-collection fs-4 text-white"></i>
          </div>
          <div>
            <h2 class="mb-0 fw-bold text-dark">Gestión de Recursos</h2>
            <p class="text-muted mb-0">Administra la biblioteca digital</p>
          </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <a href="<?= base_url('recursos/crear'); ?>" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Agregar Recurso
          </a>
          <button class="btn btn-outline-secondary btn-lg" onclick="recargarTabla()">
            <i class="bi bi-arrow-clockwise me-2"></i>Actualizar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content Card -->
  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-lg">
        <div class="card-header bg-gradient-primary text-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="bi bi-table me-2"></i>Lista de Recursos Disponibles
            </h5>
            <span class="badge bg-light text-primary fs-6">
              <?= !empty($recursos) ? count($recursos) : 0 ?> recursos
            </span>
          </div>
        </div>
        <div class="card-body p-0">
          <!-- Alert Messages -->
          <?php if (session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show m-3 border-0 shadow-sm" role="alert">
              <div class="d-flex align-items-center">
                <i class="bi bi-check-circle me-2 fs-5"></i>
                <div>
                  <strong>¡Éxito!</strong> <?= session('success') ?>
                </div>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>
          
          <?php if (session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show m-3 border-0 shadow-sm" role="alert">
              <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle me-2 fs-5"></i>
                <div>
                  <strong>Error:</strong> <?= session('error') ?>
                </div>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <!-- Table Section -->
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="recursosTable">
              <thead class="table-dark">
                <tr>
                  <th scope="col" class="text-center">#</th>
                  <th scope="col">Información del Recurso</th>
                  <th scope="col" class="text-center">Categorización</th>
                  <th scope="col" class="text-center">Publicación</th>
                  <th scope="col" class="text-center">Portada</th>
                  <th scope="col" class="text-center">Archivo</th>
                  <th scope="col" class="text-center">Estado</th>
                  <th scope="col" class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
          <?php if(!empty($recursos) && is_array($recursos)): ?>
            <?php foreach($recursos as $index => $recurso): ?>
              <tr class="table-row-hover">
                <td class="text-center fw-bold text-primary"><?= $recurso['idrecurso']; ?></td>
                <td>
                  <div class="d-flex flex-column">
                    <h6 class="mb-1 text-dark fw-semibold"><?= $recurso['titulo']; ?></h6>
                    <div class="d-flex flex-wrap gap-2 small text-muted">
                      <span><i class="bi bi-building me-1"></i><?= $recurso['editorial']; ?></span>
                      <span><i class="bi bi-bookmark me-1"></i><?= $recurso['tipo']; ?></span>
                      <?php if (!empty($recurso['isbn'])): ?>
                        <span><i class="bi bi-upc me-1"></i><?= $recurso['isbn']; ?></span>
                      <?php endif; ?>
                      <?php if (!empty($recurso['numpaginas'])): ?>
                        <span><i class="bi bi-file-text me-1"></i><?= $recurso['numpaginas']; ?> págs.</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-items-center">
                    <span class="badge bg-primary mb-1"><?= $recurso['categoria']; ?></span>
                    <span class="badge bg-secondary"><?= $recurso['subcategoria']; ?></span>
                  </div>
                </td>
                <td class="text-center">
                  <span class="badge bg-info text-dark fs-6"><?= $recurso['apublicacion']; ?></span>
                </td>
                <td class="text-center">
                  <?php if (!empty($recurso['rutaportada'])): ?>
                    <div class="position-relative">
                      <img 
                        src="<?= base_url($recurso['rutaportada']); ?>" 
                        alt="Portada de <?= $recurso['titulo']; ?>" 
                        class="img-thumbnail cursor-pointer portada-preview" 
                        style="width: 60px; height: 80px; object-fit: cover;"
                        data-bs-toggle="modal" 
                        data-bs-target="#portadaModal"
                        data-image="<?= base_url($recurso['rutaportada']); ?>"
                        data-title="<?= addslashes($recurso['titulo']); ?>"
                      >
                    </div>
                  <?php else: ?>
                    <div class="d-flex flex-column align-items-center">
                      <i class="bi bi-image text-muted fs-2 mb-1"></i>
                      <span class="badge bg-secondary">Sin portada</span>
                    </div>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if (!empty($recurso['rutarecurso'])): ?>
                    <a href="<?= base_url($recurso['rutarecurso']); ?>" target="_blank" class="btn btn-outline-primary btn-sm shadow-sm">
                      <i class="bi bi-download me-1"></i>Descargar
                    </a>
                  <?php else: ?>
                    <div class="d-flex flex-column align-items-center">
                      <i class="bi bi-file-earmark-slash text-muted fs-4 mb-1"></i>
                      <span class="badge bg-warning text-dark">Sin archivo</span>
                    </div>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php
                    $badgeClass = '';
                    $iconClass = '';
                    switch($recurso['estado']) {
                      case 'Bueno':
                        $badgeClass = 'bg-success';
                        $iconClass = 'bi bi-check-circle';
                        break;
                      case 'Regular':
                        $badgeClass = 'bg-warning text-dark';
                        $iconClass = 'bi bi-exclamation-triangle';
                        break;
                      case 'Malo':
                        $badgeClass = 'bg-danger';
                        $iconClass = 'bi bi-x-circle';
                        break;
                      default:
                        $badgeClass = 'bg-secondary';
                        $iconClass = 'bi bi-question-circle';
                    }
                  ?>
                  <span class="badge <?= $badgeClass; ?> px-3 py-2">
                    <i class="<?= $iconClass; ?> me-1"></i><?= $recurso['estado']; ?>
                  </span>
                </td>
                <td class="text-center">
                  <div class="btn-group" role="group">
                    <a 
                      href="<?= base_url('recursos/editar/' . $recurso['idrecurso']); ?>" 
                      class="btn btn-warning btn-sm shadow-sm"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title="Editar recurso"
                    >
                      <i class="bi bi-pencil"></i>
                    </a>
                    <button 
                      type="button" 
                      class="btn btn-danger btn-sm shadow-sm" 
                      onclick="confirmarEliminacion(<?= $recurso['idrecurso']; ?>, '<?= addslashes($recurso['titulo']); ?>')"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title="Eliminar recurso"
                    >
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="d-flex flex-column align-items-center text-muted">
                  <i class="bi bi-inbox fs-1 mb-3"></i>
                  <h5>No hay recursos disponibles</h5>
                  <p class="mb-3">Comienza agregando tu primer recurso a la biblioteca</p>
                  <a href="<?= base_url('recursos/crear'); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Agregar Primer Recurso
                  </a>
                </div>
              </td>
            </tr>
          <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Vista Previa de Portada -->
<div class="modal fade" id="portadaModal" tabindex="-1" aria-labelledby="portadaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-gradient-primary text-white">
        <h5 class="modal-title" id="portadaModalLabel">
          <i class="bi bi-image me-2"></i>Vista Previa de Portada
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-4">
        <img id="portadaImage" src="" alt="" class="img-fluid rounded shadow-sm mb-3" style="max-height: 400px;">
        <h6 id="portadaTitle" class="text-dark"></h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?= $footer; ?>

<!-- Custom Styles -->
<style>
.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.icon-bg-primary {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.table-row-hover:hover {
  background-color: #f8f9fa;
  transform: translateY(-1px);
  transition: all 0.3s ease;
}

.cursor-pointer {
  cursor: pointer;
}

.portada-preview:hover {
  transform: scale(1.05);
  transition: transform 0.3s ease;
}

.btn {
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-2px);
}

.card {
  border-radius: 15px;
  overflow: hidden;
}

.badge {
  border-radius: 6px;
  font-weight: 500;
}

.table th {
  font-weight: 600;
  letter-spacing: 0.5px;
  border: none;
}

.alert {
  border-radius: 10px;
}

.shadow-lg {
  box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.shadow-sm {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
  .table-responsive {
    font-size: 0.875rem;
  }
  
  .btn-group {
    flex-direction: column;
  }
  
  .btn-group .btn {
    margin-bottom: 2px;
  }
}
</style>
<script>
// Función para mostrar toasts mejorados
const showToast = (message, type = 'info') => {
    const bgColor = {
        'success': 'linear-gradient(135deg, #00b09b, #96c93d)',
        'error': 'linear-gradient(135deg, #ff5f6d, #ffc371)', 
        'warning': 'linear-gradient(135deg, #f093fb, #f5576c)',
        'info': 'linear-gradient(135deg, #4facfe, #00f2fe)'
    };

    Toastify({
        text: message,
        duration: 4000,
        close: true,
        gravity: "top",
        position: "right",
        style: {
            background: bgColor[type] || bgColor['info'],
            borderRadius: "10px",
            fontSize: "14px",
            fontWeight: "500"
        },
        onClick: function(){}
    }).showToast();
};

// Función mejorada para confirmar eliminación
async function confirmarEliminacion(id, titulo) {
    try {
        const result = await Swal.fire({
            title: '¿Eliminar Recurso?',
            html: `
                <div class="text-center">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <p class="mb-2">¿Está seguro de eliminar el recurso:</p>
                    <p class="fw-bold text-primary mb-3">"${titulo}"</p>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small>Esta acción no se puede deshacer y eliminará también todos los archivos asociados.</small>
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-2"></i>Sí, eliminar',
            cancelButtonText: '<i class="bi bi-x me-2"></i>Cancelar',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: 'border-0 shadow-lg',
                confirmButton: 'btn-lg',
                cancelButton: 'btn-lg'
            }
        });

        if (result.isConfirmed) {
            // Mostrar loading mejorado
            Swal.fire({
                title: 'Eliminando Recurso',
                html: `
                    <div class="text-center">
                        <div class="spinner-border text-danger mb-3" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p>Por favor espere mientras se elimina el recurso y sus archivos...</p>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'border-0 shadow-lg'
                }
            });

            // Redirigir para eliminar
            setTimeout(() => {
                window.location.href = `<?= base_url('recursos/eliminar/'); ?>${id}`;
            }, 1000);
        }
    } catch (error) {
        console.error('Error en confirmación:', error);
        showToast('Error inesperado al intentar eliminar', 'error');
    }
}

// Función para recargar tabla con animación
function recargarTabla() {
    showToast('Actualizando lista de recursos...', 'info');
    
    // Añadir clase de loading
    const table = document.getElementById('recursosTable');
    table.style.opacity = '0.5';
    
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Inicialización cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Modal de vista previa de portada
    const portadaModal = document.getElementById('portadaModal');
    portadaModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const imageSrc = button.getAttribute('data-image');
        const title = button.getAttribute('data-title');
        
        const modalImage = portadaModal.querySelector('#portadaImage');
        const modalTitle = portadaModal.querySelector('#portadaTitle');
        
        modalImage.src = imageSrc;
        modalImage.alt = `Portada de ${title}`;
        modalTitle.textContent = title;
    });

    // Animación de entrada para las filas de la tabla
    const tableRows = document.querySelectorAll('.table-row-hover');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 50);
    });

    // Toast de bienvenida
    setTimeout(() => {
        showToast('📚 Lista de recursos cargada correctamente', 'success');
    }, 500);
    
    // Mostrar toasts de mensajes de sesión
    <?php if (session('success')): ?>
        setTimeout(() => {
            showToast('✅ <?= addslashes(session('success')); ?>', 'success');
        }, 1000);
    <?php endif; ?>
    
    <?php if (session('error')): ?>
        setTimeout(() => {
            showToast('❌ <?= addslashes(session('error')); ?>', 'error');
        }, 1000);
    <?php endif; ?>

    // Efecto hover mejorado para botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Función para filtro rápido (búsqueda en tabla)
function filtrarTabla(searchTerm) {
    const table = document.getElementById('recursosTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent.toLowerCase();
            if (cellText.includes(searchTerm.toLowerCase())) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
}
</script>