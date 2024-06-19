<?php
include_once "Persona.php";

class Pasajero extends Persona {
    private $idpasajero;
    private $idviaje;
    private $mensajeoperacion;

    public function __construct() {
        parent::__construct();
        $this->idpasajero = 0;
        $this->idviaje = 0;
    }

    public function cargar($idpersona, $nroD, $nom, $ape, $telefono) {
        parent::cargar($idpersona, $nroD, $nom, $ape, $telefono);
    }

    public function cargarPasajero($idpasajero, $pdocumento, $pnombre, $papellido, $ptelefono, $idviaje) {
        $this->setIdPasajero($idpasajero);
        $this->setDocumento($pdocumento);
        $this->setNombre($pnombre);
        $this->setApellido($papellido);
        $this->setTelefono($ptelefono);
        $this->setIdViaje($idviaje);
    }

    public function setIdPasajero($idpasajero) {
        $this->idpasajero = $idpasajero;
    }

    public function setIdViaje($idviaje) {
        $this->idviaje = $idviaje;
    }

    public function setDocumento($pdocumento) {
        return parent::setNroDoc($pdocumento);
    }

    public function setNombre($pnombre) {
        return parent::setNombre($pnombre);
    }

    public function setApellido($papellido) {
        return parent::setApellido($papellido);
    }

    public function setTelefono($ptelefono) {
        return parent::setTelefono($ptelefono);
    }

    public function setMensajeOperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getIdPasajero() {
        return $this->idpasajero;
    }

    public function getIdViaje() {
        return $this->idviaje;
    }

    public function getDocumento() {
        return parent::getNroDoc();
    }

    public function getNombre() {
        return parent::getNombre();
    }

    public function getApellido() {
        return parent::getApellido();
    }

    public function getTelefono() {
        return parent::getTelefono();
    }

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje) 
                             VALUES ('" . $this->getDocumento() . "', '" . $this->getNombre() . "', '" 
                             . $this->getApellido() . "', '" . $this->getTelefono() . "', '" . $this->getIdViaje() . "')";

        if ($base->iniciar()) {
            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdPasajero($id);
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE pasajero SET pnombre='" . $this->getNombre() . "', papellido='" 
                            . $this->getApellido() . "', ptelefono='" . $this->getTelefono() . "', idviaje='" 
                            . $this->getIdViaje() . "' WHERE idpasajero=" . $this->getIdPasajero();

        if ($base->iniciar()) {
            if ($base->ejecutar($consultaModifica)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        
        if ($base->iniciar()) {
            $consultaBorra = "DELETE FROM pasajero WHERE idpasajero=" . $this->getIdPasajero();
            
            if ($base->ejecutar($consultaBorra)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        
        return $resp; 
    }

    public function listar($condicion = "") {
        $arregloPasajeros = null;
        $base = new BaseDatos();
        $consultaPasajeros = "SELECT * FROM pasajero ";
        
        if (!empty($condicion)) {
            $consultaPasajeros .= 'WHERE ' . $condicion;
        }
        
        $consultaPasajeros .= " ORDER BY papellido ";
        
        if ($base->iniciar()) {
            if ($base->ejecutar($consultaPasajeros)) {
                $arregloPasajeros = array();
                while ($row2 = $base->registro()) {
                    $idpasajero = $row2['idpasajero'];
                    $idviaje = $row2['idviaje'];
                    $NroDoc = $row2['pdocumento'];
                    $Nombre = $row2['pnombre'];
                    $Apellido = $row2['papellido'];
                    $Telefono = $row2['ptelefono'];
                    
                    $pasajero = new Pasajero();
                    $pasajero->cargarPasajero($idpasajero, $NroDoc, $Nombre, $Apellido, $Telefono, $idviaje);
                    array_push($arregloPasajeros, $pasajero);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        
        return $arregloPasajeros;
    }

    public function buscar($dni) {
        $base = new BaseDatos();
        $consultaPersona = "SELECT * FROM pasajero WHERE pdocumento=" . $dni;
        $resp = false;
        
        if ($base->iniciar()) {
            if ($base->ejecutar($consultaPersona)) {
                if ($row2 = $base->registro()) {
                    $idpasajero = $row2['idpasajero'];
                    $this->setIdPasajero($idpasajero);
                    $this->setIdViaje($row2['idviaje']);
                    $this->setDocumento($dni);
                    $this->setNombre($row2['pnombre']);
                    $this->setApellido($row2['papellido']);
                    $this->setTelefono($row2['ptelefono']);
                    $resp = true;
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
        return  "\nId Pasajero: " . $this->getIdPasajero() .
                "\nNombre: " . $this->getNombre() . 
                "\nApellido: " . $this->getApellido() .
                "\nDNI: " . $this->getDocumento() .
                "\nTelefono: " . $this->getTelefono() .
                "\nId Viaje: " . $this->getIdViaje();
    }
}
?>
