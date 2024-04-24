<!DOCTYPE html>
<html lang="es">
<head>
    <?php include_once 'includes/header.php'; ?>
    <title>Contenido</title>
    <style>
        .center-content {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>    
    <?php include_once 'includes/menu.php'; ?>
    <?php 
    if(isset($_GET['post'])){
        if($_GET['post']==true){
            echo '<div class="alert alert-success">Post creado correctamente</div>';
        }
    }
    // Verificar si $parametros['datos'] está definida y no es null antes de intentar iterar sobre ella
    if (isset($parametros['datos']) && is_array($parametros['datos'])) {
        foreach ($parametros['datos'] as $datos): ?>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <?= $datos['nombre']?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $datos['titulo'] ?></h5>
                                <p class="card-text"><?= substr($datos['descripcion'], 0, 45) . (strlen($datos['descripcion']) > 45 ? '...' : '') ?></p>
                                <div class="btn-group" role="group">
                                    <a class="btn btn-warning" href="index.php?accion=editar&id=<?= $datos['id_tarea']?>" role="button">Editar</a>
                                    <a class="btn btn-primary" href="index.php?accion=entrada&id=<?= $datos['id_tarea']?>" role="button">Detalles</a>
                                    <a class="btn btn-danger" href="index.php?accion=eliminar&id=<?= $datos['id_tarea'] ?>&borrar=true" role="button">Eliminar</a>
                                </div>
                            </div>
                            <div class="card-footer text-muted">
                                <?= $datos['fecha']?> // <?= $datos['hora']?>
                            </div>
                        </div>
                    </div>
                </div>
            </div><br>
        <?php endforeach; ?>
    <?php } else { ?>
        <!-- Mostrar mensaje si la agenda está vacía -->
        <div class="container center-content">
            <p>La agenda se encuentra vacía.</p>
        </div>
    <?php } ?>

    <?php 
    // Obtener los datos de paginación del modelo
    $modelo = new Modelo();
    $resultModelo = $modelo->get_paginacion();

    // Incluir el archivo paginado.php y pasar los valores necesarios
    include_once 'includes/paginado.php';
    ?>
</body>
</html>
