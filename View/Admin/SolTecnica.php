<?php
include("../../Data/conexionDB.php");
class SolTecnica extends conexionDB{

    
    public function getAllSolicitudTec(){
        $result = $this->connect();
        if ($result){
            $dataset = $this->execquery("Select * from solicitudes_tec");
        }
        else{
            echo "No conecto";
            $dataset = "error";
        }
        return $dataset;
    }

    public function getAllSolicitudTecAsJson(){
        $result = $this->connect();
        if ($result){
            $dataset = $this->execquery("Select * from solicitudes_tec");
            $data = array(); 
            while ($row = $dataset->fetch_assoc()) { 
                $data[] = $row; 
            }
            $json_data = json_encode($data, JSON_PRETTY_PRINT); // el JSON_PRETTY_PRINT es opcional y solo se usa para dar formato al JSON
        } else {
            echo "No conecto";
            $json_data = "error";
        }
        return $json_data;
    }
    
    public function getNombreCompletoUsuario($user_id){
        $query = "SELECT * FROM usuarios WHERE user_id = $user_id";
        $result = $this->connect();
        if ($result) {
            $dataset = $this->execquery($query);
            $tupla  = mysqli_fetch_assoc($dataset);
            $nombre = $tupla['first_name'];
            $apellido =$tupla['last_name'];
            $nombreCompleto  = $nombre." ".$apellido;
        }
        else{
            $nombreCompleto = "error";
        }
        return $nombreCompleto;
    }

    public function getNombreCompletoUsuarioAsJson($user_id){
        $query = "SELECT * FROM usuarios WHERE user_id = $user_id";
        $result = $this->connect();
        if ($result) {
            $dataset = $this->execquery($query);
            $tupla  = mysqli_fetch_assoc($dataset);
            $nombre = $tupla['first_name'];
            $apellido =$tupla['last_name'];
            $nombreCompleto  = $nombre." ".$apellido;
            $data = array(); 
            $data[] = $nombreCompleto;
            $json_data = json_encode($data, JSON_PRETTY_PRINT); // el JSON_PRETTY_PRINT es opcional y solo se usa para dar formato al JSON
        }
        else{
            $nombreCompleto = "error";
            $data = array();
            $data[] = $nombreCompleto;
            $json_data = json_encode($data, JSON_PRETTY_PRINT);
        }
        return $json_data;
    }
}
?>