<?php
include_once "Persona.php";
class Pasajero extends Persona {
    
    private $idviaje;
    private $mensajeoperacion;

    public function __construct() {
        parent::__construct();
        $this->idviaje = "";
    }

    public function cargar($idpersona, $nroD, $nom, $ape, $telefono, $idviaje = null) {
        parent::cargar($idpersona, $nroD, $nom, $ape, $telefono);
        if ($idviaje !== null) {
           
            $this->setIdViaje($idviaje);
        }
    }

    public function setIdViaje($idviaje) {
        $this->idviaje = $idviaje;
    } 

    public function setMensajeOperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getIdViaje() {
        return $this->idviaje;
    }

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }

    /**
     * Recupera los datos de una persona por DNI
     * @param int $dni
     * @return true en caso de encontrar los datos, false en caso contrario 
     */
    public function Buscar($idPersona) {
        $base = new BaseDatos();
        $consultaPersona = "SELECT * FROM pasajero WHERE idpersona=" . $idPersona;
        $resp = false;
        if (parent::buscar($idPersona)){
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaPersona)) {
                    if ($row2 = $base->Registro()) {
                        parent::Buscar($idPersona);
                        $this->setIdViaje($row2['idviaje']);
                        $resp = true;
                    }                
                } else {
                    $this->setMensajeOperacion($base->getError());
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }   
      }     
        return $resp;
    }

    public function listar($condicion = "") {
        $arregloPasajeros = null;
        $base = new BaseDatos();
        $consultaPasajeros = "Select * from pasajero";
        if ($condicion != "") {
            $consultaPasajeros .= ' where ' . $condicion;
        }
        $consultaPasajeros .= " order by idpasajero";
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajeros)) {
                $arregloPasajeros = array();
                while ($row2 = $base->Registro()) {
                    $idviaje = $row2['idviaje'];
                    $idpersona = $row2['idpersona']; // Suponiendo que nrodoc es parte de la tabla pasajero

                    // Crear una nueva instancia de Pasajero
                    $pasajero = new Pasajero();

                    // Usar el método Buscar para llenar los detalles desde la clase Persona
                    if ($pasajero->Buscar($idpersona)) {
                      
                        array_push($arregloPasajeros, $pasajero);
                    } else {
                        $this->setMensajeOperacion($pasajero->getMensajeOperacion());
                    }
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloPasajeros;
    }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;

        if (parent::insertar()){
            $idPersona = parent::getIdPersona();
            $idViaje = $this->getIdViaje();

            $consultaInsertar = "INSERT INTO pasajero(idpersona, idviaje) 
                VALUES ($idPersona,$idViaje)";
        
            if ($base->Iniciar()) {
                // if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                // $this->setIdPasajero($id);
                if ($base->Ejecutar($consultaInsertar)) {
                    $resp = true;

            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        }else{
            $this->setMensajeOperacion(parent::getmensajeoperacion());
        }
        return $resp;
    }

    public function modificar() {
        $resp = false; 
        $base = new BaseDatos();
        if (parent::modificar()) {
            $consultaModifica = "UPDATE pasajero SET idviaje='" . $this->getIdViaje() . "' WHERE idpersona=" . $this->getIdPersona();
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaModifica)) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion($base->getError());
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM pasajero WHERE idpersona=" . parent::getIdPersona();
            if ($base->Ejecutar($consultaBorra)) {
                if (parent::eliminar()) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion($base->getError());
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function __toString() {
        return parent::__toString() .
            "\nId Viaje: " . $this->getIdViaje();
    }
}
?>