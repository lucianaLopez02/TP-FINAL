CREATE DATABASE bdviajes; 

CREATE TABLE empresa(
    idempresa INT AUTO_INCREMENT PRIMARY KEY,
    enombre varchar(150) NOT NULL,
    edireccion varchar(150) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE persona (
    idpersona INT AUTO_INCREMENT PRIMARY KEY,
    nrodoc INT(11) NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    apellido VARCHAR(150) NOT NULL,
    telefono VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE responsable (
    idresponsable INT AUTO_INCREMENT PRIMARY KEY,
    rnumeroempleado INT NOT NULL,
    rnumerolicencia VARCHAR(20) NOT NULL,
    idpersona INT NOT NULL,
    FOREIGN KEY (idpersona) REFERENCES persona(idpersona) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE viaje (
    idviaje INT AUTO_INCREMENT PRIMARY KEY,
    vdestino VARCHAR(100) NOT NULL,
    vcantmaxpasajeros INT NOT NULL,
    idempresa INT NOT NULL,
    idresponsable INT NOT NULL,
    rnumeroempleado INT NOT NULL,
    vimporte DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (idempresa) REFERENCES empresa(idempresa) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idresponsable) REFERENCES responsable(idresponsable) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE pasajero (
    idpasajero INT AUTO_INCREMENT PRIMARY KEY,
    idpersona INT,
    idviaje INT NOT NULL,
    FOREIGN KEY (idviaje) REFERENCES viaje(idviaje) ON DELETE CASCADE,
    FOREIGN KEY (idpersona) REFERENCES persona(idpersona) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


