CREATE SCHEMA race;

CREATE  TABLE race.penalite ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_etape             INT       ,
	id_equipe            INT       ,
	temps_penalite_equipe TIME       
 ) engine=InnoDB;

CREATE INDEX fk_penalite_etape ON race.penalite ( id_etape );

CREATE INDEX fk_penalite_utilisateur ON race.penalite ( id_equipe );

public function import_penalite_csv() {
    if (isset($_FILES['csv_file_2']['tmp_name'])) {
        $file = $_FILES['csv_file_2']['tmp_name'];

        $handle = fopen($file, "r");
        if ($handle !== FALSE) {
            fgetcsv($handle, 1000, ",");

            // Parcourir chaque ligne du fichier CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Vérifier si l'identifiant de l'étape et de l'équipe existent
                $id_etape = $data[0]; // Supposons que la première colonne contienne l'identifiant de l'étape
                $id_equipe = $data[1]; // Supposons que la deuxième colonne contienne l'identifiant de l'équipe

                // Vérifier si l'identifiant de l'étape existe
                $this->db->where('id', $id_etape);
                $query_etape = $this->db->get('etape');
                if ($query_etape->num_rows() == 0) {
                    echo "L'identifiant de l'étape $id_etape n'existe pas.<br>";
                    continue; // Passer à la prochaine ligne
                }

                // Vérifier si l'identifiant de l'équipe existe
                $this->db->where('id', $id_equipe);
                $query_equipe = $this->db->get('utilisateur');
                if ($query_equipe->num_rows() == 0) {
                    echo "L'identifiant de l'équipe $id_equipe n'existe pas.<br>";
                    continue; // Passer à la prochaine ligne
                }

                // Préparer les données pour la table 'penalite'
                $penalite = array(
                    'id_etape' => $id_etape,
                    'id_equipe' => $id_equipe,
                    'temps_penalite_equipe' => $data[2] // Supposons que la troisième colonne contienne le temps de pénalité
                );

                // Insérer les données dans la table 'penalite'
                $this->db->insert('penalite', $penalite);
            }
            fclose($handle);
            echo "Importation réussie!";
        } else {
            echo "Erreur lors de l'ouverture du fichier.";
        }
    } else {
        echo "Aucun fichier sélectionné.";
    }
}
