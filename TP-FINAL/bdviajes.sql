CREATE DATABASE bdviajes; 

CREATE TABLE empresa(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE responsable (
    rnumeroempleado bigint AUTO_INCREMENT,
    rnumerolicencia bigint,
    PRIMARY KEY (rnumeroempleado)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;;
	
CREATE TABLE viaje (
    idviaje bigint AUTO_INCREMENT, /*codigo de viaje*/
	vdestino varchar(150),
    vcantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
	
CREATE TABLE pasajero (
    idpasajero bigint AUTO_INCREMENT,
    pdocumento varchar(15),
    pnombre varchar(150), 
    papellido varchar(150), 
	ptelefono varchar(15), 
	idviaje bigint,
    PRIMARY KEY (idpasajero),
	FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)	
    )ENGINE=InnoDB DEFAULT CHARSET=utf8; 
 
CREATE TABLE persona (
    idpersona BIGINT AUTO_INCREMENT PRIMARY KEY,
    nrodoc VARCHAR(15) NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    apellido VARCHAR(150) NOT NULL,
    telefono INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


  
