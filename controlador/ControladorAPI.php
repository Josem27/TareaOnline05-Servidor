<?php 

require_once "modelo/ModeloTareas.php";

class ControladorApi {

    private $response;
    private $modelo;
    private $statusCode;
    private $id = null;

    public function __construct($ruta) { 
        $ruta = substr($ruta,1); 
        $extra = explode('/',$ruta);

        if (isset($extra[1])) { 
            if(is_numeric($extra[1])){ 
                $this->id=$extra[1];
            }
        }

        $function = $extra[0]; 
        
        if (method_exists($this,$function)) {
            $this->modelo = new Modelo();   
            $this->$function();             
        } else {
            $this->statusCode = 404;
            $this->response = "{ 'message': 'Not found' }";
        }

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($this->statusCode);
        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    //Si $function es entradas, realiza la funcion de modelo
    public function listado($force_detalle=false) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' || $force_detalle) {
            $this->response = $this->modelo->entradas();  
            $this->statusCode = 200; 
        } else {
            $this->response = '{ "message": "Operacion no permitida." }';
            $this->statusCode = 405;
        }        
             
    }

    public function detalle() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($this->id) {
                $result = $this->modelo->entradaId($this->id);
                if ($result['bool']) { 
                    // Devolver los datos de la entrada en JSON
                    $this->response = $result['datos'];
                    $this->statusCode = 200;
                } else {
                    $this->response = $result['mensaje'];
                    $this->statusCode = 500;
                } 
            } else {
                $this->response = "{ 'message': 'ID inválido' }";
                $this->statusCode = 404;
            }
        } else {
            $this->response = '{ "message": "Operación no permitida." }';
            $this->statusCode = 405;
        }
    }    
    
    
    //Si la url tiene "/crearEntrada" usa esta funcion, siempre que haya un POST
    public function crearEntrada() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT') {
            if (!empty($_POST)) {
                if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) { 
                    $data = $this->modelo->get_image();
                    $imagen = $data['imagen'];
                    $errores = $data['error'];
                }
                $estado = 0;
                if($_POST['estado'] == 'on'){
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

                    $result = $this->modelo->new_Entrada($datos);
                    
                    if ($result['bool']) {
                        header("Location: /index.php?accion=listado");
                        exit();
                    } else {
                        $this->response = $result['error'];
                        $this->statusCode = 500;
                    }                
                }
            } else {
                $this->listado(true);
            }
        } else {
            $this->response = '{ "message": "Operacion no permitida." }';
            $this->statusCode = 405;
        }       
    }

    //Funcion actualizar pot id
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT') {
            if (!empty($_POST)) {
                if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) { 
                    $data = $this->modelo->get_image();
                    $imagen = $data['imagen'];
                    $errores = $data['error'];
                }
                $estado = 0;
                if($_POST['estado'] == 'on'){
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
                        "id_cat" => $_POST['id_cat'],
                        "id" => $this->id
                        
                    ];

                    $result = $this->modelo->editar($datos);
                    
                    if ($result['bool']) {
                        $this->detalle(true);
                    } else {
                        $this->response = $result['error'];
                        $this->statusCode = 500;
                    }                
                }
            } else {
                $this->detalle(true);
            }
        } else {
            $this->response = '{ "message": "Operacion no permitida." }';
            $this->statusCode = 405;
        }       
    }

    //Funcion eliminar por id
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {            
            $result = $this->modelo->del_Entrada($this->id);
            if ($result['bool']) {
                $this->response = "{ 'message': 'Entrada borrada con exito con id ".$this->id."' }";
                $this->statusCode = 200;
            } else {
                $this->response = $result['error'];
                $this->statusCode = 500;
            }
        } else {
            $this->response = '{ "message": "Operacion no permitida." }';
            $this->statusCode = 405;
        }
    }
}



?>