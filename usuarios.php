<?php
// Forma de salir de dos carpetas para encontrar el archivo Conexion
require_once('../../Conexion.php');
//Define el inicio de la sesion
session_start();

class Usuarios extends Conexion {
    public function __construct() {
        //La variable $db almacena el llamado al CONSTRUCTOR de la clase Padre (conexion)
        $this->db = parent::__construct();
    }

    public function login($Usuario, $Password) {

        $statement = $this->db->prepare("SELECT * FROM usuarios WHERE USUARIO = :Usuario AND PASSWORD = :Password");
        //Asignacion de valores de sentencia SQL y parametros recibidos por el metodo
        $statement->bindParam(':Usuario', $Usuario);
        $statement->bindParam(':Password', $Password);
        $statement->execute();

        //rowCount devuelve el numero de filas afectadas por la sentencia SQL
        if ($statement->rowCount() == 1) {
            //result es un arreglo
            //fetch obtiene la fila de un conjunto de resultados
            $result = $statement->fetch();
            $_SESSION['NOMBRE'] = $result['NOMBRE'] . " " . $result['APELLIDO'];
            $_SESSION['ID'] = $result['ID_USUARIO'];
            $_SESSION['PERFIL'] = $result['PERFIL'];
            $_SESSION['validacion'] = true;
            $_SESSION['start'] = time();
            $_SESSION['expire']= $_SESSION['start']+(1*60);
            return true;
        }
        return false;
    }
    public function validarsesion()
    {
       if($_SESSION['id_usuario']){
        if(!isset ($_SESSION['start'])){
            $_SESSION['start'] = time();
        }

        }else if(time() - $_SESSION['start']> 60){
            session_destroy();
            echo "<script>alert('Cierre de sesion por inactividad');window.location='../../index.php';</script>";
            $_SESSION["validar"]==false;
        }
        $_SESSION['start'] = time();
    }

    //Metodos que retornan el valor almacenado en las instrucciones Fletch (25,26,27)
    public function getNombre() {
        return $_SESSION['NOMBRE'];
    }

    public function getId() {
        return $_SESSION['ID'];
    }

    public function getPerfil() {
        return $_SESSION['PERFIL'];
    }

    //Validacion de sesion
    public function validateSession() {
        if ($_SESSION['ID'] == null) {
            print "<script>alert(\"El usuario o la contraseña son incorrectos.\");
            window.location='../../index.php';</script>";
        }
    }

    //Validar de sesion de administrador
    public function validateSessionAdministrator() {
        if ($_SESSION['ID'] != null) {
            //Consultar perfil de la sesion
            if ($_SESSION['PERFIL'] == 'Administrador') {
               header('Location: ../../administrador/Paginas/index.php');
            } else if ($_SESSION['PERFIL'] == 'Docente') {
                header('Location: ../../docente/paginas/index.php');
            }
        } else {
            print "<script>alert(\"El usuario o la contraseña son incorrectos.\");
            window.location='../../index.php';</script>";
        }
    }

    public function salir() {
        $_SESSION['ID'] = null;
        $_SESSION['NOMBRE'] = null;
        $_SESSION['PERFIL'] = null;
        session_destroy();
        header('Location: ../../index.php');
    }

}
?>
