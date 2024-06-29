<?php
class Viaje{
    private $idviaje;  /*codigo de viaje*/
	private $vdestino;
    private $vcantmaxpasajeros;
    private $colPasajeros;
	private $empresa;//objeto empresa
    private $responsable;//objeto responsable
    private $vimporte;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idviaje=0;
        $this->vdestino="";
        $this->vcantmaxpasajeros="";
        $this->colPasajeros="";
        $this->empresa="";
        $this->responsable="";
        $this->vimporte="";
    }

    public function cargar($idviaje,$vdestino,$vcantmaxpasajeros,$colPasajeros,$empresa,$responsable,$vimporte)
    {
       $this->setIdViaje($idviaje);
        $this->setDestino($vdestino);
        $this->setCantMaxPasajeros($vcantmaxpasajeros);
        $this->setColPasajeros($colPasajeros);
        $this->setEmpresa($empresa);
        $this->setResponsable($responsable);
        $this->setImporte($vimporte);
    }

    public function setIdViaje($idviaje){
        $this->idviaje=$idviaje;
    }
    public function setDestino($vdestino){
        $this->vdestino=$vdestino;
    }
    public function setCantMaxPasajeros($vcantmaxpasajeros){
        $this->vcantmaxpasajeros=$vcantmaxpasajeros;
    }
    public function setColPasajeros($colPasajeros){
        $this->colPasajeros=$colPasajeros;
    }
    public function setEmpresa($empresa){
        $this->empresa=$empresa;
    }
    public function setResponsable($responsable){
        $this->responsable=$responsable;
    }
    public function setImporte($vimporte){
        $this->vimporte=$vimporte;
    }
    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}

    public function getIdViaje(){
        return $this->idviaje;
    }
    public function getDestino(){
        return $this->vdestino;
    }
    public function getCantMaxPasajeros(){
        return $this->vcantmaxpasajeros;
    }
    public function getColPasajeros(){
        return $this->colPasajeros;
    }
    public function getEmpresa(){
        return $this->empresa;
    }
    public function getResponsable(){
        return $this->responsable;
    }
    public function getImporte(){
        return $this->vimporte;
    }
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

    public function contarPasajeros() {
        $cantidad = count($this->getColPasajeros());
        return $cantidad;
      
    }

    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($idviaje){
		$base=new BaseDatos();
		$consultaPersona="Select * from viaje where idviaje=".$idviaje;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){
				    $this->setIdViaje($idviaje);
				    $this->setDestino($row2['vdestino']);
                    $objPasajero=new Pasajero();
                    $colPasajeros= $objPasajero->listar("idviaje=".$idviaje);
                    $this->setColPasajeros($colPasajeros);//como coloco la coleccion? con el listar? o no se coloca?
                    $empresa= new Empresa();
                    $empresa->Buscar($row2['idempresa']);
                    $this->setEmpresa($empresa);
					$this->setCantMaxPasajeros($row2['vcantmaxpasajeros']);
                    $responsable=new ResponsableV();
                    $responsable->Buscar($row2['rnumeroempleado']);
					$this->setResponsable($responsable);//el objeto o el numero? o esto?
					$this->setImporte($row2['vimporte']);
					$resp= true;
				}
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }		
		 return $resp;
	}


    public function listar($condicion=""){
	    $arregloViajes = null;
		$base=new BaseDatos();
		$consultaViajes="\nSelect * from viaje ";
		if ($condicion!=""){
		    $consultaViajes=$consultaViajes.' where '.$condicion;
		}
		$consultaViajes.=" order by vdestino ";
		 
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViajes)){
				$arregloViajes= array();
				while($row2=$base->Registro()){//modificar mas tarde
					//print_r($row2);
				    $idviaje=$row2['idviaje'];
					$destino=$row2['vdestino'];
					$cantMaxPasajeros=$row2['vcantmaxpasajeros'];
                    $empresa= new Empresa();
                    $empresa->Buscar($row2['idempresa']);
					$objPasajero=new Pasajero();
                    $colPasajeros= $objPasajero->listar("idviaje=".$idviaje);//listar devuelve un array con los pasajeros almacenados en la base de datos
                    $objResponsable= new ResponsableV();
					$objResponsable->Buscar($row2['rnumeroempleado']);//Buscar lo que hace es buscar en la base de datos al responsable y
                    // a este objResponsable le asinga los valores del responsable al que se busca
                    $importe=$row2['vimporte'];
				
					$viaje=new Viaje();
                                                                        //?
					$viaje->cargar($idviaje, $destino,$cantMaxPasajeros,$colPasajeros,$empresa,$objResponsable,$importe);
					array_push($arregloViajes,$viaje);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloViajes;
	}

    function obtenerIdResponsablePorNumeroEmpleado($numeroEmpleado) {
        // Realiza la consulta SQL para obtener el idresponsable basado en el número de empleado
        $consulta = "SELECT idresponsable FROM responsable WHERE rnumeroempleado = '" . $numeroEmpleado . "'";
        $resultado = mysqli_query($conexion, $consulta);
    
        if ($resultado) {
            $row = mysqli_fetch_assoc($resultado);
            return $row['idresponsable']; // Devuelve el idresponsable encontrado
        } else {
            echo "Error al ejecutar la consulta: " . mysqli_error($conexion);
            return false; // Devuelve false si hubo un error
        }
    }

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
        $responsable=$this->getResponsable();
        $empresa=$this->getEmpresa();
    
		$consultaInsertar = "INSERT INTO viaje(idviaje, vdestino, vcantmaxpasajeros, idempresa, idresponsable, rnumeroempleado, vimporte) 
                    VALUES (
                        ".$this->getIdViaje().",
                        '".$this->getDestino()."',
                        '".$this->getCantMaxPasajeros()."',
                        '".$empresa->getIdEmpresa()."',
                        (SELECT idresponsable FROM responsable WHERE rnumeroempleado = '".$responsable->getNumEmpleado()."'),
                        '".$responsable->getNumEmpleado()."', 
                        '".$this->getImporte()."'
                    )";
		
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){//el id es la clave primaria
                $this->setIdViaje($id);
			    $resp=  true;

			}	else {
					$this->setmensajeoperacion($base->getError());
					
			}

		} else {
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}


    
    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE viaje SET vdestino='".$this->getDestino()."'
                           ,vcantmaxpasajeros='".$this->getCantMaxPasajeros().
                           "',idempresa='". $this->getEmpresa()->getIdEmpresa()."',rnumeroempleado='".
                           $this->getResponsable()->getNumEmpleado()."',vimporte='".$this->getImporte()."' WHERE idviaje=".$this->getIdViaje();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}

    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getIdViaje();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());
					
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}


    public function __toString(){
	    return  "\nId Viaje: ".$this->getIdViaje().
                "\nDestino: ".$this->getDestino(). 
                "\nCantidad Maxima de Pasajeros :".$this->getCantMaxPasajeros().
                "\n-----------------------------\n".
                "\nEmpresa: ".$this->getEmpresa().
                "\n-----------------------------\n".
                "\nResponsable: ".$this->getResponsable().
                "\n-----------------------------\n".
                "\nImporte: ".$this->getImporte()."\n";
                
			
	}


}
?>