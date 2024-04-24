<?php
require_once "modelo/ModeloTareas.php";

class Controlador
{

    private $modelo;
    private $mensajes;

    public function __construct()
    {
        $this->modelo = new Modelo();
        $this->mensajes = [];
    }

    public function index()
    {
        $parametros = [
            "titulo" => "MVC"
        ];

        header("Location: index.php?accion=listado");
    }

    public function listado()
    {
        $parametros = [
            "titulo" => "Listado",
            "datos" => null,
            "mensaje" => null
        ];


        $resultModelo = $this->modelo->entradas();
        if ($resultModelo) {
            $parametros['datos'] = $resultModelo;
        }
        include_once 'vistas/listado.php';
    }

    public function nuevaEntrada()
    {
        $parametros = [
            "titulo" => "Nueva Tarea"
        ];

        $errores = array();
        $imagen = null;
        if (isset($_POST['submit']) && !empty($_POST)) {
            if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) {
                $data = $this->modelo->get_image();
                $imagen = $data['imagen'];
                $errores = $data['error'];
            }
            $estado = 0;
            if ($_POST['estado'] == 'on') {
                $estado = 1;
            }
            if (!isset($errores)) {
                $datos = [
                    "fecha" => $_POST['fecha'],
                    "hora" => $_POST['hora'],
                    "titulo" => $_POST['titulo'],
                    "imagen" => $imagen,
                    "descripcion" => $_POST['descripcion'],
                    "prioridad" => $_POST['prioridad'],
                    "lugar" => $_POST['lugar'],
                    "estado" => $estado,
                    "id_cat" => $_POST['id_cat']

                ];
                $resultModelo = $this->modelo->new_Entrada($datos);
            }

            if ($resultModelo['bool']) {
                header("Location: index.php?accion=listado&post=true");
            }
        } else {
            $resultModelo = $this->modelo->idCat();
            $idCat['datos'] = $resultModelo['datos'];
            include_once 'vistas/nuevaEntrada.php';
        }
    }

    public function entrada()
    {
        $parametros = [
            "titulo" => "Tarea",
            "datos" => null,
            "mensaje" => null
        ];

        $datos = $_GET['id'];
        $resultModelo = $this->modelo->entradaId($datos);
        if ($resultModelo['bool']) {
            $parametros['datos'] = $resultModelo['datos'];
            include_once 'vistas/verEntrada.php';
        }
    }

    public function eliminar()
    {
        $resultModelo = $this->modelo->del_Entrada($_GET['id']);
        header("Location: index.php?accion=listado");
    }

    public function editar()
    {
        $parametros = [
            "titulo" => "Editar",
            "datos" => null,
            "mensaje" => null
        ];
        $errores = array();
        $imagen = null;
        if (isset($_POST['submit']) && !empty($_POST)) {
            if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) {
                $data = $this->modelo->get_image();
                $imagen = $data['imagen'];
                $errores = $data['error'];
            }
            $estado = 0;
            if (isset($_POST['estado']) && $_POST['estado'] == 'on') {
                $estado = 1;
            }
           
                $datos = [
                    "fecha" => $_POST['fecha'],
                    "hora" => $_POST['hora'],
                    "titulo" => $_POST['titulo'],
                    "imagen" => $imagen,
                    "descripcion" => $_POST['descripcion'],
                    "prioridad" => $_POST['prioridad'],
                    "lugar" => $_POST['lugar'],
                    "estado" => $estado,
                    "id_cat" => $_POST['id_cat'],
                    "id" => $_GET['id']
                ];
                $resultModelo = $this->modelo->editar($datos);
            
            
            if ($resultModelo['bool']) {
                header("Location: index.php?accion=listado");
            } else {
                echo '<div class="alert alert-danger">error' . $resultModelo['error'] . '</div>';
            }
        }



        $datos = $_GET['id'];
        $resultModelo = $this->modelo->entradaId($datos);
        if ($resultModelo['bool']) {
            $parametros['datos'] = $resultModelo['datos'];
            $resultModelo2 = $this->modelo->idCat();
            $idCat['datos'] = $resultModelo2['datos'];
            include_once 'vistas/editarEntrada.php';
        }
    }

    public function generarPDF()
    {
        // Obtener todo el listado de entradas
        $resultModelo = $this->modelo->entradas();
    
        // Incluir los archivos de TCPDF localmente
        require_once 'tcpdf/tcpdf.php';
    
        // Crear una instancia de TCPDF
        $pdf = new TCPDF();
    
        // Establecer la ubicación de las fuentes de TCPDF
        $fontPath = 'ruta/a/la/carpeta/fonts/';
        TCPDF_FONTS::addTTFfont($fontPath . 'arial.ttf', 'TrueTypeUnicode', '', 32);
    
        // Agregar contenido al PDF (ajusta según tus necesidades)
        $pdf->AddPage();
        $pdf->SetFont('times', 'B', 16);
    
        foreach ($resultModelo as $entrada) {
            $pdf->Cell(0, 10, 'Detalles de Entrada', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Título: ' . $entrada['titulo'], 0, 1);
            $pdf->Cell(0, 10, 'Descripción: ' . $entrada['descripcion'], 0, 1);
            $pdf->Cell(0, 10, 'Prioridad: ' . $entrada['prioridad'], 0, 1);
            $pdf->Cell(0, 10, 'Lugar: ' . $entrada['lugar'], 0, 1);
            $pdf->Cell(0, 10, 'Estado: ' . ($entrada['estado'] == 1 ? 'Completada' : 'Pendiente'), 0, 1);
            $pdf->Cell(0, 10, 'Categoría: ' . $entrada['nombre'], 0, 1); // Utiliza 'nombre' para el nombre de la categoría
            $pdf->Cell(0, 10, 'Fecha de Creación: ' . $entrada['fecha'], 0, 1);
    
            // Agregar la imagen al PDF
            if ($entrada['imagen'] != null) {
                $imagePath = 'fotos/' . $entrada['imagen']; // Ajusta la ruta de la imagen según sea necesario
                $pdf->Image($imagePath, 10, 40, 90, 0, '', '', '', false, 300, '', false, false, 0);
            }
    
            // Agregar un salto de página después de cada entrada
            $pdf->AddPage();
        }
    
        // Salida del PDF
        $pdf->Output('Listado_de_Entradas.pdf', 'I');
        exit();
    }
    
    
}
