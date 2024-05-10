<div class="container mt-4 mb-5">
    <!-- Agregar un botón original para abrir un nuevo modal -->
    <div class="text-center">
        <button type="button" class="btn btn-primary" id='button-ModalAnadir' data-bs-toggle="modal"
            data-bs-target="#ModalAnadir">
            ¡Nueva Alta de Pelicula!
        </button>
    </div>

    <!-- Tabla/Listado de peliculas -->
    <div class="table-responsive">
        <table class="table caption-top table-dark table-striped table-bordered align-middle tabla-con-el-contenido"
            id="tabla">
            <caption class="text-light">Datos de Peliculas</caption>
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Modificar</th>
                    <th scope="col">Eliminar</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php foreach ($SQL_Peliculas as $SQL_Pelicula) : ?>
                <tr id="columna-con-id-<?=$SQL_Pelicula->id;?>">
                    <td scope="row fs-1"><?=$SQL_Pelicula->id;?></td>
                    <td scope="row"><?=$SQL_Pelicula->nom;?></td>
                    <td scope="row"><?=$SQL_Pelicula->direccion;?></td>
                    <td scope="row">
                        <span class="d-inline-block text-truncate" style="max-width: 250px;">
                            <?=$SQL_Pelicula->descripcion;?>
                        </span>
                    </td>
                    <td scope="row">
                        <div class="d-grid gap-1">
                            <button class="btn btn-outline-warning boton-modificar-pelicula" type="button"
                                data-id="<?=$SQL_Pelicula->id;?>" data-nom="<?=$SQL_Pelicula->nom;?>"><i
                                    class="bi bi-sliders">
                                    Modificar</i></button>
                        </div>
                    </td>
                    <td scope="row">
                        <div class="d-grid gap-1">
                            <button class="btn btn-outline-danger eliminar" type="button"
                                data-id="<?=$SQL_Pelicula->id;?>" data-nom="<?=$SQL_Pelicula->nom;?>"><i
                                    class="bi bi-trash3-fill">
                                    Eliminar</i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Nueva Pelicula -->
    <?php $this->load->view('Modales/Anadir'); ?>

    <!-- Modal Eliminar Pelicula -->
    <?php $this->load->view('Modales/Eliminar'); ?>


    <!-- Modal Realizado -->
    <?php $this->load->view('Modales/Realizado'); ?>


    <!-- Modificar Pelicula -->
    <?php $this->load->view('Modales/Modificar'); ?>


</div>


<script>
$(function() {
    // Capturar clic en el botón "Eliminar" de la tabla
    $('table.tabla-con-el-contenido').on('click', '.eliminar', function() {
        let datasForDelete = {
            id: $(this).data('id'),
            name: $(this).data('nom'),
        };
        // URL de la petición AJAX para borrar la película
        let urlPeticionAJAX = '<?=base_url('PeliculaController/eliminar/');?>' + datasForDelete.id;

        eliminar_mediante_AJAX(datasForDelete, urlPeticionAJAX, toggleModal);
    })

    // Capturar clic en el botón "Modificar" de la tabla
    $('table.tabla-con-el-contenido').on('click', '.boton-modificar-pelicula', function() {
        let datasAndCampos = {
            dataID: $(this).data('id'),
            campoID: $('#idModificar'),
            campoNom: $('#nombreModificar'),
            campoDireccion: $('#direccionModificar'),
            campoDescripcion: $('#descripcionModificar'),
            selectValue: '.modal-modificar-select',
        }

        let urlAJAX = {
            urlPeticionAJAX: '<?=base_url('PeliculaController/getPeliculaById_and_Categoria/');?>'
        + datasAndCampos.dataID,
            urlEnvioAJAX: '<?= base_url('PeliculaController/modificarPelicula/') ?>',
        }

        modificar_mediante_AJAX(datasAndCampos, urlAJAX, toggleModal);
    });

    // Modificar Pelicula dentro del Modal
    // $('#formModificar').submit(function(event) {
    //     // Obtener los datos del formulario
    //     let idPeliculaModificar = $('#idModificar').val();
    //     let nombrePeliculaModificar = $('#nombreModificar').val();
    //     let direccionPeliculaModificar = $('#direccionModificar').val();
    //     let descripcionPeliculaModificar = $('#descripcionModificar').val();
    //     let categoria_idPeliculaModificar = $('.modal-modificar-select').val();
    //     $('#idModificar').val('');
    //     $('#nombreModificar').val('');
    //     $('#direccionModificar').val('');
    //     $('#descripcionModificar').val('');
    //     $('.modal-modificar-select').val('0');

    //     // Enviar los datos por AJAX al controlador
    //     $.ajax({
    //         url: '<?= base_url('PeliculaController/modificarPelicula/') ?>',
    //         type: 'POST',
    //         data: {
    //             id: idPeliculaModificar,
    //             nombre: nombrePeliculaModificar,
    //             direccion: direccionPeliculaModificar,
    //             descripcion: descripcionPeliculaModificar,
    //             categoria_id: categoria_idPeliculaModificar
    //         },
    //         success: function(response) {
    //             // Actualizar la fila modificada en la tabla
    //             $('#columna-con-id-' + idPeliculaModificar).find('td:eq(1)').text(
    //                 nombrePeliculaModificar);
    //             $('#columna-con-id-' + idPeliculaModificar).find('td:eq(2)').text(
    //                 direccionPeliculaModificar);
    //             // Actualizar el texto dentro del span de descripción
    //             $('#columna-con-id-' + idPeliculaModificar).find('td:eq(3)').find('span')
    //                 .text(descripcionPeliculaModificar);

    //             // Cerrar el modal
    //             $('#ModalModificar').modal('hide');

    //         }
    //     });
    // });

    // Añadir peliculas
    $('#formAñadir').submit(function(e) {
        e.preventDefault();
        let nombre = $('#nombreAñadir').val();
        let direccion = $('#direccionAñadir').val();
        let descripcion = $('#descripcionAñadir').val();
        let categoria_id = $('.modal-anadir-select').val();
        $('#nombreAñadir').val('');
        $('#direccionAñadir').val('');
        $('#descripcionAñadir').val('');
        $('.modal-anadir-select').val('0');

        $.ajax({
            url: '<?= base_url('PeliculaController/addPelicula/') ?>',
            type: 'POST',
            data: {
                nom: nombre,
                direccion: direccion,
                descripcion: descripcion,
                categoria: categoria_id
            },
            success: function(response) {
                $('#ModalAnadir').modal('hide');
                location.reload();
            }
        });
    })
})
</script>