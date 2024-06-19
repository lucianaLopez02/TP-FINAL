<?php
include_once "BaseDatos.php";

class Persona{
    private $idPersona;
	private $nrodoc;
	private $nombre;
	private $apellido;
	private $telefono;
	private $mensajeoperacion;


	public function __construct(){
		
        $this->idPersona=0;
		$this->nrodoc = "";
		$this->nombre = "";
		$this->apellido = "";
		$this->telefono = "";
	}

	public function cargar($idpersona,$nroD,$nom,$ape,$telefono){	
        $this->setIdPersona($idpersona);
		$this->setNrodoc($nroD);
		$this->setNombre($nom);
		$this->setApellido($ape);
		$this->setTelefono($telefono);
    }
	
	public function setIdPersona($idpersona){
        $this->idPersona=$idpersona;
    }
    public function setNrodoc($nroDNI){
		$this->nrodoc=$nroDNI;
	}
	public function setNombre($nom){
		$this->nombre=$nom;
	}
	public function setApellido($ape){
		$this->apellido=$ape;
	}
	public function setTelefono($telefono){
		$this->telefono=$telefono;
	}
	
	public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}

    public function getIdPersona(){
        return $this->idPersona;
    }
	
	public function getNrodoc(){
		return $this->nrodoc;
	}
	public function getNombre(){
		return $this->nombre ;
	}
	public function getApellido(){
		return $this->apellido ;
	}
	public function getTelefono(){
		return $this->telefono ;
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
		$consultaPersona="Select * from persona where nrodoc=".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){
                    $this->setIdPersona($row2['$idpersona']);					
				    $this->setNrodoc($row2['dni']);
					$this->setNombre($row2['nombre']);
					$this->setApellido($row2['apellido']);
					$this->setTelefono($row2['telefono']);
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
	    $arregloPersona = null;
		$base=new BaseDatos();
		$consultaPersonas="Select * from persona ";
		if ($condicion!=""){
		    $consultaPersonas=$consultaPersonas.' where '.$condicion;
		}
		$consultaPersonas.=" order by apellido ";
		//echo $consultaPersonas;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersonas)){				
				$arregloPersona= array();
				while($row2=$base->Registro()){
					$idPersona=$row2['idpersona'];
					$nroDoc=$row2['nrodoc'];
					$nombre=$row2['nombre'];
					$apellido=$row2['apellido'];
					$telefono=$row2['telefono'];
				
					$perso=new Persona();
					$perso->cargar($idPersona,$nroDoc,$nombre,$apellido,$telefono);
					array_push($arregloPersona,$perso);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloPersona;
	}	


	
	public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO persona(idpersona,nrodoc, apellido, nombre,  email) 
				VALUES (".$this->getIdPersona().",'".$this->getNrodoc().",'".$this->getApellido()."','".$this->getNombre()."')";
		
		if($base->Iniciar()){

			if($base->Ejecutar($consultaInsertar)){

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
		$consultaModifica="UPDATE persona SET idepersona='".$this->getIdPersona()."',apellido='".$this->getApellido()."',nombre='".$this->getNombre()."'
                           ,telefono='".$this->getTelefono()."' WHERE nrodoc=". $this->getNrodoc();
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
				$consultaBorra="DELETE FROM persona WHERE nrodoc=".$this->getNrodoc();
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
	    return "\nId Persona: ".$this->getIdPersona()."\nNombre: ".$this->getNombre(). "\n Apellido:".$this->getApellido()."\n DNI: ".$this->getNrodoc()."\n";
			
	}
}
?>