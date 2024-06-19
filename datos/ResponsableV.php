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

    // Ajuste en la firma de cargar para que sea compatible con Persona
    public function cargar($idpersona, $numLicencia, $nombre, $apellido, $telefono = "") {
        parent::cargar($idpersona, "", $nombre, $apellido, $telefono); // Llama al método cargar de Persona
        $this->numEmpleado = $idpersona; // Asigna el idpersona al numEmpleado
        $this->numLicencia = $numLicencia;
    }

    public function setNumEmpleado($numEmpleado) {
        $this->numEmpleado = $numEmpleado;
    }

    public function setNumLicencia($numLicencia) {
        $this->numLicencia = $numLicencia;
    }

    public function setNombre($nombre) {
        parent::setNombre($nombre); // Llama al método setNombre de Persona
    }

    public function setApellido($apellido) {
        parent::setApellido($apellido); // Llama al método setApellido de Persona
    }

    public function setMensajeOperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getNumEmpleado() {
        return $this->numEmpleado;
    }

    public function getNumLicencia() {
        return $this->numLicencia;
    }

    public function getNombre() {
        return parent::getNombre(); // Llama al método getNombre de Persona
    }

    public function getApellido() {
        return parent::getApellido(); // Llama al método getApellido de Persona
    }

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }

    public function buscar($numEmpleado) {
        $base = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable WHERE rnumeroempleado=" . $numEmpleado;
        $resp = false;

        if ($base->iniciar()) {
            if ($base->ejecutar($consultaResponsable)) {
                if ($row2 = $base->registro()) {
                    $this->setNumEmpleado($row2['rnumeroempleado']);
                    $this->setNumLicencia($row2['rnumerolicencia']);
                    $this->setNombre($row2['rnombre']);
                    $this->setApellido($row2['rapellido']);
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

    public function listar($condicion = "") {
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable ";

        if (!empty($condicion)) {
            $consultaResponsable .= 'WHERE ' . $condicion;
        }

        $consultaResponsable .= " ORDER BY rapellido ";

        if ($base->iniciar()) {
            if ($base->ejecutar($consultaResponsable)) {
                $arregloResponsable = array();
                while ($row2 = $base->registro()) {
                    $numEmpleado = $row2['rnumeroempleado'];
                    $numLicencia = $row2['rnumerolicencia'];
                    $nombre = $row2['rnombre'];
                    $apellido = $row2['rapellido'];

                    $responsable = new ResponsableV();
                    $responsable->cargar($numEmpleado, $numLicencia, $nombre, $apellido);
                    array_push($arregloResponsable, $responsable);
                }
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }

        return $arregloResponsable;
    }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO responsable(rnumeroempleado, rnumerolicencia, rnombre, rapellido) 
                             VALUES (" . $this->getNumEmpleado() . ", '" . $this->getNumLicencia() . "', '" . $this->getNombre() . "', '" . $this->getApellido() . "')";

        if ($base->iniciar()) {
            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setNumEmpleado($id);
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
        $consultaModifica = "UPDATE responsable SET rapellido='" . $this->getApellido() . "', rnombre='" . $this->getNombre() . "', 
                             rnumeroempleado='" . $this->getNumEmpleado() . "', rnumerolicencia='" . $this->getNumLicencia() . "' 
                             WHERE rnumeroempleado=" . $this->getNumEmpleado();

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
            $consultaBorra = "DELETE FROM responsable WHERE rnumeroempleado=" . $this->getNumEmpleado();

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

    public function __toString() {
        return  "\nN° de Empleado: " . $this->getNumEmpleado() .
                "\nN° de Licencia: " . $this->getNumLicencia() .
                "\nNombre: " . $this->getNombre() .
                "\nApellido:" . $this->getApellido();
    }
}
?>
