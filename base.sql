CREATE SCHEMA race;

CREATE  TABLE race.categorie ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       
 ) engine=InnoDB;

CREATE  TABLE race.coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	numero_dossard       VARCHAR(100)       ,
	genre                VARCHAR(100)       ,
	date_naissance       DATE       
 ) engine=InnoDB;

CREATE  TABLE race.etape ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	longueur             DOUBLE       ,
	nb_coureur           INT       ,
	rang_etape           INT       
 ) engine=InnoDB;

CREATE  TABLE race.etape_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_etape             INT       ,
	id_coureur           INT       ,
	date_depart          DATETIME(6)       ,
	date_arriver         DATETIME(6)       
 ) engine=InnoDB;

CREATE  TABLE race.resultat ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_etape             INT       ,
	id_coureur           INT       ,
	rang                 INT       ,
	point                INT       
 ) engine=InnoDB;

CREATE  TABLE race.utilisateur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	login                VARCHAR(100)       ,
	mdp                  VARCHAR(255)       ,
	type_utilisateur     INT       
 ) engine=InnoDB;

CREATE  TABLE race.categorie_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_categorie         INT       ,
	id_coureur           INT       
 ) engine=InnoDB;

CREATE  TABLE race.equipe_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_equipe            INT       ,
	id_coureur           INT       
 ) engine=InnoDB;

ALTER TABLE race.categorie_coureur ADD CONSTRAINT fk_categorie_coureur_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.categorie_coureur ADD CONSTRAINT fk_categorie_coureur_categorie FOREIGN KEY ( id_categorie ) REFERENCES race.categorie( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.equipe_coureur ADD CONSTRAINT fk_equipe_coureur_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.equipe_coureur ADD CONSTRAINT fk_equipe_coureur_utilisateur FOREIGN KEY ( id_equipe ) REFERENCES race.utilisateur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.etape_coureur ADD CONSTRAINT fk_etape_coureur_etape FOREIGN KEY ( id_etape ) REFERENCES race.etape( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.etape_coureur ADD CONSTRAINT fk_etape_coureur_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

