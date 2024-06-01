CREATE SCHEMA race;

CREATE  TABLE race.coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	numero_dossard       VARCHAR(100)       ,
	genre                VARCHAR(100)       ,
	date_naissance       DATE       
 ) engine=InnoDB;

INSERT INTO race.coureur (nom, numero_dossard, genre, date_naissance) VALUES
('Lova', 'A123', 'H', '1990-01-15'),
('Sabrina', 'B456', 'F', '1992-07-24'),
('Justin', 'C789', 'F', '1988-03-11'),
('Vero', 'D012', 'H', '1985-09-30'),
('John', 'E345', 'H', '1995-12-05'),
('Jill', 'F678', 'F', '1991-06-18'),
('Victor', 'G901', 'H', '1993-11-22'),
('Mendrika', 'H234', 'F', '1987-08-14'),
('Toky', 'I567', 'H', '1984-02-27');


SELECT * from coureur 
JOIN equipe_coureur ON equipe_coureur.id_coureur = coureur.id
where equipe_coureur.id_equipe = 2;