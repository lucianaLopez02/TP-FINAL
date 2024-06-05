<?php

class Pasajero{
	private $idpasajero;
    private $pdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $idviaje;
    private $mensajeoperacion;

    public function __construct()
    {
	   $this->idpasajero =0;
       $this->pdocumento="";
       $this->pnombre="";
       $this->papellido="";
       $this->ptelefono="";
       $this->idviaje="";
    }

    public function cargar($idpasajero,$pdocumento,$pnombre,$papellido,$ptelefono,$idviaje)
    {
		$this->setIdPasajero($idpasajero);
        $this->setIdViaje($idviaje);
        $this->setDocumento($pdocumento);
        $this->setNombre($pnombre);
        $this->setApellido($papellido);
        $this->setTelefono($ptelefono);
    }

	public function setIdPasajero($idpasajero){
		$this->idpasajero = $idpasajero;
	}


    public function setIdViaje($idviaje){
        $this->idviaje=$idviaje;
    } 
    public function setDocumento($pdocumento){
        $this->pdocumento=$pdocumento;
    }
    public function setNombre($pnombre){
        $this->pnombre=$pnombre;
    }
    public function setApellido($papellido){
        $this->papellido=$papellido;
    }
    public function setTelefono($ptelefono){
        $this->ptelefono=$ptelefono;
    }
    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}

	public function getIdPasajero(){
		return $this->idpasajero;
	}

    public function getIdViaje(){
        return $this->idviaje;
    }
    public function getDocumento(){
        return $this->pdocumento;
    }
    public function getNombre(){
        return $this->pnombre;
    }
    public function getApellido(){
        return $this->papellido;
    }
    public function getTelefono(){
        return $this->ptelefono;
    }
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($dni){
		$base=new BaseDatos();
		$consultaPersona="Select * from pasajero where pdocumento=".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){
					$this->setIdPasajero($idpasajero);
				    $this->setIdViaje($row2['idviaje']);
				    $this->setDocumento($dni);
					$this->setNombre($row2['pnombre']);
					$this->setApellido($row2['papellido']);
					$this->setTelefono($row2['ptelefono']);
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
	    $arregloPasajeros = null;
		$base=new BaseDatos();
		$consultaPasajeros="\nSelect * from pasajero ";
		if ($condicion!=""){
		    $consultaPasajeros=$consultaPasajeros.' where '.$condicion;
		}
		$consultaPasajeros.=" order by papellido ";
		
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajeros)){
				$arregloPasajeros= array();
				while($row2=$base->Registro()){
					//print_r($row2);
					$idpasajero=$row2['idpasajero'];
				    $idviaje=$row2['idviaje'];
					$NroDoc=$row2['pdocumento'];
					$Nombre=$row2['pnombre'];
					$Apellido=$row2['papellido'];
					$Telefono=$row2['ptelefono'];
				
					$pasajero=new Pasajero();
					
					$pasajero->cargar($idpasajero,$NroDoc,$Nombre,$Apellido,$Telefono,$idviaje);
					array_push($arregloPasajeros,$pasajero);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloPasajeros;
	}

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;

		// Asegurarse de que los valores numéricos no estén entre comillas
		$idpasajero = $this->getIdPasajero() !== null ? intval($this->getIdPasajero()) : 'NULL';
		$pdocumento = $this->getDocumento() !== null ? "'" . $this->getDocumento() . "'" : 'NULL';
		$pnombre = $this->getNombre() !== null ? "'" . $this->getNombre() . "'" : 'NULL';
		$papellido = $this->getApellido() !== null ? "'" . $this->getApellido() . "'" : 'NULL';
		$ptelefono = $this->getTelefono() !== null ? "'" . $this->getTelefono() . "'" : 'NULL';
		$idviaje = $this->getIdViaje() !== null ? intval($this->getIdViaje()) : 'NULL'; 
		
		$consultaInsertar="INSERT INTO pasajero(idpasajero,pdocumento,pnombre,papellido,ptelefono,idviaje) 
				VALUES ($idpasajero, $pdocumento, $pnombre, $papellido, $ptelefono, $idviaje)";
		
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){//el id es la clave primaria, osea el idPasajero
                $this->setIdPasajero($id);
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
		$consultaModifica="UPDATE pasajero SET idpasajero='".$this->getIdPasajero()."',papellido='".$this->getApellido()."',pnombre='".$this->getNombre()."'
                           ,ptelefono='".$this->getTelefono()."',pdocumento='". $this->getDocumento()."' WHERE pdocumento=".$this->getDocumento();
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
				$consultaBorra="DELETE FROM pasajero WHERE idpasajero=".$this->getIdPasajero();
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
	    return 	"\nId Pasajero: ".$this->getIdPasajero().
				"\nNombre: ".$this->getNombre(). 
                "\nApellido:".$this->getApellido().
                "\nDNI: ".$this->getDocumento().
                "\nTelefono: ".$this->getTelefono()."\n".
                "\nId Viaje: ".$this->getIdViaje();
			
	}
}

?>