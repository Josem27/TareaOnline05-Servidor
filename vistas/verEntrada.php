<!DOCTYPE html>
<html lang="es">

<head>
    <?php include_once 'includes/header.php' ?>
</head>

<body>
    <div class="container cuerpo text-center">
    <?php foreach ($parametros['datos'] as $datos): ?>
        <p>
        <h2>Tarea: <?= $datos['titulo']; ?><h2></h2>
            </p>
            <hr width="50%" color="black">
    </div>
    <div class="container cuerpo text-center">
        <a class="btn btn-primary" href="index.php?accion=listado" role="button">Volver</a>
        <hr width="50%" color="black">
    </div>
    <div class="container text-center">
           
            <label for="fechahora"><b>Fecha y Hora:</b> <?=$datos['fecha'];?> <?= $datos['hora'];?></label><br>


            <label for="imagen"><b>Imagen</b></label><br> 
            <img src="images/<?=$datos['imagen'];?>" width="260"><br>

            <label for="categoria"><b>Categoria:</b></label><br>
            <label for="categoria"><?= $datos['nombre']?></label><br>

            <label for="prioridad"><b>Prioridad:</b></label><br>
            <label for="prioridad"><?=$datos['prioridad'];?></label><br>

            <label for="estado"><b>Estado:</b></label>
            <label for="estado"><?php if($datos['estado']==1)echo"Completado"; else echo"No completado";?></label>


            <div>
                <label for="descripcion"><b>Descripción:</b></label><br>
                <label for=""><?= $datos['descripcion']?></label><br>

            
    </div>
    <?php endforeach; ?>
</body>

</html>