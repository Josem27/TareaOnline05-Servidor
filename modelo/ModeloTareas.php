<?php

class Modelo {

    private $conexion;
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "bd_todo_list"; //Nombre de la base de datos

    private $longitudPag = 3; //Para Paginacion, si hiciera falta en el listado
    private $page = 0;

    public function __construct()
    {
        $this->conectar();
        if (isset($_GET['page']) && is_numeric($_GET['page'])) { //Paginacion
            $this->page = $_GET['page'];
        }
    }


    public function get_paginacion(){
        $listCount = $this->conexion->query("SELECT COUNT(*) FROM tareas")->fetch()[0];
        if ($this->page > ($listCount / $this->longitudPag)) {            
            header("Location: /index.php?accion=listado");            
        }
        $result = [
            'paginas' => $listCount,
            'longitudPag' => $this->longitudPag,
            'offset' => 3
        ];
        return $result;
    }

    //Funcion para realizar la conexion a la base de datos
    public function conectar()
    {
        try {
            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexion;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    //Funcion para listar todos los elementos de la tabla
    public function entradas()
    {
        $offset = $this->page * $this->longitudPag; // LIMIT $longitudPag OFFSET $offset
        $sql = $this->conexion->prepare("SELECT DISTINCT tareas.id_tarea, tareas.fecha, tareas.hora, tareas.titulo, tareas.imagen, tareas.descripcion, tareas.prioridad, tareas.lugar, tareas.estado, tareas.id_cat, categorias.nombre AS nombre FROM tareas LEFT JOIN categorias ON tareas.id_cat = categorias.cat_id ORDER BY fecha ASC LIMIT $this->longitudPag OFFSET $offset"); //Sentencia para sacar TODO de la tabla (SELECT * FROM x)
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    

    //Funcion para mostrar los elementos 
    public function entradaFecha($fecha)
    {
        $result = [
            'datos' => null,
            'mensaje' => null,
            'bool' => false
        ];

        try {
            //Sentencia de referencia
            //(entradas LEFT JOIN usuarios ON usuarios.id_user=entradas.user_id) LEFT JOIN categorias ON categorias.id_cat=entradas.cat_id WHERE entradas.id_ent = $id"
            $sql = "SELECT * FROM tareas LEFT JOIN categorias ON tareas.id_cat = categorias.cat_id WHERE fecha = $fecha";

            $resultquery = $this->conexion->query($sql);

            if ($resultquery) {
                $result['bool'] = true;
                $result['datos'] = $resultquery->fetchall(PDO::FETCH_ASSOC);
            }

        } catch (PDOException $e) {
            $result['mensaje'] = $e->getMessage();
        }
        return $result;
    }

    public function entradaId($id)
    {
        $result = [
            'datos' => null,
            'mensaje' => null,
            'bool' => false
        ];

        try {
            //Sentencia de referencia
            //(entradas LEFT JOIN usuarios ON usuarios.id_user=entradas.user_id) LEFT JOIN categorias ON categorias.id_cat=entradas.cat_id WHERE entradas.id_ent = $id"
            $sql = "SELECT * FROM tareas LEFT JOIN categorias ON tareas.id_cat = categorias.cat_id WHERE id_tarea = $id";

            $resultquery = $this->conexion->query($sql);

            if ($resultquery) {
                $result['bool'] = true;
                $result['datos'] = $resultquery->fetchall(PDO::FETCH_ASSOC);
            }

        } catch (PDOException $e) {
            $result['mensaje'] = $e->getMessage();
        }
        return $result;
    }

    //Funcion para crear una nueva entrada en la tabla
    public function new_Entrada($datos)
    {
        $result = [
            "bool" => false
        ];

        try {
            //Sentencia SQL
            $sql = "INSERT INTO tareas(fecha,hora,titulo,imagen,descripcion,prioridad,lugar,estado,id_cat) VALUES (:fecha,:hora,:titulo,:imagen,:descripcion,:prioridad,:lugar,:estado,:id_cat)";

            $query = $this->conexion->prepare($sql);

            $query->execute([                
                'fecha' => $datos['fecha'],
                'hora' => $datos['hora'],
                'titulo' => $datos['titulo'],
                'imagen' => $datos['imagen'],
                'descripcion' => $datos['descripcion'],
                'prioridad' => $datos['prioridad'],
                'lugar' => $datos['lugar'],
                'estado' => $datos['estado'],
                'id_cat' => $datos['id_cat']
            ]);

            if ($query->rowCount() > 0) {
                $result['bool'] = true;
                $result['error'] = 0;
            } else {
                $result['error'] = "No se insertó ninguna fila";
            }

        } catch (PDOException $e) {
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //Funcion para eliminar una entrada
    public function del_Entrada($id)
    {
        $result = [
            'bool' => false,
            'error' => null
        ];
        try {
            $sql = "DELETE FROM tareas WHERE id_tarea = $id";

            $resultquery = $this->conexion->query($sql);

            if ($resultquery) {
                $result['bool'] = true;
            }

        } catch (PDOException $e) {
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //Funcion para editar una entrada con id
    public function editar($datos)
    {
        $result = [
            "bool" => false,
            "error" => null
        ];
        try {
            //Sentencia SQL            
            $sql = "UPDATE tareas SET fecha= :fecha, hora= :hora, titulo= :titulo,imagen=:imagen,descripcion=:descripcion,prioridad=:prioridad,lugar=:lugar,estado=:estado,id_cat=:id_cat WHERE id_tarea= :id";
    
            $query = $this->conexion->prepare($sql);
    
            $query->execute([               
                'fecha' => $datos['fecha'],
                'hora' => $datos['hora'],
                'titulo' => $datos['titulo'],
                'imagen' => $datos['imagen'],
                'descripcion' => $datos['descripcion'],
                'prioridad' => $datos['prioridad'],
                'lugar' => $datos['lugar'],
                'estado' => $datos['estado'],
                'id_cat' => $datos['id_cat'],
                'id' => $datos['id']
            ]);   
    
            // Verificar si la actualización se realizó correctamente
            if ($query->rowCount() > 0) {
                $result['bool'] = true;
                $result['error'] = null;
            } else {
                $result['error'] = "No se encontró ninguna fila para actualizar.";
            }
        } catch (PDOException $e) {
            $result['error'] = $e->getMessage();
        }
        return $result;
    }
    

    //Funcion para gestionar la imagen subida
    public function get_image() {
        $result = [
            "error" => null,
            "imagen" => null,
        ];
        if (!is_dir("images")) {
            $dir = mkdir("images", 0777, true);
        } else {
            $dir = true;
        }

        if ($dir) {
            $nombreFichImg = time() . "-" . $_FILES["imagen"]["name"];
            $movFichImg = move_uploaded_file($_FILES["imagen"]["tmp_name"], "images/" . $nombreFichImg);            
            if (!$movFichImg) {
                $result["error"] = "Error, imagen no cargada";
            }
            $result['imagen'] = $nombreFichImg;        
        }

        return $result;
    }

    public function idCat()
    {
        $result = [
            "datos" => null,
            "mensaje" => null,
            "bool" => false,
        ];

        try {
            $sql = "SELECT * FROM categorias";
            $resultquery = $this->conexion->query($sql);
            if ($resultquery) {
                $result['bool'] = true;
                $result['datos'] = $resultquery->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $result['mensaje'] = $e->getMessage();
        }
        return $result;
    }

    

}

?>