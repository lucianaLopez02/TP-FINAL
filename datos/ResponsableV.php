<?php
class ResponsableV{
    private $numEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->numEmpleado=0;
        $this->numLicencia="";
        $this->nombre="";
        $this->apellido="";
    }

    public function cargar($numEmpleado,$numLicencia,$nombre,$apellido){
        $this->setNumEmpleado($numEmpleado);
        $this->setNumLicencia($numLicencia);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }

    public function setNumEmpleado($numEmpleado){
        $this->numEmpleado=$numEmpleado;
    }
    public function setNumLicencia($numLicencia){
        $this->numLicencia=$numLicencia;
    }
    public function setNombre($nombre){
        $this->nombre=$nombre;
    }
    public function setApellido($apellido){
        $this->apellido=$apellido;
    }
    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}


    public function getNumEmpleado(){
        return $this->numEmpleado;
    }
    public function getNumLicencia(){
        return $this->numLicencia;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($numEmpleado){
		$base=new BaseDatos();
		$consultaResponsable="Select * from responsable where rnumeroempleado=".$numEmpleado;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsable)){
				if($row2=$base->Registro()){
				    //print_r($row2);
				    $this->setNumEmpleado($row2['rnumeroempleado']);
					$this->setNumLicencia($row2['rnumerolicencia']);
					$this->setApellido($row2['rapellido']);
					$this->setNombre($row2['rnombre']);
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
	    $arregloResponsable = null;
		$base=new BaseDatos();
		$consultaResponsable="\nSelect * from responsable ";
		if ($condicion!=""){
		    $consultaResponsable=$consultaResponsable.' where '.$condicion;
		}
		$consultaResponsable.=" order by rapellido ";
		
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsable)){
				$arregloResponsable= array();
				while($row2=$base->Registro()){
					//print_r($row2);
					$numEmpleado=$row2['rnumeroempleado'];
					$numLicencia=$row2['rnumerolicencia'];
					$Apellido=$row2['rapellido'];
					$nombre=$row2['rnombre'];
				
					$responsable=new ResponsableV();
					
					$responsable->cargar($numEmpleado,$numLicencia,$nombre,$Apellido);
					array_push($arregloResponsable,$responsable);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloResponsable;
	}

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO responsable(rnumeroempleado,rnumerolicencia,rnombre,rapellido) 
				VALUES (".$this->getNumEmpleado().",'".$this->getNumLicencia()."','".$this->getNombre()."','".$this->getApellido()."')";
		
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){//el id es la clave primaria, numero de empleado
                $this->setNumEmpleado($id);
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
		$consultaModifica="UPDATE responsable SET rapellido='".$this->getApellido()."',rnombre='".$this->getNombre()."'
                           ,rnumeroempleado='".$this->getNumEmpleado()."',rnumerolicencia='". $this->getNumLicencia()."' WHERE rnumeroempleado=".$this->getNumEmpleado();
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
				$consultaBorra="DELETE FROM responsable WHERE rnumeroempleado=".$this->getNumEmpleado();
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
	    return  "\nN° de Empleado: ".$this->getNumEmpleado().
                "\nN° de Licencia: ".$this->getNumLicencia().
                "\nNombre: ".$this->getNombre(). 
                "\nApellido:".$this->getApellido();
			
	}


}
?>