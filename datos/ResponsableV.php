<?php
include_once "Persona.php";

class ResponsableV extends Persona {
    private $numEmpleado;
    private $numLicencia;
    private $mensajeoperacion;

    public function __construct() {
        parent::__construct();
        $this->numEmpleado = 0;
        $this->numLicencia = "";
    }

    public function cargar($idPersona, $nrodoc, $nombre, $apellido, $telefono, $numEmpleado = null, $numLicencia = null) {
        parent::cargar($idPersona, $nrodoc, $nombre, $apellido, $telefono);
        if ($numEmpleado != null) {
            $this->setNumEmpleado($numEmpleado);
        }
        if ($numLicencia != null) {
            $this->setNumLicencia($numLicencia);
        }
    }

    public function setNumEmpleado($numEmpleado) {
        $this->numEmpleado = $numEmpleado;
    }

    public function setNumLicencia($numLicencia) {
        $this->numLicencia = $numLicencia;
    }

    public function setmensajeoperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getNumEmpleado() {
        return $this->numEmpleado;
    }

    public function getNumLicencia() {
        return $this->numLicencia;
    }

    public function getmensajeoperacion() {
        return $this->mensajeoperacion;
    }


    public function buscar($idPersona) {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE idpersona=".$idPersona;
        $resp = false;
        if (parent::buscar($idPersona)){
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consulta)) {
                    if ($row2 = $base->Registro()) {
                        $this->setNumEmpleado($row2['rnumeroempleado']);
                        $this->setNumLicencia($row2['rnumerolicencia']);
                        $resp = true;
                    }
                } else {
                    $this->setmensajeoperacion($base->getError());
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        }
        return $resp;
    }

    public function listar($condicion = "") {
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consulta ="
        SELECT r.rnumeroempleado, r.rnumerolicencia, p.idpersona, p.nrodoc, p.nombre, p.apellido, p.telefono
        FROM responsable r
        JOIN persona p ON r.idpersona = p.idpersona";
        if ($condicion != "") {
            $consulta .= ' WHERE ' . $condicion;
        }
        $consulta .= " ORDER BY rnumeroempleado";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arregloResponsable = array();
                while ($row2 = $base->Registro()) {
                    $responsable = new ResponsableV();
                    $responsable->cargar($row2['idpersona'], $row2['nrodoc'], $row2['nombre'], $row2['apellido'], $row2['telefono'], $row2['rnumeroempleado'], $row2['rnumerolicencia']);
                    array_push($arregloResponsable, $responsable);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloResponsable;
    }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;

        if (parent::insertar()) {
            $idPersona = parent::getIdPersona();
            $numEmpleado = $this->getNumEmpleado();
            $numLicencia = $this->getNumLicencia();
    
            $consultaInsertar = "INSERT INTO responsable (rnumeroempleado, rnumerolicencia, idpersona) 
                                 VALUES ($numEmpleado, $numLicencia, $idPersona)";
    
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaInsertar)) {
                    $resp = true;
                } else {
                    $this->setmensajeoperacion($base->getError());
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion(parent::getmensajeoperacion());
        }

        return $resp;
    }
    

    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        if (parent::modificar()) {
            $consultaModifica = "UPDATE responsable SET rnumerolicencia='" . $this->getNumLicencia() . "' WHERE rnumeroempleado=" . $this->getNumEmpleado();
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaModifica)) {
                    $resp = true;
                } else {
                    $this->setmensajeoperacion($base->getError());
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        }
        return $resp;
    }
    

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM responsable WHERE idpersona=" . parent::getIdPersona();
            if ($base->Ejecutar($consultaBorra)) {
                if (parent::eliminar()) {
                    $resp = true;
                } else {
                    $this->setmensajeoperacion($base->getError());
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function __toString() {
        return parent::__toString().
            "\nN° de Empleado: " . $this->getNumEmpleado() .
            "\nN° de Licencia: " . $this->getNumLicencia()."\n";
    }
}
?>
