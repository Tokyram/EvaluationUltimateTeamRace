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
		
		public function calculate_and_insert_results() {

			
			$this->db->truncate('resultat');
			$this->db->query("ALTER TABLE resultat AUTO_INCREMENT = 1");
			$sql = "
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
			";
			$this->db->query($sql);
		}

		public function points_totaux_par_equipe_par_etape(){
			$sql = "SELECT * FROM race.points_totaux_par_equipe_par_etape";
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
		

		public function resetDatabase(){
			$this->load->database();
	
			$this->db->query('SET FOREIGN_KEY_CHECKS = 0');

			$this->db->truncate('etapes');

			$this->db->truncate('coureur');

			$this->db->truncate('categorie');

			$this->db->truncate('etape_coureur');

			$this->db->truncate('equipe_coureur');
			$this->db->truncate('categorie_coureur');

			$this->db->truncate('resultat');

			$this->db->where('type_utilisateur !=', '1');

			$this->db->delete('utilisateur');

			// Réactiver les contraintes de clé étrangère
			$this->db->query('SET FOREIGN_KEY_CHECKS = 1');
		}
    }

?>