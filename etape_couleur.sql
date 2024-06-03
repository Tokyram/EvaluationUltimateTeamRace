CREATE SCHEMA race;

CREATE  TABLE race.etape_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_etape             INT       ,
	id_coureur           INT       ,
	date_depart          DATETIME(6)       ,
	date_arriver         DATETIME(6)       
 ) engine=InnoDB;


CREATE VIEW race.etape_coureur_temps_total AS
SELECT 
    id_etape,
    id_coureur,
    TIMESTAMPDIFF(SECOND, date_depart, date_arriver) AS temps_total
FROM 
    race.etape_coureur;


-- rang de chaque coureur dans chaque pour etape
SELECT 
    ec.id_etape,
    ec.id_coureur,
    ec.temps_total,
    @rank := IF(@currentEtape = ec.id_etape, @rank + 1, 1) AS rang,
    @currentEtape := ec.id_etape
FROM 
    (SELECT 
        id_etape,
        id_coureur,
        CASE 
            WHEN date_depart IS NULL OR date_arriver IS NULL THEN 999999999
            ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
        END AS temps_total
    FROM 
        race.etape_coureur) ec
JOIN 
    (SELECT @rank := 0, @currentEtape := 0) r
ORDER BY 
    ec.id_etape, ec.temps_total;


-- attributrion de point de chaque coureur dans chaque etape selon le rang
SELECT 
    ec.id_etape,
    ec.id_coureur,
    ec.temps_total,
    @rank := IF(@currentEtape = ec.id_etape, @rank + 1, 1) AS rang,
    @currentEtape := ec.id_etape,
    CASE 
        WHEN ec.temps_total = 999999999 THEN 0
        WHEN @rank = 1 THEN 10
        WHEN @rank = 2 THEN 6
        WHEN @rank = 3 THEN 4
        WHEN @rank = 4 THEN 2
        WHEN @rank = 5 THEN 1
        ELSE 0
    END AS points
FROM 
    (SELECT 
        id_etape,
        id_coureur,
        CASE 
            WHEN date_depart IS NULL OR date_arriver IS NULL THEN 999999999
            ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
        END AS temps_total
    FROM 
        race.etape_coureur) ec
JOIN 
    (SELECT @rank := 0, @currentEtape := 0) r
ORDER BY 
    ec.id_etape, ec.temps_total;

-- interstion des resultat de rang et points dans la table rasultat

INSERT INTO race.resultat (id_etape, id_coureur, rang, point)
SELECT 
    ec.id_etape, 
    ec.id_coureur, 
    ec.rang,
    ec.points
FROM 
    (SELECT 
        ec.id_etape,
        ec.id_coureur,
        ec.temps_total,
        @rank := IF(@currentEtape = ec.id_etape, @rank + 1, 1) AS rang,
        @currentEtape := ec.id_etape,
        CASE 
            WHEN ec.temps_total = 999999999 THEN 0
            WHEN @rank = 1 THEN 10
            WHEN @rank = 2 THEN 6
            WHEN @rank = 3 THEN 4
            WHEN @rank = 4 THEN 2
            WHEN @rank = 5 THEN 1
            ELSE 0
        END AS points
    FROM 
        (SELECT 
            id_etape,
            id_coureur,
            CASE 
                WHEN date_depart IS NULL OR date_arriver IS NULL THEN 999999999
                ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
            END AS temps_total
        FROM 
            race.etape_coureur) ec
    JOIN 
        (SELECT @rank := 0, @currentEtape := 0) r
    ORDER BY 
        ec.id_etape, ec.temps_total) ec
ON DUPLICATE KEY UPDATE rang = VALUES(rang), point = VALUES(point);


INSERT INTO race.resultat (id_etape, id_coureur, rang, point)
				SELECT 
					ec.id_etape, 
					ec.id_coureur, 
					ec.rang,
					ec.points
				FROM 
					(SELECT 
						ec.id_etape,
						ec.id_coureur,
						ec.temps_total,
						@rank := IF(@currentEtape = ec.id_etape, @rank + 1, 1) AS rang,
						@currentEtape := ec.id_etape,
						CASE 
							WHEN ec.temps_total = 999999999 THEN 0
							WHEN @rank = 1 THEN 10
							WHEN @rank = 2 THEN 6
							WHEN @rank = 3 THEN 4
							WHEN @rank = 4 THEN 2
							WHEN @rank = 5 THEN 1
							ELSE 0
						END AS points
					FROM 
						(SELECT 
							id_etape,
							id_coureur,
							CASE 
								WHEN date_depart IS NULL OR date_arriver IS NULL THEN 999999999
								ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
							END AS temps_total
						FROM 
							race.etape_coureur) ec
					JOIN 
						(SELECT @rank := 0, @currentEtape := 0) r
					ORDER BY 
						ec.id_etape, ec.temps_total) ec
				ON DUPLICATE KEY UPDATE rang = VALUES(rang), point = VALUES(point);


$this->db->query('SET @current_rank := 0, @prev_rank := 0, @prev_time := -1, @currentEtape := 0');

			$sql = "
				INSERT INTO race.resultat (id_etape, id_coureur, rang, point)
					SELECT 
						ec.id_etape, 
						ec.id_coureur, 
						CASE 
							WHEN ec.temps_total IS NULL THEN NULL
							ELSE ec.rank
						END AS rang,
						CASE 
							WHEN ec.temps_total IS NULL THEN NULL
							WHEN ec.rank = 1 THEN 10
							WHEN ec.rank = 2 THEN 6
							WHEN ec.rank = 3 THEN 4
							WHEN ec.rank = 4 THEN 2
							WHEN ec.rank = 5 THEN 1
							ELSE 0
						END AS points
					FROM (
						SELECT 
							ec.id_etape,
							ec.id_coureur,
							ec.temps_total,
							@current_rank := IF(@currentEtape = ec.id_etape, 
												IF(@prev_time = ec.temps_total, @prev_rank, @prev_rank := @current_rank + 1), 
												@prev_rank := 1) AS rank,
							@prev_time := ec.temps_total,
							@currentEtape := ec.id_etape
						FROM (
							SELECT 
								id_etape,
								id_coureur,
								CASE 
									WHEN date_depart IS NULL OR date_arriver IS NULL THEN NULL
									ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
								END AS temps_total
							FROM 
								race.etape_coureur
							ORDER BY 
								id_etape, temps_total
						) ec
					) ec
					ON DUPLICATE KEY UPDATE rang = VALUES(rang), point = VALUES(point);



CREATE VIEW race.points_totaux_par_equipe_par_etape AS
SELECT
    r.id_etape,
    e.id AS id_equipe,
    e.nom AS nom_equipe,
    SUM(r.point) AS points_totaux
FROM
    race.resultat r
JOIN
    race.equipe_coureur ec ON r.id_coureur = ec.id_coureur
JOIN
    race.utilisateur e ON ec.id_equipe = e.id
GROUP BY
    r.id_etape,
    e.id,
    e.nom
ORDER BY
    r.id_etape,
    points_totaux DESC;
