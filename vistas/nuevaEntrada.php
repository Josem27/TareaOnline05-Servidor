<!DOCTYPE html>
<html lang="es">

<head>
    <?php include_once 'includes/header.php' ?>
</head>

<body>
    <div class="container cuerpo text-center">
        <p>
        <h2>Nueva Tarea<h2></h2>
            </p>
            <hr width="50%" color="black">
    </div>
    <div class="container cuerpo text-center">
        <a class="btn btn-primary" href="index.php?accion=listado" role="button">Volver</a>
        <hr width="50%" color="black">
    </div>
    <script>
        CKEDITOR.replace('descripcion');
    </script>
    <div class="container text-center">
            <form action="/index.php/api/crearEntrada" method="POST" enctype="multipart/form-data">
            <label for="titulo" class="form-label">Titulo</label>
            <input type="text" class="form-control" name="titulo" id="titulo"><br>
            <label for="horafecha">Fecha y Hora:</label>
            <input type="date" name="fecha" id="fecha"><t>
            <input type="time" name="hora" id="hora"><br><br>
            <label for="lugar">Lugar:</label>
            <input type="text" name="lugar" id="lugar"class="form-control"><br>
            <label for="prioridad">Prioridad: </label>
            <select name="prioridad" id="prioridad" class="form-control">
                <option value="alta">Prioritaria</option>
                <option value="alta">Alta</option>
                <option value="media" selected="selected">Media</option>
                <option value="baja">Baja</option>
            </select><br>
            <label for="estado">Estado:</label><br>
            <input type="checkbox" name="estado" id="completa"> Completa
            <input type="checkbox" name="estado" id="pendiente"> Pendiente
            <input type="checkbox" name="estado" id="cancelada"> Cancelada<br><br>


            <label for="imagen">Imagen <input type="file" name="imagen" class="form-control-file"></label><br><br>

            <label for="categoria">Categoria</label>
            <select name="id_cat" class="form-control" id="id_cat">
                <?php foreach ($idCat['datos'] as $key) {
                    echo "<option value=".$key['id_cat'].">".$key['nombre']."</option>";
                }?>
            </select><br>

            <div>
                <label for="descripcion">Descripción</label>
                <textarea class="ckeditor form-control" id="descripcion" name="descripcion" required></textarea>
            </div></br>

            <button type="submit" name="submit" class="btn btn-primary">Registrar tarea</button><br>

        </form>
    </div>
</body>

</html>