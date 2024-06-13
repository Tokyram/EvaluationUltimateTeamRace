<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

    class ModelGeneral extends CI_Model{
        public function login($input,$table) {
			if(!empty($input["nom"]) || !empty($input["mdp"])){
				$sql = "SELECT * FROM %s WHERE nom = '%s' AND mdp = '%s'";
				$sql = sprintf($sql, $table, $input["nom"], $input["mdp"]);
				$query = $this->db->query($sql);
				$row = $query->row_array();
				return $row;
			}
			return null;
		}


		public function save($table, $data){
			$sql = $this->db->insert($table,$data);
			$query = $this->db->insert_id();
			return $query;
		}

		public function findAll($table, $colonne){
			$sql = "SELECT * FROM %s order by %s asc";
			$sql = sprintf($sql, $table, $colonne);
			return $this->db->query($sql)->result();
		}

		public function findAllbytype($table, $colonne, $condition = null) {
			$sql = "SELECT * FROM %s";
			if ($condition) {
				$sql .= " WHERE %s";
			}
			$sql .= " ORDER BY %s ASC";
			
			if ($condition) {
				$sql = sprintf($sql, $table, $condition, $colonne);
			} else {
				$sql = sprintf($sql, $table, $colonne);
			}
		
			return $this->db->query($sql)->result();
		}
		

		public function findAll4($table, $colonne, $colonne1, $colonne2, $colonne3){
			$sql = "SELECT * FROM %s order by %s desc";
			$sql = sprintf($sql, $table, $colonne,$colonne1, $colonne2, $colonne3);
			return $this->db->query($sql)->result();
		}

		public function get_where_equipe($table, $colone){
			$sql = "SELECT * FROM %s WHERE id = '%d'";
			$sql = sprintf($sql, $table, $colone);
			return $this->db->query($sql)->result();
		}

		public function get_where_equipe_coureur($id_equipe){
			$sql = "SELECT * from coureur 
						JOIN equipe_coureur ON equipe_coureur.id_coureur = coureur.id
						where equipe_coureur.id_equipe = $id_equipe";
			return $this->db->query($sql)->result();
		}

		public function get_where_equipe_coureur_categorie($id_equipe) {
			$sql = "SELECT c.*, GROUP_CONCAT(cc.id_categorie) as categories,
							GROUP_CONCAT(cat.nom) as nom_categories
					FROM coureur c
					JOIN equipe_coureur ec ON ec.id_coureur = c.id
					LEFT JOIN categorie_coureur cc ON cc.id_coureur = c.id
					LEFT JOIN categorie cat ON cat.id = cc.id_categorie
					WHERE ec.id_equipe = $id_equipe
					GROUP BY c.id";
			return $this->db->query($sql)->result();
		}
		

		public function get_where_etape_coureur($id_etape){
			$sql = "SELECT * from coureur 
						JOIN etape_coureur ON etape_coureur.id_coureur = coureur.id
						where etape_coureur.id_etape = $id_etape";
			return $this->db->query($sql)->result();
		}
		
		public function update($table, $colone_id, $id, $data){
			$this->db->where($colone_id, $id);
			if($this->db->update($table, $data)) return true;
			else return false;
		}

		public function update2($table, $colone_id, $colone_id2, $id, $id2, $data) {
			// Utiliser where avec un tableau associatif
			$this->db->where(array($colone_id => $id, $colone_id2 => $id2));
		
			if ($this->db->update($table, $data)) {
				return true;
			} else {
				return false;
			}
		}
		public function delete($table,$colone_id,$id){
			$this->db->where($colone_id, $id);
			if($this->db->delete($table)) return true;
			else return false;
		}
		public function calculate_and_insert_results() {

			
			$this->db->truncate('resultat');
			$this->db->query("ALTER TABLE resultat AUTO_INCREMENT = 1");
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
							WHEN p.points IS NULL THEN 0 
							ELSE p.points
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
								etp.id_etape,
								etp.id_coureur,
								CASE 
									WHEN date_depart IS NULL OR date_arriver IS NULL THEN NULL
									WHEN vp.penalite_en_secondes IS NOT NULL THEN  TIMESTAMPDIFF(SECOND, date_depart, date_arriver) + vp.penalite_en_secondes
									ELSE TIMESTAMPDIFF(SECOND, date_depart, date_arriver)
								END AS temps_total
							FROM race.etape_coureur etp
							JOIN race.coureur c on etp.id_coureur = c.id
							JOIN race.equipe_coureur eqc ON c.id = eqc.id_coureur
							JOIN race.utilisateur u ON eqc.id_equipe = u.id
							LEFT JOIN v_penalite vp on vp.id_equipe = eqc.id_equipe AND vp.id_etape = etp.id_etape
							ORDER BY id_etape, temps_total
						) ec
					) ec
					LEFT JOIN race.points p ON ec.rank = p.classement
					ON DUPLICATE KEY UPDATE rang = VALUES(rang), point = VALUES(point)
			
					";
			$this->db->query($sql);
		}

		public function points_totaux_par_equipe_par_etape(){
			$sql = "SELECT * FROM race.points_totaux_par_equipe_par_etape";
			return $this->db->query($sql)->result();
		}

		public function points_totaux_par_equipe_par_etape_equipe($equipe){
			$sql = "SELECT * FROM race.points_totaux_par_equipe_par_etape
					where nom_equipe = '$equipe'
					order by id_etape asc
					";
			return $this->db->query($sql)->result();
		}

		public function classement_general_par_equipe_avec_rang(){
			$sql="SELECT
						id_equipe,
						nom_equipe,
						points_totaux,
						@rank := @rank + 1 AS rang
					FROM
						(SELECT 
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
							points_totaux DESC) AS classement,
						(SELECT @rank := 0) r;
						";
			return $this->db->query($sql)->result();
		}
		
		public function classement_general_par_equipe_gagnant(){
			$sql="SELECT
						id_equipe,
						nom_equipe,
						points_totaux,
						@rank := @rank + 1 AS points_totauxpoints_totaux
					FROM
						(SELECT 
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
							points_totaux DESC) AS classement,
						(SELECT @rank := 0) r
						where @rank + 1 = 1
						";
			return $this->db->query($sql)->result();
		}
		

		public function resetDatabase(){
			$this->load->database();
	
			$this->db->query('SET FOREIGN_KEY_CHECKS = 0');

			$this->db->truncate('etape');

			$this->db->truncate('coureur');

			$this->db->truncate('categorie');

			$this->db->truncate('etape_coureur');

			$this->db->truncate('equipe_coureur');
			$this->db->truncate('categorie_coureur');

			$this->db->truncate('resultat');
			$this->db->truncate('resultat_categorie');
			$this->db->truncate('points');
			$this->db->truncate('penalite');


			$this->db->where('type_utilisateur !=', '1');
			$this->db->delete('utilisateur');

			$this->db->query("ALTER TABLE utilisateur AUTO_INCREMENT = 1");

			$this->db->query('SET FOREIGN_KEY_CHECKS = 1');
		}

		// public function get_nb_coureur( $id_etape){
		// 	$sql ="SELECT nb_coureur from etape where id= $id_etape";
		// 	return $this->db->query($sql)->result();
		// }

		public function get_nb_coureur($id_etape) {
			$this->db->select('nb_coureur');
			$this->db->from('etape');
			$this->db->where('id', $id_etape);
			$query = $this->db->get();
			return $query->row()->nb_coureur;
		}

		public function count_coureurs_par_equipe($id_etape, $id_equipe) {
			$this->db->select('COUNT(*) as nb_coureurs');
			$this->db->from('etape_coureur ec');
			$this->db->join('equipe_coureur eq', 'ec.id_coureur = eq.id_coureur');
			$this->db->where('ec.id_etape', $id_etape);
			$this->db->where('eq.id_equipe', $id_equipe);
			$query = $this->db->get();
			return $query->row()->nb_coureurs;
		}

		public function get_id_equipe_by_coureur($id){
			$this->db->select('id_equipe');
			$this->db->from('equipe_coureur');
			$this->db->where('id_coureur', $id);
			$query = $this->db->get();
			return $query->row()->id_equipe;
		}

		public function does_etape_exist($id_etape) {
			$query = $this->db->get_where('etape', array('id' => $id_etape));
			return $query->num_rows() > 0;
		}
	
		// Vérifie si l'ID du coureur existe
		public function does_coureur_exist($numero_dossard) {
			$query = $this->db->get_where('coureur', array('numero_dossard' => $numero_dossard));
			return $query->num_rows() > 0 ? $query->row()->id : false;
		}
	
		// Récupère l'ID de l'étape par son rang
		public function get_etape_id_by_rang($rang) {
			$query = $this->db->get_where('etape', array('rang_etape' => $rang));
			return $query->num_rows() > 0 ? $query->row()->id : false;
		}
	
		// Récupère la date de départ de l'étape
		public function get_date_depart($id_etape) {
			$this->db->select('date_depart');
			$this->db->from('etape');
			$this->db->where('id', $id_etape);
			$query = $this->db->get();
	
			if ($query->num_rows() > 0) {
				return $query->row()->date_depart;
			} else {
				return null;
			}
		}

		public function does_equipe_exist($nom_equipe) {
			$this->db->where('nom', $nom_equipe);
			$query = $this->db->get('utilisateur');
			
			if ($query->num_rows() > 0) {
				// L'équipe existe déjà, renvoyer son ID
				$row = $query->row();
				return $row->id;
			} else {
				// L'équipe n'existe pas
				return false;
			}
		}
		
		public function get_points_by_classement($classement) {
			// Récupérer les points pour un classement donné
			$query = $this->db->get_where('points', array('classement' => $classement));
			return $query->row_array();
		}
	
		public function insert_points($classement, $points) {
			// Insérer de nouveaux points pour un classement donné
			$data = array(
				'classement' => $classement,
				'points' => $points
			);
			$this->db->insert('points', $data);
		}
	
		public function update_points($classement, $points) {
			// Mettre à jour les points pour un classement donné
			$data = array(
				'points' => $points
			);
			$this->db->where('classement', $classement);
			$this->db->update('points', $data);
		}


		// coureur avec temps chrono
		public function get_coureurs_with_temps_by_equipe_and_etape($id_equipe, $id_etape) {
			$sql = "
					SELECT 
					ec.id_etape,
					ec.id_coureur,
					c.nom AS nom_coureur,
					TIMESTAMPDIFF(SECOND, ec.date_depart, ec.date_arriver) AS temps_total,
					(
						SELECT COUNT(*)
						FROM race.etape_coureur inner_ec
						JOIN race.equipe_coureur inner_eqc ON inner_ec.id_coureur = inner_eqc.id_coureur
						WHERE inner_eqc.id_equipe = eqc.id_equipe AND inner_ec.id_etape = ec.id_etape
					) AS coureurs_count
				FROM 
					race.etape_coureur ec
				JOIN
					race.equipe_coureur eqc ON ec.id_coureur = eqc.id_coureur
				JOIN
					race.coureur c ON ec.id_coureur = c.id
				WHERE 
					eqc.id_equipe = ? AND ec.id_etape = ?
				ORDER BY 
					ec.id_etape, ec.id_coureur;
		
			";
	
			$query = $this->db->query($sql, array($id_equipe, $id_etape));
	
			return $query->result_array();
		}

		public function get_coureurs_count_by_equipe_and_etape($id_equipe, $id_etape) {
			$sql = "
				SELECT 
					COUNT(ec.id_coureur) AS coureurs_count
				FROM 
					race.etape_coureur ec
				JOIN
					race.equipe_coureur eqc ON ec.id_coureur = eqc.id_coureur
				WHERE 
					eqc.id_equipe = ? AND ec.id_etape = ?
			";
		
			$query = $this->db->query($sql, array($id_equipe, $id_etape));
			return $query->row()->coureurs_count;
		}

		public function add_coureur_to_equipe_and_etape($id_equipe, $id_etape, $coureur_data) {
			$this->db->where('numero_dossard', $coureur_data['numero_dossard']);
			$query = $this->db->get('coureur');
			
			if ($query->num_rows() == 0) {
				$this->db->insert('coureur', $coureur_data);
				$id_coureur = $this->db->insert_id();
			} else {
				$row = $query->row();
				$id_coureur = $row->id;
			}
	
			$this->db->where('id_equipe', $id_equipe);
			$this->db->where('id_coureur', $id_coureur);
			$query = $this->db->get('equipe_coureur');
	
			if ($query->num_rows() == 0) {
				$equipe_coureur_data = array(
					'id_equipe' => $id_equipe,
					'id_coureur' => $id_coureur
				);
				$this->db->insert('equipe_coureur', $equipe_coureur_data);
			}
	
			$this->db->where('id_etape', $id_etape);
			$this->db->where('id_coureur', $id_coureur);
			$query = $this->db->get('etape_coureur');
	
			if ($query->num_rows() == 0) {
				
				$etape_coureur_data = array(
					'id_etape' => $id_etape,
					'id_coureur' => $id_coureur,
					'date_depart' => null,  
					'date_arriver' => null  
				);
				$this->db->insert('etape_coureur', $etape_coureur_data);
			}
		}
		
		public function generer_categories() {
			$coureurs = $this->db->get('coureur')->result_array();
			
			foreach ($coureurs as $coureur) {
				$date_naissance = new DateTime($coureur['date_naissance']);
				$aujourdhui = new DateTime();
				$age = $date_naissance->diff($aujourdhui)->y;
		
				$categories = array();
				if ($coureur['genre'] == 'M') {
					$categories[] = 'Homme';
				} elseif ($coureur['genre'] == 'F') {
					$categories[] = 'Femme';
				}
				if ($age < 18) {
					$categories[] = 'Junior';
				}
				// if ($age >= 18) {
				// 	$categories[] = 'Senior';
				// }
		
				foreach ($categories as $categorie) {
					// Vérifie si la catégorie existe dans la table 'categorie'
					$categorie_row = $this->db->get_where('categorie', array('nom' => $categorie))->row();
					if ($categorie_row) {
						// Si la catégorie existe, insérez-la dans la table 'categorie_coureur'
						$this->db->insert('categorie_coureur', array(
							'id_categorie' => $categorie_row->id,
							'id_coureur' => $coureur['id']
						));
					} else {
						// Si la catégorie n'existe pas, ajoutez-la d'abord à la table 'categorie' puis insérez-la dans 'categorie_coureur'
						$this->db->insert('categorie', array('nom' => $categorie));
						$categorie_id = $this->db->insert_id(); // Récupère l'ID de la nouvelle catégorie
						$this->db->insert('categorie_coureur', array(
							'id_categorie' => $categorie_id,
							'id_coureur' => $coureur['id']
						));
					}
				}
			}
		}
		
		public function get_points_par_equipe_et_categorie() {
			$sql = "
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
					c.id, points_totaux DESC;
			";
			return $this->db->query($sql)->result_array();
		}
		
		
		
		public function calculate_and_insert_results_categorie() {

			
			$this->db->truncate('resultat_categorie');
			$this->db->query("ALTER TABLE resultat_categorie AUTO_INCREMENT = 1");
			$this->db->query('SET @current_rank := 0, @prev_rank := 0, @prev_time := -1, @currentEtape := 0');

			$sql = "
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
						
					";
			$this->db->query($sql);
		}
		public function penalite_equipe() {
			$sql = "SELECT p.id as id_penalite, u.nom as nom_equipe , e.nom as nom_etape, temps_penalite_equipe
							FROM penalite p
							JOIN utilisateur u ON p.id_equipe = u.id
							JOIN etape e ON p.id_etape = e.id
			";
			return $this->db->query($sql)->result_array();
		}

	
		public function coureur_etape_temps_penalite($id_etape){
			$sql = "SELECT 
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
				WHERE nouveau.id_etape = $id_etape
				ORDER BY nouveau.id_etape, nouveau.temps_total ";

				return $this->db->query($sql)->result_array();
		}

    }

?>