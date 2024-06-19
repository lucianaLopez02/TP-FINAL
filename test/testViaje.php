<?php

include_once '../datos/BaseDatos.php';
include_once '../datos/Viaje.php';
include_once '../datos/Empresa.php';
include_once '../datos/Pasajero.php';
include_once '../datos/ResponsableV.php';

//Funciones de escritura de colores en consola

/**
 * Escribe en color verde
 * @param string $texto
 */
function escribirVerde($texto)
{
    echo "\033[32m" . $texto . "\033[0m";
}

/**
 * Escribe en color rojo
 * @param string $texto
 */
function escribirRojo($texto)
{
    echo "\033[31m" . $texto . "\033[0m";
}

/**
 * Escribe en color amarillo
 * @param string $texto
 */

function escribirAmarillo($texto)
{
    echo "\033[33m" . $texto . "\033[0m";
}

/**
 * Escribe en color azul
 * @param string $texto
 */

function escribirAzul($texto)
{
    echo "\033[34m" . $texto . "\033[0m";
}

function opcionesMenu()
{
    //Menu inicial
    escribirVerde("-------Menu Principal-----");
    escribirVerde("\n 1. Menu empresa\n 2. Menu viaje\n 3. Menu pasajero\n 4. Menu responsable\n 5. Salir\n");
    escribirVerde("--------------------------\n");
}

function menuEmpresa()
{
    escribirAzul("------- Menu Empresa -------");
    escribirAzul("\n 1. Insertar Empresa\n 2. Modificar Empresa\n 3. Listar Empresas\n 4. Eliminar Empresa\n 5. Volver al Menu Principal\n");
    escribirAzul("----------------------------\n");
}

function menuViaje()
{
    escribirAzul("------- Menu Viaje -------");
    escribirAzul("\n 1. Insertar Viaje\n 2. Modificar Viaje\n 3. Listar Viajes\n 4. Eliminar Viaje\n 5. Volver al Menu Principal\n");
    escribirAzul("--------------------------\n");
}

function menuPasajero()
{
    escribirAzul("------- Menu Pasajero -------");
    escribirAzul("\n 1. Insertar Pasajero\n 2. Modificar Pasajero\n 3. Listar Pasajeros\n 4. Eliminar Pasajero\n 5. Volver al Menu Principal\n");
    escribirAzul("----------------------------\n");
}

function menuResponsable()
{
    escribirAzul("------- Menu Responsable -------");
    escribirAzul("\n 1. Insertar Responsable\n 2. Modificar Responsable\n 3. Listar Responsables\n 4. Eliminar Responsable\n 5. Volver al Menu Principal\n");
    escribirAzul("-------------------------------\n");
}

// Funciones CRUD para Empresa
function insertarEmpresa()
{
    escribirVerde("Ingrese nombre de la empresa\n");
    $nombre = trim(fgets(STDIN));
    escribirVerde("Ingrese dirección de la empresa\n");
    $direccion = trim(fgets(STDIN));
    $empresa = new Empresa();
    $empresa->cargar(0, $nombre, $direccion);

    if ($empresa->insertar()) {
        escribirVerde("Empresa insertada correctamente.\n");
        escribirVerde($empresa . "\n");
    } else {
        escribirRojo("Error al insertar empresa: " . $empresa->getmensajeoperacion() . "\n");
    }
}

function modificarEmpresa()
{
    //mostrar listado de empresas a modificar 
    $empresa = new Empresa();
    $empresas = $empresa->listar();

     if (empty($empresas)) {
        escribirRojo("No hay empresas existentes.\n");
    } else {
        escribirVerde("Listado de empresas:\n");
        foreach ($empresas as $unaEmpresa) {
            escribirVerde($unaEmpresa . "\n");
        }

    escribirVerde("\nIngrese ID de la empresa a modificar: \n");
    $id = trim(fgets(STDIN));
    $empresa = new Empresa();
    if ($empresa->Buscar($id)) {
        escribirVerde("Ingrese nuevo nombre de la empresa\n");
        $nombre = trim(fgets(STDIN));
        escribirVerde("Ingrese nueva dirección de la empresa\n");
        $direccion = trim(fgets(STDIN));
        $empresa->setNombreEmpresa($nombre);
        $empresa->setDireccion($direccion);
        if ($empresa->modificar()) {
            escribirVerde("Empresa modificada correctamente.\n");
        } else {
            escribirRojo("Error al modificar empresa: " . $empresa->getmensajeoperacion() . "\n");
        }
    } else {
        escribirRojo("Empresa no encontrada.\n");
    }
}
}

function listarEmpresas()
{
    $empresa = new Empresa();
    $empresas = $empresa->listar();

    if (empty($empresas) || $empresa->getmensajeoperacion() != "") {
        escribirRojo("No hay empresas existentes \n". $empresa->getmensajeoperacion() . "\n");
    } else {
        escribirVerde("Listado de empresas:\n");
        foreach ($empresas as $unaEmpresa) {
            escribirVerde($unaEmpresa . "\n");
        }
    }
}


function eliminarEmpresa()
{
    // Mostrar listado de empresas a eliminar 
    $empresa = new Empresa();
    $empresas = $empresa->listar();

    if (empty($empresas)) {
        escribirRojo("No hay empresas existentes.\n");
    } else {
        escribirVerde("Listado de empresas:\n");
        foreach ($empresas as $unaEmpresa) {
            escribirVerde($unaEmpresa . "\n");
        }

        escribirVerde("\nIngrese ID de la empresa a eliminar\n");
        $id = trim(fgets(STDIN));

        if ($empresa->Buscar($id)) {
            // Eliminar los viajes asociados a la empresa
            $viaje = new Viaje();
            $viajes = $viaje->listar("idempresa=$id");
            foreach ($viajes as $unViaje) {
                // Eliminar los pasajeros asociados al viaje
                $pasajero = new Pasajero();
                $pasajeros = $pasajero->listar("idviaje={$unViaje->getIdViaje()}");
                foreach ($pasajeros as $unPasajero) {
                    if (!$unPasajero->eliminar()) {
                        escribirRojo("Error al eliminar pasajero: " . $unPasajero->getmensajeoperacion() . "\n");
                    }
                }

                // Ahora eliminar el viaje
                if (!$unViaje->eliminar()) {
                    escribirRojo("Error al eliminar viaje: " . $unViaje->getmensajeoperacion() . "\n");
                }
            }

            // Ahora eliminar la empresa
            if ($empresa->eliminar()) {
                escribirVerde("Empresa eliminada correctamente.\n");

                // Comprobar si no quedan empresas y reiniciar el contador de ID
                $empresasRestantes = $empresa->listar();
                if (empty($empresasRestantes)) {
                    $baseDatos = new BaseDatos();
                    if ($baseDatos->Iniciar()) {
                        $consulta = "ALTER TABLE empresa AUTO_INCREMENT = 1";
                        if ($baseDatos->Ejecutar($consulta)) {
                            escribirVerde("Contador de ID reiniciado correctamente.\n");
                        } else {
                            escribirRojo("Error al reiniciar contador de ID: " . $baseDatos->getError() . "\n");
                        }
                    } else {
                        escribirRojo("Error al iniciar la conexión con la base de datos: " . $baseDatos->getError() . "\n");
                    }
                }
            } else {
                escribirRojo("Error al eliminar empresa: " . $empresa->getmensajeoperacion() . "\n");
            }
        } else {
            escribirRojo("Empresa no encontrada.\n");
        }
    }
}
// Repite funciones CRUD similares para Viaje, Pasajero y Responsable

//CRUD PARA VIAJE
function insertarViaje()
{
    escribirVerde("Ingrese el Destino:\n");
    $destino = trim(fgets(STDIN));
    escribirVerde("Ingrese la Cantidad Maxima de Pasajeros:\n");
    $cantPasajeros = trim(fgets(STDIN));
    escribirVerde("Ingrese el Importe del Viaje: \n");
    $importe = trim(fgets(STDIN));

    $empresa = new Empresa();
    $colEmpresas = $empresa->listar();

    foreach ($colEmpresas as $unaEmpresa) {
        escribirVerde($unaEmpresa . "\n");
        escribirVerde("\n-----------\n");
    }
    escribirVerde("Ingrese El Id de la Empresa:\n");
    $idEmpresa = trim(fgets(STDIN));

    // Verificar si la empresa existe
    if (!$empresa->Buscar($idEmpresa)) {
        escribirRojo("Error: La empresa con ID ".$idEmpresa." no existe. No se puede crear el viaje.\n");
    } else {
        $responsable = new ResponsableV();
        $responsables = $responsable->listar();
        if ($responsables) {
            escribirVerde("Listado de responsables:\n");
            foreach ($responsables as $unResponsable) {
                escribirVerde($unResponsable . "\n");
                escribirVerde("\n-----------\n");
            }
            escribirVerde("Ingrese el N° de Empleado del Responsable\n");
            $numRes = trim(fgets(STDIN));
            // Verificar si el responsable existe
            if (!$responsable->Buscar($numRes)) {
                escribirRojo("Error: El responsable con número de empleado " . $numRes . " no existe. No se puede crear el viaje.\n");
            } else {
                $colResp = $responsable->listar();
                foreach ($colResp as $unResp) {
                    escribirVerde($unResp);
                    escribirVerde("\n-----------\n");
                }

                $viaje = new Viaje();
                $viaje->cargar(0, $destino, $cantPasajeros, [], $empresa, $responsable, $importe);

                if ($viaje->insertar()) {
                    escribirVerde("Viaje insertado correctamente.\n");
                    escribirVerde($viaje . "\n");
                } else {
                    escribirRojo("Error al insertar viaje: " . $viaje->getmensajeoperacion() . "\n");
                }
            }
        } else {
            // Si no hay responsables, mostrar un mensaje de error
            escribirRojo("Error: No existen responsables. No se puede crear el viaje.\n");
        }
    }
}

function modificarViaje()
{
    //mostrar listado de viajes para modificar 
    $viaje = new Viaje();
    $viajes = $viaje->listar();

     if (empty($viajes)) {
        escribirRojo("No hay viajes existentes.\n");
    } else {
        escribirVerde("Listado de viajes:\n");
        foreach ($viajes as $unViaje) {
            escribirVerde($unViaje . "\n");
        }


    escribirVerde("Ingrese ID del viaje a modificar\n");
    $id = trim(fgets(STDIN));
    $viaje = new Viaje();
    if ($viaje->Buscar($id)) {
        escribirVerde("Ingrese nuevo destino del viaje\n");
        $nuevoDestino = trim(fgets(STDIN));
        escribirVerde("Ingrese nueva cantidad maxima de Pasajeros\n");
        $nuevaCantMaxPasajeros = trim(fgets(STDIN));
        escribirVerde("Ingrese nuevo importe del viaje\n");
        $nuevoImporte = trim(fgets(STDIN));

        $viaje->setDestino($nuevoDestino);
        $viaje->setCantMaxPasajeros($nuevaCantMaxPasajeros);
        $viaje->setImporte($nuevoImporte);

        if ($viaje->modificar()) {
            escribirVerde("Viaje modificado correctamente.\n");
        } else {
            escribirRojo("Error al modificar el viaje: " . $viaje->getmensajeoperacion() . "\n");
        }
    } else {
        escribirRojo("Viaje no encontrado.\n");
    }
}
}

function listarViajes()
{
    $viaje = new Viaje();
    $viajes = $viaje->listar();
    if (empty($viajes) || $viaje->getmensajeoperacion() != "") {
        escribirRojo("No hay viajes existentes \n". $viaje->getmensajeoperacion() . "\n");
    }else{
        escribirVerde("Listado de viajes:\n");
        foreach ($viajes as $unViaje) {
            escribirVerde($unViaje . "\n");
        }
    } 
}

function eliminarViaje()
{
    // Mostrar viajes para eliminar
    $viaje = new Viaje();
    $viajes = $viaje->listar();

    if (empty($viajes)) {
        escribirRojo("No hay viajes existentes.\n");
    } else {
        escribirVerde("Listado de viajes:\n");
        foreach ($viajes as $unViaje) {
            escribirVerde($unViaje . "\n");
        }

        escribirVerde("Ingrese ID del viaje a eliminar\n");
        $id = trim(fgets(STDIN));
        $viaje = new Viaje();
        if ($viaje->Buscar($id)) {
            // Eliminar pasajeros asociados al viaje
            $baseDatos = new BaseDatos();
            if ($baseDatos->Iniciar()) {
                $consulta = "DELETE FROM pasajero WHERE idviaje = ".$id;
                $params = array($id);
                if ($baseDatos->Ejecutar($consulta, $params)) {
                    escribirVerde("Pasajeros eliminados correctamente.\n");

            // Eliminar el viaje después de eliminar los pasajeros
            if ($viaje->eliminar()) {
                escribirVerde("Viaje eliminado correctamente.\n");
                
                // Reiniciar el contador de ID si no hay más viajes
                if (empty($viaje->listar())) {
                    $baseDatos = new BaseDatos();
                    if ($baseDatos->Iniciar()) {
                        $consulta = "ALTER TABLE viaje AUTO_INCREMENT = 1";
                        if ($baseDatos->Ejecutar($consulta)) {
                            escribirVerde("Contador de ID reiniciado correctamente.\n");
                        } else {
                            escribirRojo("Error al reiniciar contador de ID: " . $baseDatos->getError() . "\n");
                        }
                    } else {
                        escribirRojo("Error al iniciar la conexión con la base de datos: " . $baseDatos->getError() . "\n");
                    }
                }
                    } else {
                        escribirRojo("Error al eliminar viaje: " . $viaje->getmensajeoperacion() . "\n");
                    }
                } else {
                    escribirRojo("Error al eliminar pasajeros: " . $baseDatos->getError() . "\n");
                }
            } else {
                escribirRojo("Error al iniciar la conexión con la base de datos: " . $baseDatos->getError() . "\n");
            }
        } else {
            escribirRojo("Viaje no encontrado.\n");
        }
    }
}


//CRUD para responsable
function insertarResponsable()
{
    $responsable = new ResponsableV();
    escribirVerde("Ingrese el número de empleado del responsable: \n");
    $numEmpleado = trim(fgets(STDIN));

    // Verificar si el responsable ya existe
    if ($responsable->Buscar($numEmpleado)) {
        escribirRojo("El responsable con número de empleado $numEmpleado ya existe.\n");
    } else {
        // Solicitar los datos del nuevo responsable
        escribirVerde("Ingrese el número de licencia del responsable: \n");
        $numLicencia = trim(fgets(STDIN));
        escribirVerde("Ingrese el nombre del responsable: \n");
        $nombre = trim(fgets(STDIN));
        escribirVerde("Ingrese el apellido del responsable: \n");
        $apellido = trim(fgets(STDIN));

        // Cargar los datos del nuevo responsable y tratar de insertarlo en la base de datos
        $responsable->cargar($numEmpleado, $numLicencia, $nombre, $apellido);
        if ($responsable->insertar()) {
            escribirVerde("Responsable insertado correctamente.\n");
            $responsable->cargar(0, $numLicencia, $nombre, $apellido);
            $colResp = $responsable->listar();
            foreach ($colResp as $unResp) {
                escribirVerde($unResp);
                escribirVerde("\n-----------\n");
            }
        } else {
            escribirRojo("Error al insertar responsable: " . $responsable->getmensajeoperacion() . "\n");
        }
    }
}

function modificarResponsable()
{
    //mostrar listado de responsables
    $responsable = new ResponsableV();
    $responsables = $responsable->listar();

     if (empty($responsables)) {
        escribirRojo("No hay responsables existentes.\n");
    } else {
        escribirVerde("Listado de responsables:\n");
        foreach ($responsables as $unResponsable) {
            escribirVerde($unResponsable . "\n");
        }

    escribirVerde("Ingrese el numero de empleado del Responsable:\n");
    $nroEmpleado = trim(fgets(STDIN));
    $responsable = new ResponsableV();
    if ($responsable->Buscar($nroEmpleado)) {
        escribirVerde("Ingrese el nuevo numero de licencia del responsable:\n");
        $nuevaLicencia = trim(fgets(STDIN));
        escribirVerde("Ingrese nuevo nombre del Responsable:\n");
        $nuevoNombre = trim(fgets(STDIN));
        escribirVerde("Ingrese nuevo apellido del Responsable:\n");
        $nuevoApellido = trim(fgets(STDIN));

        $responsable->setNumLicencia($nuevaLicencia);
        $responsable->setNombre($nuevoNombre);
        $responsable->setApellido($nuevoApellido);

        if ($responsable->modificar()) {
            escribirVerde("Responsable modificado correctamente.\n");
        } else {
            escribirRojo("Error al modificar el responsable: " . $responsable->getmensajeoperacion() . "\n");
        }
    } else {
        escribirRojo("Responsable no encontrado.\n");
    }
}
}

function listarResponsables()
{
    $responsable = new ResponsableV();
    $responsables = $responsable->listar();

    if (empty($responsables) || $responsable->getmensajeoperacion() != "") {
        escribirRojo("No hay responsables existentes \n". $responsable->getmensajeoperacion() . "\n");
    } else {
        escribirVerde("Listado de responsables:\n");
        foreach ($responsables as $unResponsable) {
            escribirVerde($unResponsable . "\n");
        }
    }
}

function eliminarResponsable()
{
    //mostrar listado de responsables
    $responsable = new ResponsableV();
    $responsables = $responsable->listar();

     if (empty($responsables)) {
        escribirRojo("No hay responsables existentes.\n");
    } else {
        escribirVerde("Listado de responsables:\n");
        foreach ($responsables as $unResponsable) {
            escribirVerde($unResponsable . "\n");
        }

    escribirVerde("Ingrese numero de empleado del Responsable a eliminar\n");
    $nroEmpleado = trim(fgets(STDIN));
    $responsable = new ResponsableV();
    if ($responsable->Buscar($nroEmpleado)) {
        if ($responsable->eliminar()) {
            escribirVerde("Responsable eliminado correctamente.\n");
            // if (empty($responsable)) {
            //     // Si no hay responsable, reiniciamos el contador de ID
            //     $baseDatos = new BaseDatos();
            //     if ($baseDatos->Iniciar()) {
            //         // Ejecutar consulta SQL para reiniciar el contador de ID
            //         $consulta = "ALTER TABLE responsable AUTO_INCREMENT = 1";
            //         if ($baseDatos->Ejecutar($consulta)) {
            //             escribirVerde("Contador de ID reiniciado correctamente.\n");
            //         } else {
            //             escribirRojo("Error al reiniciar contador de ID: " . $baseDatos->getError() . "\n");
            //         }
            //     } else {
            //         escribirRojo("Error al iniciar la conexión con la base de datos: " . $baseDatos->getError() . "\n");
            //     }
            // }
        } else {
            escribirRojo("Error al eliminar responsable: " . $responsable->getmensajeoperacion() . "\n");
        }
    } else {
        escribirRojo("Responsable no encontrado.\n");
    }
}
}

//CRUD de Pasajero

// Solicita al usuario que seleccione un viaje y devuelve el objeto del viaje
function seleccionarViaje()
{
    escribirVerde("Seleccione un viaje:\n");
    $viaje = new Viaje();
    $viajes = $viaje->listar();
    $resultado = null; // Variable para almacenar el resultado

    if ($viajes) {
        foreach ($viajes as $unViaje) {
            escribirVerde($unViaje . "\n");
        }
        escribirVerde("Ingrese el ID del viaje seleccionado:\n");
        $idViaje = trim(fgets(STDIN));
        if ($viaje->Buscar($idViaje)) {
            $resultado = $viaje;
        } else {
            escribirRojo("Viaje no encontrado.\n");
        }
    } else {
        escribirRojo("No hay viajes disponibles.\n");
    }

    return $resultado; // Retorno único al final
}

function insertarPasajero($viaje)
{
    $continuarEjecucion = true; // Variable de control
    if ($viaje->contarPasajeros() >= $viaje->getCantMaxPasajeros()) {
        escribirRojo("No se puede insertar el pasajero. La capacidad máxima del viaje ha sido alcanzada.\n");
        $continuarEjecucion = false; // Cambiar la variable de control
    }

    if ($continuarEjecucion) {
        $pasajero = new Pasajero();

        // Solicitar los datos del nuevo pasajero
        escribirVerde("Ingrese el DNI del pasajero:\n");
        $dni = trim(fgets(STDIN));
        escribirVerde("Ingrese el nombre del pasajero:\n");
        $nombre = trim(fgets(STDIN));
        escribirVerde("Ingrese el apellido del pasajero:\n");
        $apellido = trim(fgets(STDIN));
        escribirVerde("Ingrese el teléfono del pasajero:\n");
        $telefono = trim(fgets(STDIN));

        // Verificar si el pasajero ya existe
        if ($pasajero->buscar($dni)) {
            escribirRojo("El pasajero con DNI $dni ya existe.\n");
        } else {
            // Cargar los datos del nuevo pasajero y tratar de insertarlo en la base de datos
            $pasajero->cargar(0, $dni, $nombre, $apellido, $telefono);
            $pasajero->setIdViaje($viaje->getIdViaje()); // Asignar el idviaje del objeto $viaje
            if ($pasajero->insertar()) {
                escribirVerde("Pasajero insertado correctamente.\n");
            } else {
                escribirRojo("Error al insertar pasajero: " . $pasajero->getMensajeOperacion() . "\n");
            }
        }
    }
}

function modificarPasajero()
{
    $pasajero = new Pasajero();

    $pasajeros = $pasajero->listar();
    
    if (empty($pasajeros)) {
        escribirRojo("No hay pasajeros existentes.\n");
    } else {
        escribirVerde("Listado de pasajeros:\n");
        foreach ($pasajeros as $unPasajero) {
            escribirVerde($unPasajero . "\n");
        }


    // Solicitar el DNI del pasajero a modificar
    escribirVerde("Ingrese el DNI del pasajero a modificar:\n");
    $dni = trim(fgets(STDIN));

    // Verificar si el pasajero existe
    if ($pasajero->Buscar($dni)) {
        // Solicitar los nuevos datos del pasajero
        escribirVerde("Ingrese el nuevo nombre del pasajero:\n");
        $nombre = trim(fgets(STDIN));
        escribirVerde("Ingrese el nuevo apellido del pasajero:\n");
        $apellido = trim(fgets(STDIN));
        escribirVerde("Ingrese el nuevo teléfono del pasajero:\n");
        $telefono = trim(fgets(STDIN));

        // Modificar los datos del pasajero y actualizar en la base de datos
        $pasajero->setNombre($nombre);
        $pasajero->setApellido($apellido);
        $pasajero->setTelefono($telefono);

        if ($pasajero->modificar()) {
            escribirVerde("Pasajero modificado correctamente.\n");
        } else {
            escribirRojo("Error al modificar pasajero: " . $pasajero->getmensajeoperacion() . "\n");
        }
    } else {
        escribirRojo("No se encontró un pasajero con el DNI $dni.\n");
    }
}
}

function listarPasajeros($viaje)
{
    $pasajero = new Pasajero();
    $condicion = "idviaje=" . $viaje->getIdViaje();
    $colPasajeros = $pasajero->listar($condicion);

    if (empty($colPasajeros) || $pasajero->getmensajeoperacion() != "") {
        escribirRojo("No hay pasajeros existentes \n". $pasajero->getmensajeoperacion() . "\n");
    } elseif (count($colPasajeros) > 0) {
        escribirVerde("Listado de pasajeros:\n");
        foreach ($colPasajeros as $unPasajero) {
            escribirVerde($unPasajero . "\n");
            escribirVerde("\n-----------\n");
        }
    }
}

function eliminarPasajero()
{
    $pasajero = new Pasajero();

    // Mostrar lista de pasajeros para eliminar
    $pasajeros = $pasajero->listar();

    // Verificar si hay pasajeros para eliminar
    if (empty($pasajeros)) {
        escribirRojo("No hay pasajeros existentes.\n");
        return; // Salir de la función si no hay pasajeros
    } else {
        escribirVerde("Listado de pasajeros:\n");
        foreach ($pasajeros as $unPasajero) {
            escribirVerde($unPasajero . "\n");
        }
    }

    // Solicitar el DNI del pasajero a eliminar
    escribirVerde("Ingrese el DNI del pasajero a eliminar: ");
    $dni = trim(fgets(STDIN));

    // Verificar si el pasajero existe
    if ($pasajero->Buscar($dni)) {
        // Eliminar el pasajero de la base de datos
        if ($pasajero->eliminar()) {
            escribirVerde("Pasajero eliminado correctamente.\n");

            // Verificar si no hay más pasajeros
            $pasajerosRestantes = $pasajero->listar(); // Obtener lista actualizada
            if (empty($pasajerosRestantes)) {
                // Si no hay pasajeros, reiniciamos el contador de ID
                $baseDatos = new BaseDatos();
                if ($baseDatos->Iniciar()) {
                    // Ejecutar consulta SQL para reiniciar el contador de ID
                    $consulta = "ALTER TABLE pasajero AUTO_INCREMENT = 1";
                    if ($baseDatos->Ejecutar($consulta)) {
                        escribirVerde("Contador de ID reiniciado correctamente.\n");
                    } else {
                        escribirRojo("Error al reiniciar contador de ID: " . $baseDatos->getError() . "\n");
                    }
                } else {
                    escribirRojo("Error al iniciar la conexión con la base de datos: " . $baseDatos->getError() . "\n");
                }
            }

        } else {
            escribirRojo("Error al eliminar pasajero: " . $pasajero->getmensajeoperacion() . "\n");
        }
    } else {
        escribirRojo("No se encontró un pasajero con el DNI $dni.\n");
    }
}
// Ejecución del menú principal
do {
    opcionesMenu();
    escribirVerde("Seleccione una opción\n");
    $opcion = trim(fgets(STDIN));
    switch ($opcion) {
        case 1:
            do {
                menuEmpresa();
                escribirVerde("Seleccione una opción\n");
                $opcionEmpresa = trim(fgets(STDIN));
                switch ($opcionEmpresa) {
                    case 1:
                        insertarEmpresa();
                        break;
                    case 2:
                        modificarEmpresa();
                        break;
                    case 3:
                        listarEmpresas();
                        break;
                    case 4:
                        eliminarEmpresa();
                        break;
                    case 5:
                        escribirVerde("Volviendo al menú principal...\n");
                        break;
                    default:
                        escribirRojo("Opción no válida.\n");
                }
            } while ($opcionEmpresa != 5);
            break;
        case 2:
            do {
                menuViaje();

                escribirVerde("Seleccione una opción\n");
                $opcionViaje = trim(fgets(STDIN));
                switch ($opcionViaje) {
                    // Inserta aquí las funciones CRUD para Viaje
                    case 1:
                        insertarViaje();
                        break;
                    case 2:
                        modificarViaje();
                        break;
                    case 3:
                        listarViajes();
                        break;
                    case 4:
                        eliminarViaje();
                        break;
                    case 5:
                        escribirVerde("Volviendo al menú principal...\n");
                        break;
                    default:
                        escribirRojo("Opción no válida.\n");
                }
            } while ($opcionViaje != 5);
            break;
        case 3:
            do {
                menuPasajero();
                escribirVerde("Seleccione una opción\n");

                $opcionPasajero = trim(fgets(STDIN));

                switch ($opcionPasajero) {
                    // Inserta aquí las funciones CRUD para Pasajero
                    case 1:
                        $viajeSeleccionado = seleccionarViaje();
                        if ($viajeSeleccionado != null) {
                            insertarPasajero($viajeSeleccionado);
                        } else {
                            escribirRojo("No existe viaje seleccionado\n");
                        }
                        break;
                        
                    case 2:
                        modificarPasajero();
                        break;
                    case 3:
                        $viajeSeleccionado = seleccionarViaje();
                        if ($viajeSeleccionado != null) {
                            listarPasajeros($viajeSeleccionado);
                        } else {
                            escribirRojo("No existe viaje seleccionado\n");
                        }
                        break;
                    
                    case 4:
                        eliminarPasajero();
                        break;
                    case 5:
                        escribirVerde("Volviendo al menú principal...\n");
                        break;
                    default:
                        escribirRojo("Opción no válida.\n");
                }
            } while ($opcionPasajero != 5);
            break;
        case 4:
            do {
                menuResponsable();
                escribirVerde("Seleccione una opción\n");
                $opcionResponsable = trim(fgets(STDIN));
                switch ($opcionResponsable) {
                    // Inserta aquí las funciones CRUD para Responsable
                    case 1:
                        insertarResponsable();
                        break;
                    case 2:
                        modificarResponsable();
                        break;
                    case 3:
                        listarResponsables();
                        break;
                    case 4:
                        eliminarResponsable();
                        break;
                    case 5:
                        escribirVerde("Volviendo al menú principal...\n");
                        break;
                    default:
                        escribirRojo("Opción no válida.\n");
                }
            } while ($opcionResponsable != 5);
            break;
        case 5:
            escribirVerde("Saliendo...\n");
            break;
        default:
            escribirRojo("Opción no válida.\n");
    }
} while ($opcion != 5);
