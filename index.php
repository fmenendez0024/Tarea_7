<?php
// conectamos a nuestra base de datos
$host = "localhost";
$usuario = "root";
$password = "Froilan1988";
$basededatos = "api";


$conexion = new mysqli($host,$usuario,$password,$basededatos);

if ($conexion->connect_error) {
    die ("Conexion no establecida". $conexion->connect_error);

}

header("Content-Type: application/json");

$metodo = $_SERVER ['REQUEST_METHOD'];

$path = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';

$buscarId = explode('/',$path);

$id = ($path!=='/') ? end($buscarId):null;

// print_r($metodo);

switch ($metodo){
// metodos para las consultas
    // SELECT
    case 'GET':
        // echo "Consulta de registros - GET";
        ConsultaSelect($conexion);
        break;
    case 'GET':
            // echo "Consulta de registros por ID - GET";
        ConsultaID($conexion, $id);
        break;
    // INSERT
    case 'POST':
        // echo "Insertar registros - POST";
        insertarPOST ($conexion);
        break;
    //UPDATE
    case 'PUT':
        // echo "Actualizar registros - PUT";
        actualizarPUT($conexion, $id);
        break;
    //DELETE
    case 'DELETE':
        // echo "Borrar de registros - DELETE";
        borrarDELETE($conexion, $id);
        break;
    default:
        echo "Metodo no permitido";
        break;

}

function ConsultaSelect ($conexion){
    $sql = "SELECT * FROM cliente";
    $resultado = $conexion->query($sql);

    if ($resultado){
        $datos = array ();
        while ($fila = $resultado->fetch_assoc()){
            $datos[] = $fila;

        }
        echo json_encode($datos);

    }

}
function ConsultaID ($conexion, $id){
    $sql = ($id === null) ? "SELECT * FROM cliente": "SELECT * FROM cliente WHERE id = $id";
    $resultado = $conexion->query($sql);

    if ($resultado){
        $datos = array ();
        while ($fila = $resultado->fetch_assoc()){
            $datos[] = $fila;
        }
        echo json_encode($datos);

    }

}
function insertarPOST($conexion){
    $dato = json_decode(file_get_contents('php://input'),true);
    $nombres = $dato['nombres'];
    $apellidos = $dato['apellidos'];
    $correo_electronico = $dato['correo_electronico'];
    // print_r($nombres);
    $sql = "INSERT INTO cliente (nombres, apellidos, correo_electronico) VALUES ('$nombres', '$apellidos', '$correo_electronico')";
    $resultado = $conexion->query($sql);

    if ($resultado) {
        $dato ['id'] = $conexion->insert_id;
        echo json_encode($dato);
    }else {
        echo json_encode(array('error' => 'Error al crear usuario'));
    }
}
function borrarDELETE ($conexion, $id) {
    echo "El id a borrar es: ". $id;

    $sql = "DELETE FROM cliente WHERE id = $id";
    $resultado = $conexion->query($sql);

    if ($resultado) {
        echo json_encode(array('mensaje' => 'usuario eliminado'));
    }else {
        echo json_encode(array('error' => 'Error al eliminar usuario'));
    }
}
function actualizarPUT ($conexion, $id) {

    $dato = json_decode(file_get_contents('php://input'),true);
    $nombres = $dato['nombres'];
    $apellidos = $dato['apellidos'];
    $correo_electronico = $dato['correo_electronico'];

    echo "El id a editar es: ". $id. " Con el dato ". $nombres ,$apellidos ,$correo_electronico;

    $sql = "UPDATE cliente SET nombres = '$nombres', apellidos = '$apellidos', correo_electronico = '$correo_electronico' WHERE id = $id";
    $resultado = $conexion->query($sql);

    if ($resultado) {
        echo json_encode(array('mensaje' => 'usuario actualizado'));
    }else {
        echo json_encode(array('error' => 'Error al actualizar usuario'));
    }
}

?>