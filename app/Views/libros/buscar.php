<?= $header; ?>

<div class="container mt-2">
  <div class="my-2">
    <h4>Buscador de libros</h4>
    <a href="<?= base_url("libros"); ?>">Volver a libros</a>
  </div>
</div>

<form action="" autocomplete="off">
  <div>
    <label for="id">Ingrese el ID del libro</label>
    <div>
      <input type="text" class="form-control" name="id" id="id">
    </div>
    <div class="my-2">
      <button type="button" id="buscar" class="btn btn-primary">Buscar</button>
    </div>
    <div>
      <label for="nombre">Nombre del libro</label>
      <input type="text" class="form-control" name="nombre" id="nombre" readonly>
    </div>
    <div class="mt-2">
      <img src="" alt="Portada del libro" id="portada" style="max-width: 200px;">
    </div>
  </div>
</form>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const id = document.querySelector("#id");
    const nombre = document.querySelector("#nombre");
    const portada = document.querySelector("#portada");
    const buscar = document.querySelector("#buscar");

    buscar.addEventListener("click", async () => {
      if (!id.value) {
        alert("Escriba el ID del libro");
        return;
      }

      try {
        const response = await fetch("http://biblioteca.test/public/api/buscarlibro", {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id.value })
        });

        if (!response.ok) {
          throw new Error('Error en la comunicación con el servidor');
        }

        const data = await response.json();

        if (data.success) {
          nombre.value = data.nombre;
          portada.setAttribute('src', `http://biblioteca.test/uploads/${data.imagen}`);
        } else {
          nombre.value = "";
          portada.setAttribute('src', "");
          console.error(data.message);
        }

      } catch (error) {
        console.error('Error:', error);
      }
    });
  });
</script>

<?= $footer; ?>
