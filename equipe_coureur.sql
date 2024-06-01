CREATE SCHEMA race;

CREATE  TABLE race.equipe_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_equipe            INT       ,
	id_coureur           INT       
 ) engine=InnoDB;

INSERT INTO race.equipe_coureur (id_equipe, id_coureur) VALUES
(6, 1),
(6, 2),
(7, 3),
(7, 4),
(8, 5),
(8, 6),
(6, 7),
(9, 8),
(9, 9);

SELECT
    utilisateur.nom AS nom_equipe,
    coureur.nom AS nom_coureur
FROM
    race.equipe_coureur
JOIN
    race.utilisateur ON race.equipe_coureur.id_equipe = race.utilisateur.id
JOIN
    race.coureur ON race.equipe_coureur.id_coureur = race.coureur.id;
