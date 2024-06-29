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
    
    public function listar($condicion = "") {
        $arregloViajes = null;
        $base = new BaseDatos();
        $consultaViajes = "SELECT * FROM viaje";
        if ($condicion != "") {
            $consultaViajes .= ' WHERE ' . $condicion;
        }
        $consultaViajes .= " ORDER BY vdestino";
    
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViajes)) {
                $arregloViajes = array();
                while ($row2 = $base->Registro()) {
                    // Recuperar datos del viaje
                    $idviaje = $row2['idviaje'];
                    $destino = $row2['vdestino'];
                    $cantMaxPasajeros = $row2['vcantmaxpasajeros'];
                    $importe = $row2['vimporte'];
    
                    // Buscar la empresa asociada
                    $empresa = new Empresa();
                    $empresa->Buscar($row2['idempresa']);
    
                    // Buscar los pasajeros asociados
                    $objPasajero = new Pasajero();
                    $colPasajeros = $objPasajero->listar("idviaje=" . $idviaje);
    
                    // Buscar el responsable asociado
                    $objResponsable = new ResponsableV();
                    $objResponsable->Buscar($row2['idresponsable']); // Buscar usando idresponsable
    
                    // Crear el objeto Viaje y cargarlo con los datos recuperados
                    $viaje = new Viaje();
                    $viaje->cargar($idviaje, $destino, $cantMaxPasajeros, $colPasajeros, $empresa, $objResponsable, $importe);
    
                    // Agregar el objeto Viaje al arreglo
                    array_push($arregloViajes, $viaje);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
    
        return $arregloViajes;
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
