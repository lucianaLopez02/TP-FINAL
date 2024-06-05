<?php
class Empresa{
    private $idempresa; 
    private $enombre;
    private $edireccion;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idempresa=0;
        $this->enombre="";
        $this->edireccion="";
    }

    public function cargar($idempresa,$enombre,$edireccion)
    {
        $this->setIdEmpresa($idempresa);
        $this->setNombreEmpresa($enombre);
        $this->setDireccion($edireccion);
    }

    public function setIdEmpresa($idempresa){
        $this->idempresa=$idempresa;
    }
    public function setNombreEmpresa($enombre){
        $this->enombre=$enombre;
    }
    public function setDireccion($edireccion){
        $this->edireccion=$edireccion;
    }
    public function setmensajeoperacion($mensajeoperacion){
		$this->mensajeoperacion=$mensajeoperacion;
	}

    public function getIdEmpresa(){
        return $this->idempresa;
    }
    public function getNombreEmpresa(){
        return $this->enombre;
    }
    public function getDireccion(){
        return $this->edireccion;
    }
    public function getmensajeoperacion(){
		return $this->mensajeoperacion ;
	}

    /**
	 * Recupera los datos de una persona por dni
	 * @param int $idempresa
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($idempresa){
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa where idempresa=".$idempresa;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){
				if($row2=$base->Registro()){
				    $this->setIdEmpresa($idempresa);
					$this->setNombreEmpresa($row2['enombre']);
					$this->setDireccion($row2['edireccion']);
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
	    $arregloEmpresa = null;
		$base=new BaseDatos();
		$consultaEmpresa="\nSelect * from empresa ";
		if ($condicion!=""){
		    $consultaEmpresa=$consultaEmpresa.' where '.$condicion;
		}
		$consultaEmpresa.=" order by enombre ";
	
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){
				$arregloEmpresa= array();
				while($row2=$base->Registro()){
					//print_r($row2);
				    $idempresa=$row2['idempresa'];
					$nombre=$row2['enombre'];
					$direccion=$row2['edireccion'];
					
				
					$empresa=new Empresa();
					
					$empresa->cargar($idempresa,$nombre,$direccion);
					array_push($arregloEmpresa,$empresa);
	
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloEmpresa;
	}

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO empresa(idempresa,enombre,edireccion) 
				VALUES (".$this->getIdEmpresa().",'".$this->getNombreEmpresa()."','".$this->getDireccion()."')";
		
		if($base->Iniciar()){

			if($id = $base->devuelveIDInsercion($consultaInsertar)){//el id es la clave primaria, osea el idempresa
                $this->setIdEmpresa($id);
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
		$consultaModifica="UPDATE empresa SET idempresa='".$this->getIdEmpresa()."',enombre='".$this->getNombreEmpresa()."'
                           ,edireccion='".$this->getDireccion()."' WHERE idempresa=".$this->getIdEmpresa();
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
				$consultaBorra="DELETE FROM empresa WHERE idempresa=".$this->getIdEmpresa();
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
	    return "\nId Empresa: ".$this->getIdEmpresa().
                "\nNombre :".$this->getNombreEmpresa().
                "\nDireccion: ".$this->getDireccion();
			
	}
}
?>