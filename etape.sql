CREATE SCHEMA race;

CREATE  TABLE race.etape ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	longueur             DOUBLE       ,
	nb_coureur           INT       ,
	rang_etape           INT       
 ) engine=InnoDB;

INSERT INTO race.etape (nom, longueur, nb_coureur, rang_etape) VALUES
('betsizaraina', 7.0, 2, 1),
('ankazobe', 5.5, 1, 2),
('ampasibe', 3.0, 1, 3),
('ambato', 4.5, 2, 4);
