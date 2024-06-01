CREATE SCHEMA race;

CREATE  TABLE race.etape_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_etape             INT       ,
	id_coureur           INT       ,
	date_depart          DATETIME(6)       ,
	date_arriver         DATETIME(6)       
 ) engine=InnoDB;

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
