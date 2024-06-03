CREATE SCHEMA race;

CREATE  TABLE race.categorie ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE  TABLE race.coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	numero_dossard       VARCHAR(100)       ,
	genre                VARCHAR(100)       ,
	date_naissance       DATE       
 ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

CREATE  TABLE race.etape ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	longueur             DOUBLE       ,
	nb_coureur           INT       ,
	rang_etape           INT       ,
	date_depart          DATETIME       
 ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE  TABLE race.etape_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_etape             INT       ,
	id_coureur           INT       ,
	date_depart          DATETIME(6)       ,
	date_arriver         DATETIME(6)       
 ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

CREATE INDEX fk_etape_coureur_etape ON race.etape_coureur ( id_etape );

CREATE INDEX fk_etape_coureur_coureur ON race.etape_coureur ( id_coureur );

CREATE  TABLE race.points ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	classement           INT       ,
	points               INT       
 ) engine=InnoDB;

CREATE  TABLE race.resultat ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_etape             INT       ,
	id_coureur           INT       ,
	rang                 INT       ,
	point                INT       
 ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

CREATE INDEX fk_resultat_etape ON race.resultat ( id_etape );

CREATE INDEX fk_resultat_coureur ON race.resultat ( id_coureur );

CREATE  TABLE race.utilisateur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	login                VARCHAR(100)       ,
	mdp                  VARCHAR(255)       ,
	type_utilisateur     INT       
 ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE  TABLE race.categorie_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_categorie         INT       ,
	id_coureur           INT       
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE INDEX fk_categorie_coureur_coureur ON race.categorie_coureur ( id_coureur );

CREATE INDEX fk_categorie_coureur_categorie ON race.categorie_coureur ( id_categorie );

CREATE  TABLE race.equipe_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_equipe            INT       ,
	id_coureur           INT       
 ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE INDEX fk_equipe_coureur_coureur ON race.equipe_coureur ( id_coureur );

CREATE INDEX fk_equipe_coureur_utilisateur ON race.equipe_coureur ( id_equipe );

ALTER TABLE race.categorie_coureur ADD CONSTRAINT fk_categorie_coureur_categorie FOREIGN KEY ( id_categorie ) REFERENCES race.categorie( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.categorie_coureur ADD CONSTRAINT fk_categorie_coureur_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.equipe_coureur ADD CONSTRAINT fk_equipe_coureur_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.equipe_coureur ADD CONSTRAINT fk_equipe_coureur_utilisateur FOREIGN KEY ( id_equipe ) REFERENCES race.utilisateur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.etape_coureur ADD CONSTRAINT fk_etape_coureur_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.etape_coureur ADD CONSTRAINT fk_etape_coureur_etape FOREIGN KEY ( id_etape ) REFERENCES race.etape( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.resultat ADD CONSTRAINT fk_resultat_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.resultat ADD CONSTRAINT fk_resultat_etape FOREIGN KEY ( id_etape ) REFERENCES race.etape( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

CREATE VIEW race.etape_coureur_temps_total AS select `race`.`etape_coureur`.`id_etape` AS `id_etape`,`race`.`etape_coureur`.`id_coureur` AS `id_coureur`,timestampdiff(SECOND,`race`.`etape_coureur`.`date_depart`,`race`.`etape_coureur`.`date_arriver`) AS `temps_total` from `race`.`etape_coureur`;

CREATE VIEW race.points_totaux_par_equipe_par_etape AS select `r`.`id_etape` AS `id_etape`,`e`.`id` AS `id_equipe`,`e`.`nom` AS `nom_equipe`,sum(`r`.`point`) AS `points_totaux` from ((`race`.`resultat` `r` join `race`.`equipe_coureur` `ec` on((`r`.`id_coureur` = `ec`.`id_coureur`))) join `race`.`utilisateur` `e` on((`ec`.`id_equipe` = `e`.`id`))) group by `r`.`id_etape`,`e`.`id`,`e`.`nom` order by `r`.`id_etape`,`points_totaux` desc;

CREATE VIEW race.classement_general_par_equipe AS select `e`.`id` AS `id_equipe`,`e`.`nom` AS `nom_equipe`,sum(`pte`.`points_totaux`) AS `points_totaux` from (`race`.`points_totaux_par_equipe_par_etape` `pte` join `race`.`utilisateur` `e` on((`pte`.`id_equipe` = `e`.`id`))) group by `e`.`id`,`e`.`nom` order by `points_totaux` desc;

