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


-- somme des penalite de l equipe

create or replace view v_penalite as 
SELECT 
    p.id_equipe, 
    p.id_etape, 
    e.nom, 
    SUM(TIME_TO_SEC(p.temps_penalite_equipe)) as penalite_en_secondes
FROM 
    penalite p 
JOIN 
    etape e ON p.id_etape = e.id
GROUP BY 
    p.id_equipe, 
    p.id_etape, 
    e.nom;


-- temps des coureur avec penalite (affectation) eo ambany ny tena mete fa tsy mahazaka view misy select ny mysql
create or replace view etape_coureur_temps_total as 
SELECT 
    temps_coureur.id_etape as id_etape,
    temps_coureur.id_coureur as id_coureur,
     COALESCE((temps_coureur.temps_total + vp.penalite_en_secondes), temps_coureur.temps_total) as temps_total,
    COALESCE(vp.penalite_en_secondes,'0') as penalite,
    temps_coureur.nom_equipe,
    temps_coureur.rang_etape
FROM
(SELECT 
    ec.id_etape,
    ec.id_coureur,
     u.id AS id_equipe,
    TIMESTAMPDIFF(SECOND, ec.date_depart, ec.date_arriver) AS temps_total,
    c.nom AS nom_coureur,
    u.nom AS nom_equipe,
    e.rang_etape AS rang_etape
FROM 
    race.etape_coureur ec
JOIN 
    race.coureur c ON ec.id_coureur = c.id
JOIN 
    race.equipe_coureur eqc ON c.id = eqc.id_coureur
JOIN 
    race.utilisateur u ON eqc.id_equipe = u.id
JOIN etape e on ec.id_etape = e.id
ORDER BY id_etape asc) AS temps_coureur
LEFT JOIN v_penalite vp on vp.id_equipe = temps_coureur.id_equipe and
vp.id_etape = temps_coureur.id_etape


-- vue temps_coureur en second plus detaille
CREATE OR REPLACE VIEW temps_coureur_view AS 
SELECT 
    ec.id_etape,
    ec.id_coureur,
    u.id AS id_equipe,
    TIMESTAMPDIFF(SECOND, ec.date_depart, ec.date_arriver) AS temps_total,
    cc.id_categorie,
    c.nom AS nom_coureur,
    u.nom AS nom_equipe,
    e.rang_etape AS rang_etape
FROM 
    race.etape_coureur ec
JOIN 
    race.coureur c ON ec.id_coureur = c.id
JOIN 
    race.equipe_coureur eqc ON c.id = eqc.id_coureur
JOIN 
    race.utilisateur u ON eqc.id_equipe = u.id
JOIN 
    etape e ON ec.id_etape = e.id
JOIN 
    race.categorie_coureur cc ON ec.id_coureur = cc.id_coureur
ORDER BY 
    id_etape, cc.id_categorie,temps_total;

-- temps des coureur avec penalite (affectation) VRAI
CREATE OR REPLACE VIEW etape_coureur_temps_total AS 
SELECT 
    temps_coureur.id_etape as id_etape,
    temps_coureur.id_coureur as id_coureur,
    COALESCE((temps_coureur.temps_total + vp.penalite_en_secondes), temps_coureur.temps_total) as temps_total,
    COALESCE(vp.penalite_en_secondes, '0') as penalite,
    temps_coureur.nom_equipe,
    temps_coureur.rang_etape
FROM
    temps_coureur_view AS temps_coureur
LEFT JOIN 
    v_penalite vp ON vp.id_equipe = temps_coureur.id_equipe AND vp.id_etape = temps_coureur.id_etape
   

-- resulta taloha
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


-- resultat cotegorie taloha
SELECT 
    ec.id_etape,
    ec.id_coureur,
    cc.id_categorie,
    CASE 
        WHEN ec.date_depart IS NULL OR ec.date_arriver IS NULL THEN NULL
        ELSE TIMESTAMPDIFF(SECOND, ec.date_depart, ec.date_arriver)
    END AS temps_total
    FROM 
        race.etape_coureur ec
    JOIN 
        race.categorie_coureur cc ON ec.id_coureur = cc.id_coureur
    ORDER BY 
        ec.id_etape, cc.id_categorie, temps_total



-- classement equipe_
SELECT 
	etp.id_etape,
	etp.id_coureur,
	CASE 
		WHEN date_depart IS NULL OR date_arriver IS NULL THEN NULL
        WHEN vp.penalite_en_secondes IS NOT NULL THEN  TIMESTAMPDIFF(SECOND, date_depart, date_arriver) + vp.penalite_en_secondes
		ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
	END AS temps_total,
    u.id AS id_equipe, -- alana rehefa avedika vu
    vp.penalite_en_secondes -- alana rehefa avedika vu
FROM race.etape_coureur etp
JOIN race.coureur c on etp.id_coureur = c.id
JOIN race.equipe_coureur eqc ON c.id = eqc.id_coureur
JOIN race.utilisateur u ON eqc.id_equipe = u.id
LEFT JOIN v_penalite vp on vp.id_equipe = eqc.id_equipe AND vp.id_etape = etp.id_etape
ORDER BY id_etape, temps_total

-- classement categorie
SELECT
	etp.id_etape,
	etp.id_coureur,
	CASE 
		WHEN date_depart IS NULL OR date_arriver IS NULL THEN NULL
        WHEN vp.penalite_en_secondes IS NOT NULL THEN  TIMESTAMPDIFF(SECOND, date_depart, date_arriver) + vp.penalite_en_secondes
		ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
	END AS temps_total,
    cc.id_categorie,
    u.id AS id_equipe,
    vp.penalite_en_secondes
FROM race.etape_coureur etp
JOIN race.categorie_coureur cc ON etp.id_coureur = cc.id_coureur
JOIN race.coureur c on etp.id_coureur = c.id
JOIN race.equipe_coureur eqc ON c.id = eqc.id_coureur
JOIN race.utilisateur u ON eqc.id_equipe = u.id
LEFT JOIN v_penalite vp on vp.id_equipe = eqc.id_equipe AND vp.id_etape = etp.id_etape
ORDER BY id_etape,cc.id_categorie, temps_total

-- v2
SELECT 
	etp.id_etape,
	etp.id_coureur,
	cc.id_categorie,
	CASE 
		WHEN date_depart IS NULL OR date_arriver IS NULL THEN NULL
		WHEN vp.penalite_en_secondes IS NOT NULL THEN  TIMESTAMPDIFF(SECOND, date_depart, date_arriver) + vp.penalite_en_secondes
		ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
	END AS temps_total,
FROM race.etape_coureur etp
JOIN race.categorie_coureur cc ON etp.id_coureur = cc.id_coureur
JOIN race.coureur c on etp.id_coureur = c.id
JOIN race.equipe_coureur eqc ON c.id = eqc.id_coureur
JOIN race.utilisateur u ON eqc.id_equipe = u.id
LEFT JOIN v_penalite vp on vp.id_equipe = eqc.id_equipe AND vp.id_etape = etp.id_etape
ORDER BY id_etape,cc.id_categorie, temps_total




-- gagnat avec coureur limite
SELECT 
    ec.id_coureur,
    c.nom AS nom_coureur,
    e.id AS id_equipe,
    e.nom AS nom_equipe,
    classement_equipe.points_totaux,
    @rank := @rank + 1 AS points_totauxpoints_totaux
FROM 
    race.equipe_coureur ec
JOIN 
    race.utilisateur e ON ec.id_equipe = e.id
JOIN 
    race.coureur c ON ec.id_coureur = c.id
JOIN 
    (
        SELECT 
            e.id AS id_equipe,
            e.nom AS nom_equipe,
            SUM(pte.points_totaux) AS points_totaux
        FROM
            race.points_totaux_par_equipe_par_etape pte
        JOIN
            race.utilisateur e ON pte.id_equipe = e.id
        GROUP BY
            e.id,
            e.nom
        ORDER BY
            points_totaux DESC
        LIMIT 1
    ) AS classement_equipe ON classement_equipe.id_equipe = e.id





INSERT INTO race.resultat_categorie (id_etape, id_coureur, id_categorie, rang, point)
						SELECT 
							ec.id_etape, 
							ec.id_coureur, 
							ec.id_categorie,
							CASE 
								WHEN ec.temps_total IS NULL THEN NULL
								ELSE ec.rank
							END AS rang,
							CASE 
								WHEN ec.temps_total IS NULL THEN NULL
								WHEN p.points IS NULL THEN 0 
								ELSE p.points
							END AS points
						FROM (
							SELECT 
								ec.id_etape,
								ec.id_coureur,
								ec.temps_total,
								ec.id_categorie,
								@current_rank := IF(@currentEtape = ec.id_etape AND @currentCategorie = ec.id_categorie, 
													IF(@prev_time = ec.temps_total, @prev_rank, @prev_rank := @current_rank + 1), 
													@prev_rank := 1) AS rank,
								@prev_time := ec.temps_total,
								@currentEtape := ec.id_etape,
								@currentCategorie := ec.id_categorie
							FROM (
								SELECT 
									etp.id_etape,
									etp.id_coureur,
									cc.id_categorie,
									CASE 
										WHEN date_depart IS NULL OR date_arriver IS NULL THEN NULL
										WHEN vp.penalite_en_secondes IS NOT NULL THEN  TIMESTAMPDIFF(SECOND, date_depart, date_arriver) + vp.penalite_en_secondes
										ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
									END AS temps_total
								FROM race.etape_coureur etp
								JOIN race.categorie_coureur cc ON etp.id_coureur = cc.id_coureur
								JOIN race.coureur c on etp.id_coureur = c.id
								JOIN race.equipe_coureur eqc ON c.id = eqc.id_coureur
								JOIN race.utilisateur u ON eqc.id_equipe = u.id
								LEFT JOIN v_penalite vp on vp.id_equipe = eqc.id_equipe AND vp.id_etape = etp.id_etape
								ORDER BY id_etape,cc.id_categorie, temps_total
							) ec,
							(SELECT @current_rank := 0, @prev_rank := 0, @prev_time := NULL, @currentEtape := NULL, @currentCategorie := NULL) r
						) ec
						LEFT JOIN race.points p ON ec.rank = p.classement
						ON DUPLICATE KEY UPDATE rang = VALUES(rang), point = VALUES(point);
				




SELECT 
	etp.id_etape,
	etp.id_coureur,
	CASE 
		WHEN date_depart IS NULL OR date_arriver IS NULL THEN NULL
		WHEN vp.penalite_en_secondes IS NOT NULL THEN  COALESCE(TIMESTAMPDIFF(SECOND, date_depart, date_arriver) + vp.penalite_en_secondes,'0')
		ELSE COALESCE(TIMESTAMPDIFF(SECOND, date_depart, date_arriver),'0')
	END AS temps_total,
	u.id AS id_equipe, 
	vp.penalite_en_secondes
    
	FROM race.etape_coureur etp
	JOIN race.coureur c on etp.id_coureur = c.id
	JOIN race.equipe_coureur eqc ON c.id = eqc.id_coureur
	JOIN race.utilisateur u ON eqc.id_equipe = u.id
	LEFT JOIN v_penalite vp on vp.id_equipe = eqc.id_equipe AND vp.id_etape = etp.id_etape
	ORDER BY id_etape, temps_total


--------------------------------------------------------------
SELECT 
    nouveau.id_etape, 
    nouveau.nom, 
    nouveau.genre, 
    nouveau.temps_total, 
    nouveau.penalite_en_secondes, 
    COALESCE((nouveau.temps_total - nouveau.penalite_en_secondes), nouveau.temps_total) AS temps_initial,
    @current_rank := IF(@currentEtape = nouveau.id_etape AND @prev_time = nouveau.temps_total, @current_rank, @rank := @rank + 1) AS rang,
    @prev_time := nouveau.temps_total,
    @currentEtape := nouveau.id_etape
FROM (
    SELECT 
        etp.id_etape,
        etp.id_coureur,
        c.nom,
        c.genre,
        CASE 
            WHEN date_depart IS NULL OR date_arriver IS NULL THEN NULL
            WHEN vp.penalite_en_secondes IS NOT NULL THEN COALESCE(TIMESTAMPDIFF(SECOND, date_depart, date_arriver) + vp.penalite_en_secondes, '0')
            ELSE COALESCE(TIMESTAMPDIFF(SECOND, date_depart, date_arriver), '0')
        END AS temps_total,
        u.id AS id_equipe, 
        vp.penalite_en_secondes
    FROM race.etape_coureur etp
    JOIN race.coureur c ON etp.id_coureur = c.id
    JOIN race.equipe_coureur eqc ON c.id = eqc.id_coureur
    JOIN race.utilisateur u ON eqc.id_equipe = u.id
    LEFT JOIN v_penalite vp ON vp.id_equipe = eqc.id_equipe AND vp.id_etape = etp.id_etape
    ORDER BY etp.id_etape, temps_total
) AS nouveau
JOIN (SELECT @rank := 0, @current_rank := 0, @prev_time := NULL, @currentEtape := NULL) r
ORDER BY nouveau.id_etape, nouveau.temps_total;



-- vraaaaaa j4
SELECT 
    nouveau.id_etape, 
    nouveau.nom, 
    nouveau.genre, 
    nouveau.temps_total, 
    nouveau.penalite_en_secondes, 
    COALESCE((nouveau.temps_total - nouveau.penalite_en_secondes), nouveau.temps_total) AS temps_initial,
    @current_rank := IF(@currentEtape = nouveau.id_etape AND @prev_time = nouveau.temps_total, @current_rank, @rank := @rank + 1) AS rang,
    @prev_time := nouveau.temps_total,
    @currentEtape := nouveau.id_etape
FROM (
    SELECT 
        etp.id_etape,
        etp.id_coureur,
        c.nom,
        c.genre,
        CASE 
            WHEN date_depart IS NULL OR date_arriver IS NULL THEN NULL
            WHEN vp.penalite_en_secondes IS NOT NULL THEN COALESCE(TIMESTAMPDIFF(SECOND, date_depart, date_arriver) + vp.penalite_en_secondes, '0')
            ELSE COALESCE(TIMESTAMPDIFF(SECOND, date_depart, date_arriver), '0')
        END AS temps_total,
        u.id AS id_equipe, 
        vp.penalite_en_secondes
    FROM race.etape_coureur etp
    JOIN race.coureur c ON etp.id_coureur = c.id
    JOIN race.equipe_coureur eqc ON c.id = eqc.id_coureur
    JOIN race.utilisateur u ON eqc.id_equipe = u.id
    LEFT JOIN v_penalite vp ON vp.id_equipe = eqc.id_equipe AND vp.id_etape = etp.id_etape
    ORDER BY etp.id_etape, temps_total
) AS nouveau

JOIN (SELECT @rank := 0, @current_rank := 0, @prev_time := NULL, @currentEtape := NULL) r
WHERE nouveau.id_etape = 1
ORDER BY nouveau.id_etape, nouveau.temps_total;

------------------------------ categorie avec ran=ng up 
SELECT 
    nv.id_equipe, 
    nv.nom_equipe, 
    nv.id_categorie, 
    nv.nom_categorie, 
    nv.points_totaux,
    @rank := IF(@current_category = nv.id_categorie, 
                IF(@prev_points = nv.points_totaux, @rank, @rank_counter), 
                1) AS rang,
    @rank_counter := IF(@current_category = nv.id_categorie, @rank_counter + 1, 2),
    @prev_points := nv.points_totaux,
    @current_category := nv.id_categorie
FROM (
    SELECT 
        e.id AS id_equipe,
        e.nom AS nom_equipe,
        c.id AS id_categorie,
        c.nom AS nom_categorie,
        COALESCE(SUM(rc.point), 0) AS points_totaux
    FROM 
        race.utilisateur e
    CROSS JOIN 
        race.categorie c
    LEFT JOIN 
        race.equipe_coureur ec ON e.id = ec.id_equipe
    LEFT JOIN 
        race.coureur cou ON ec.id_coureur = cou.id
    LEFT JOIN 
        race.resultat_categorie rc ON cou.id = rc.id_coureur AND rc.id_categorie = c.id
    WHERE 
        e.type_utilisateur != 1 
    GROUP BY 
        e.id, c.id
    ORDER BY 
        c.id, points_totaux DESC
) as nv,
(SELECT @rank := 0, @rank_counter := 1, @prev_points := NULL, @current_category := NULL) r
ORDER BY nv.id_categorie, nv.points_totaux DESC;
