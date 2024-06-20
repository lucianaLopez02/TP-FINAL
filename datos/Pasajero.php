<?php
include_once "Persona.php";
class Pasajero extends Persona {
    private $idpasajero;
    private $idviaje;
    private $mensajeoperacion;

    public function __construct() {
        parent::__construct();
        $this->idpasajero = 0;
        $this->idviaje = "";
    }

    public function cargar($idpersona, $nroD, $nom, $ape, $telefono, $idpasajero = null, $idviaje = null) {
        parent::cargar($idpersona, $nroD, $nom, $ape, $telefono);
        if ($idpasajero !== null && $idviaje !== null) {
            $this->setIdPasajero($idpasajero);
            $this->setIdViaje($idviaje);
        }
    }

    public function setIdPasajero($idpasajero) {
        $this->idpasajero = $idpasajero;
    }

    public function setIdViaje($idviaje) {
        $this->idviaje = $idviaje;
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

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }

    /**
     * Recupera los datos de una persona por DNI
     * @param int $dni
     * @return true en caso de encontrar los datos, false en caso contrario 
     */
    public function Buscar($dni) {
        $base = new BaseDatos();
        $consultaPersona = "Select * from pasajero where nrodoc=" . $dni;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPersona)) {
                if ($row2 = $base->Registro()) {
                    parent::Buscar($dni);
                    $this->setIdPasajero($row2['idpasajero']);
                    $this->setIdViaje($row2['idviaje']);
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
                    $idpasajero = $row2['idpasajero'];
                    $idviaje = $row2['idviaje'];
                    $nrodoc = $row2['nrodoc']; // Suponiendo que nrodoc es parte de la tabla pasajero

                    // Crear una nueva instancia de Pasajero
                    $pasajero = new Pasajero();

                    // Usar el método Buscar para llenar los detalles desde la clase Persona
                    if ($pasajero->Buscar($nrodoc)) {
                        // Ahora que Buscar ha llenado los atributos de la clase padre, podemos obtener esos valores
                        $idpersona = $pasajero->getIdPersona(); // Suponiendo que getIdPersona() está definido en la clase Persona
                        $nroD = $pasajero->getNrodoc(); // Suponiendo que getNrodoc() está definido en la clase Persona
                        $nom = $pasajero->getNombre(); // Suponiendo que getNombre() está definido en la clase Persona
                        $ape = $pasajero->getApellido(); // Suponiendo que getApellido() está definido en la clase Persona
                        $telefono = $pasajero->getTelefono(); // Suponiendo que getTelefono() está definido en la clase Persona

                        // Cargar los detalles en el objeto Pasajero
                        $pasajero->cargar($idpersona, $nroD, $nom, $ape, $telefono, $idpasajero, $idviaje);

                        // Añadir el objeto Pasajero al array
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

        $consultaInsertar = "INSERT INTO pasajero(idpersona, idviaje) 
                VALUES (" . $this->getIdPersona() . ",'" . $this->getIdViaje() . "')";
        
        if ($base->Iniciar()) {
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
        if (parent::modificar()) {
            $consultaModifica = "UPDATE pasajero SET idviaje='" . $this->getIdViaje() . "' WHERE idpasajero=" . $this->getIdPasajero();
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
            $consultaBorra = "DELETE FROM pasajero WHERE idpasajero=" . $this->getIdPasajero();
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
            "\nId Pasajero: " . $this->getIdPasajero() .
            "\nId Viaje: " . $this->getIdViaje();
    }
}
?>