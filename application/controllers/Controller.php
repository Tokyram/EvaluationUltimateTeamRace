<?php
defined('BASEPATH') OR exit('No direct script access allowed');	

class Controller extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
		date_default_timezone_set("Indian/Antananarivo");
		// parent::__construct();
        // $this->load->library('pdf');
        $this->load->library('session');
		// $data = $this->model->getDateNow();
		// session_start();	
		// $_SESSION['dateparking'] = $data;
	}
	
	public function home()
	{

		$data['page'] = "home";
		$data['points_totaux_par_equipe_par_etape'] = $this->model->points_totaux_par_equipe_par_etape();
		$data['classement_general_par_equipe_avec_rang'] = $this->model->classement_general_par_equipe_avec_rang();
		$this->load->view('header', $data);
	}
	public function login()
	{
		$this->load->view('login');
	}
	public function register()
	{
		$this->load->view('register');
	}
	public function form()
	{
		$id = $this->input->get('id');
		
		$user = $this->session->userdata('utilisateur');
			$user2 = $this->session->userdata('administrateur');
			
			if ($user) {
				$id_user = $user['id'];
			} elseif ($user2) {
				$id_user = $user2['id'];
			} else {
				echo "Aucun utilisateur connecté.";
				return;
			}

		$data['page'] = "forms";
		$data['etape'] = $id;
		$data['user'] = $id_user;
		$data['get_coureurs_with_temps_by_equipe_and_etape'] = $this->model->get_coureurs_with_temps_by_equipe_and_etape($id_user, $id);
		// $data['get_coureurs_count_by_equipe_and_etape'] = $this->model->get_coureurs_count_by_equipe_and_etape($id_user, $id);
		$data['coureur'] = $this->model->findAll('coureur', 'nom');
		$data['equipe_coureur'] = $this->model->get_where_equipe_coureur($id_user);
		$data['etape_coureur'] = $this->model->get_where_etape_coureur($id);
		$this->load->view('header',$data);
	}

	public function insert_etape_coureur(){

		$id = $this->input->get('id');

		$user = $this->session->userdata('utilisateur');
		$user2 = $this->session->userdata('administrateur');
		
		if ($user) {
			$id_user = $user['id'];
		} elseif ($user2) {
			$id_user = $user2['id'];
		} else {
			echo "Aucun utilisateur connecté.";
			return;
		}

		$input = $this->input->post();

		$id_equipe = $this->model->get_id_equipe_by_coureur($input['coureur']);
    
		$nb_coureurs_actuels = $this->model->count_coureurs_par_equipe($input['etape'], $id_equipe);
		
		$nb_coureur_max = $this->model->get_nb_coureur($input['etape']);

		// var_dump(intval($nb_coureur_max), intval($nb_coureurs_actuels));
		
		if (intval($nb_coureurs_actuels) >= intval($nb_coureur_max)) {

			$data['erreur'] = "Le nombre de coureurs pour cette équipe dans cette étape a déjà atteint la limite autorisée.";
			$data['etape'] = $input['etape'];
			$data['equipe_coureur'] = $this->model->get_where_equipe_coureur($id_user);
			$data['page'] = "forms";
			$data['get_coureurs_with_temps_by_equipe_and_etape'] = $this->model->get_coureurs_with_temps_by_equipe_and_etape($id_user,  $input['etape']);

			$this->load->view('header', $data);   
			return;
		}
		
		$data = array(
			"id_etape" => $input['etape'],
			"id_coureur" => $input['coureur'],
		);

		$this->model->save('etape_coureur',$data);
		

		redirect(base_url('controller/form?id='.$input['etape']));

	}

	public function insert_temps_coureur() {

		

		$input = $this->input->post();
		
		$date_depart = date("Y-m-d H:i:s", strtotime($input['depart']));
		$date_arriver = date("Y-m-d H:i:s", strtotime($input['arriver']));
		
		// Valider si la date d'arrivée est après la date de départ
		if ($date_arriver < $date_depart) {
			$data['erreur'] = "La date d'arrivée ne peut pas être antérieure à la date de départ.";
			$data['etape'] = $input['etape'];
			$data['page'] = "forms";
			$this->load->view('header', $data);  
			return;
		}
		
		$data = array(
			"date_depart" => $date_depart,
			"date_arriver" => $date_arriver
		);
		
		// Mettre à jour les données de l'étape du coureur
		$this->model->update2('etape_coureur', 'id_etape', 'id_coureur', $input['etape'], $input['coureur'], $data);
		
		// Calculer et insérer les résultats
		$this->model->calculate_and_insert_results();
		
		// Rechargement des données nécessaires pour la vue
		// Redirection vers la vue
		redirect(base_url('controller/form?id=' . $input['etape']));
	}
	
	
	
	

	public function calendar()
	{
		$data['page'] = "calendar";
		$this->load->view('header',$data);
		// echo ("coucou");
	}	
	public function profil()
	{
		$user = $this->session->userdata('utilisateur');
		$user2 = $this->session->userdata('administrateur');
		
		if ($user) {
			$id_user = $user['id'];
		} elseif ($user2) {
			$id_user = $user2['id'];
		} else {
			echo "Aucun utilisateur connecté.";
			return;
		}
		$data['equipe_coureur'] = $this->model->get_where_equipe_coureur($id_user);
		$data['equipe_coureur_categorie'] = $this->model->get_where_equipe_coureur_categorie($id_user);

		$data['page'] = "profile";
		$data['etapes'] = $this->model->findAll4('etape', 'nom','longueur', 'nb_coureur','rang_etape');
		$this->load->view('header', $data);
		// echo ("coucou");
	}
	public function table()
	{
		$data['page'] = "tables";
		$this->load->view('header', $data);
		// echo ("coucou");
	}
	
	public function liste()
	{
		
		$data['page'] = "cards";

		$data['etapes'] = $this->model->findAll4('etape', 'nom','longueur', 'nb_coureur','rang_etape');
		$this->load->view('header', $data);
		
	}

	public function details()
	{
		$data['page'] = "details";
		$this->load->view('header', $data);
	}

	

	public function validerLogin() {
		$this->form_validation->set_rules('nom', 'Nom d utilisateur', 'required');
		$this->form_validation->set_rules('mdp', 'Mot de passe', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			// Si la validation échoue, renvoyer les erreurs de validation
			$this->load->view('login');
		} else {
			$inputs = $this->input->post();
			try {
				$login = $this->model->login($inputs, 'utilisateur');
	
				if ($login != null) {
					if ($login['type_utilisateur'] == 1) {
						$_SESSION['administrateur'] = $login;
						$this->liste();
					} else {
						$_SESSION['utilisateur'] = $login;
						$this->profil();
					}
				} else {
					// Nom d'utilisateur ou mot de passe incorrect
					$data['erreur'] = "Vérifiez votre nom d'utilisateur et mot de passe!!";
					$this->load->view('login', $data);
				}
			} catch(Exception $ex) {
				$data['erreur'] = $ex->getMessage();
				$this->load->view('login', $data);
			}
		}
	}
	
	

	public function inscription_utilisateur(){
		$input = $this->input->post();

		$this->form_validation->set_rules('nom', 'Nom d utilisateur', 'required');
        $this->form_validation->set_rules('mdp', 'Mot de passe', 'required');
        $this->form_validation->set_rules('mdp1', 'Validation mot de passe', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');

		if($this->form_validation->run() == FALSE) {
			
			$this->load->view('register');
		}
		elseif($input['mdp'] != $input['mdp1']){
			$data['erreur'] = "Le mot de passe doit être confirmé!!";
            $data['page'] = "register";
			$this->load->view('header',$data);
		}
		else {
			$pers = array("nom" => $input['nom'],"email" => $input['email'],"mdp" => $input['mdp'],"type_utilisateur" => 0);
			$p = $this->model->save('utilisateur',$pers);
            // $data['page'] = "login";
			$this->load->view('login');
		}
	}

	

	public function deconnexion() {
        session_destroy();
        $this->load->view('login');
    }

	public function resetData(){
		$this->model->resetDatabase();

		redirect(base_url('controller/liste'));
	}

	public function importation_csv_etape_resultat(){
		$date['page']='forms_importation_etapes_resultats';
		$this->load->view('header', $date);
	}

	public function importation_csv_point(){
		$date['page']='forms_importation_points';
		$this->load->view('header', $date);
	}
	
	public function forms_importation(){
		$date['page']='forms_importation';
		$this->load->view('header', $date);
	}

	public function importation_csv(){

		if (!empty($_FILES['csv_file_1']['name'])) {
			$this->import_etape_csv();
		} else {
			echo "Veuillez sélectionner le fichier CSV pour l'etapes et resultat.";
		}
	
		// Vérifiez si le fichier CSV pour les devis a été soumis
		if (!empty($_FILES['csv_file_2']['name'])) {
			// Appel de la fonction pour traiter le deuxième fichier CSV
			$this->import_resultat_csv();
		} else {
			echo "Veuillez sélectionner le fichier CSV pour les points.";
		}

		redirect(base_url('controller/importation_csv_etape_resultat'));
	}

	public function import_etape_csv() {
		if (isset($_FILES['csv_file_1']['tmp_name'])) {
			$file = $_FILES['csv_file_1']['tmp_name'];
	
			$handle = fopen($file, "r");
			if ($handle !== FALSE) {
				fgetcsv($handle, 1000, ",");
				
				// Parcourir chaque ligne du fichier CSV
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					// Préparer les données pour la table 'etape'
					$etape = array(
						'nom' => $data[0],
						'longueur' => str_replace(",", ".", $data[1]),
						'nb_coureur' => $data[2],
						'rang_etape' => $data[3],
						'date_depart' => $data[4] . ' ' . $data[5] // Ajout de la date de départ
					);
	
					// Convertir et assembler la date et l'heure de départ
					$date_depart = DateTime::createFromFormat('d/m/Y H:i:s', $data[4] . ' ' . $data[5]);
					$date_depart_formatted = $date_depart->format('Y-m-d H:i:s');
					$etape['date_depart'] = $date_depart_formatted;
	
					// Insérer les données dans la table 'etape'
					$this->db->insert('etape', $etape);
					$id_etape = $this->db->insert_id();
	
					// Préparer les données pour la table 'etape_coureur'
					for ($i = 0; $i < $etape['nb_coureur']; $i++) {
						$etape_coureur = array(
							'id_etape' => $id_etape,
							'id_coureur' => null, // Assurez-vous de gérer ou d'obtenir l'id_coureur approprié
							'date_depart' => $date_depart_formatted,
							'date_arriver' => null
						);
	
						// Utiliser ON DUPLICATE KEY UPDATE pour mettre à jour les colonnes qui changent
						$sql = $this->db->insert_string('etape_coureur', $etape_coureur) . 
							   ' ON DUPLICATE KEY UPDATE date_depart = VALUES(date_depart), date_arriver = VALUES(date_arriver)';
						$this->db->query($sql);
					}
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
	
	
	public function import_resultat_csv() {
		if (isset($_FILES['csv_file_2']['tmp_name'])) {
			$file = $_FILES['csv_file_2']['tmp_name'];
	
			// Ouvrir le fichier CSV
			$handle = fopen($file, "r");
			if ($handle !== FALSE) {
				// Effacer toutes les données de la table etape_coureur
				$this->db->truncate('etape_coureur');
	
				// Lire les en-têtes
				fgetcsv($handle, 1000, ",");
				
				// Parcourir chaque ligne du fichier CSV
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					// Vérifier que l'équipe existe
					$id_equipe = $this->model->does_equipe_exist($data[5]);
					if (!$id_equipe) {
						// Si l'équipe n'existe pas, insérez-la dans la table utilisateur
						$equipe_data = array(
							'nom' => $data[5],
							'login' => $data[5], // Vous pouvez définir le login par défaut comme le nom de l'équipe
							'mdp' => '123', // Mot de passe vide par défaut, à modifier selon vos besoins
							'type_utilisateur' => 0 // Type utilisateur pour l'équipe (vous pouvez ajuster selon votre système)
						);
						$this->db->insert('utilisateur', $equipe_data);
						$id_equipe = $this->db->insert_id();
					}
	
					// Récupérer l'ID de l'étape à partir de son rang
					$id_etape = $this->model->get_etape_id_by_rang($data[0]);
					if (!$id_etape) {
						// Si l'étape n'existe pas, insérez-la
						$etape_data = array(
							'nom' => $data[0],
							'longueur' => null, // Vous pouvez définir la longueur ou d'autres valeurs par défaut ici
							'nb_coureur' => null,
							'rang_etape' => $data[0]
						);
						$this->db->insert('etape', $etape_data);
						$id_etape = $this->db->insert_id();
					}
	
					// Vérifier que le coureur existe
					$id_coureur = $this->model->does_coureur_exist($data[1]);
					if (!$id_coureur) {
						// Si le coureur n'existe pas, insérez-le
						$coureur_data = array(
							'nom' => $data[2],
							'numero_dossard' => $data[1],
							'genre' => $data[3],
							'date_naissance' => DateTime::createFromFormat('d/m/Y', $data[4])->format('Y-m-d')
						);
						$this->db->insert('coureur', $coureur_data);
						$id_coureur = $this->db->insert_id();
					}
	
					// Vérifier si l'enregistrement existe déjà dans etape_coureur
					$this->db->where('id_equipe', $id_equipe);
					$this->db->where('id_coureur', $id_coureur);
					$query = $this->db->get('equipe_coureur');
	
					if ($query->num_rows() == 0) {
						// Insertion de l'entrée dans equipe_coureur si elle n'existe pas
						$equipe_coureur_data = array(
							'id_equipe' => $id_equipe,
							'id_coureur' => $id_coureur
						);
						$this->db->insert('equipe_coureur', $equipe_coureur_data);
					}
	
					// Convertir la date d'arrivée
					$date_arriver = DateTime::createFromFormat('d/m/Y H:i:s', $data[6]);
					$date_arriver_formatted = $date_arriver->format('Y-m-d H:i:s');
	
					// Vérifier si l'enregistrement existe déjà dans etape_coureur
					$this->db->where('id_etape', $id_etape);
					$this->db->where('id_coureur', $id_coureur);
					$query = $this->db->get('etape_coureur');
	
					if ($query->num_rows() > 0) {
						// Mettre à jour l'enregistrement existant
						$this->db->where('id_etape', $id_etape);
						$this->db->where('id_coureur', $id_coureur);
						$this->db->update('etape_coureur', array('date_arriver' => $date_arriver_formatted));
					} else {
						// Insérer un nouvel enregistrement
						$etape_coureur = array(
							'id_etape' => $id_etape,
							'id_coureur' => $id_coureur,
							// 'id_equipe' => $id_equipe,
							'date_depart' => $this->model->get_date_depart($id_etape),
							'date_arriver' => $date_arriver_formatted
						);
						$this->db->insert('etape_coureur', $etape_coureur);
					}
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
	
	public function actualise_resultat(){
		$this->model->calculate_and_insert_results();
		redirect(base_url('controller/home'));
	}

	public function actualise_resultat_categorie(){
		$this->model->calculate_and_insert_results_categorie();
		redirect(base_url('controller/categorie_equipe'));
	}


	public function generer_categories() {
		$this->model->generer_categories();
		redirect(base_url('controller/home'));
	
	}
	

	public function import_points_csv() {
        if (isset($_FILES['csv_file']['tmp_name'])) {
            $file = $_FILES['csv_file']['tmp_name'];
    
            $handle = fopen($file, "r");
            if ($handle !== FALSE) {
                fgetcsv($handle, 1000, ",");
                
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $existing_points = $this->model->get_points_by_classement($data[0]);
                    
                    if ($existing_points) {
                        if ($existing_points['points'] != $data[1]) {
                            $this->model->update_points($data[0], $data[1]);
                        }
                    } else {
                        $this->model->insert_points($data[0], $data[1]);
                    }
                }
                fclose($handle);
                echo "Importation réussie!";
				redirect(base_url('controller/importation_csv_point'));
            } else {
                echo "Erreur lors de l'ouverture du fichier.";
            }
        } else {
            echo "Aucun fichier sélectionné.";
        }
    }

	public function add_coureur() {
        if ($this->input->post()) {
            $id_equipe = $this->input->post('id_equipe');
            $id_etape = $this->input->post('id_etape');
            $coureur_data = array(
                'nom' => $this->input->post('nom'),
                'numero_dossard' => $this->input->post('numero_dossard'),
                'genre' => $this->input->post('genre'),
                'date_naissance' => DateTime::createFromFormat('Y-m-d', $this->input->post('date_naissance'))->format('Y-m-d')
            );

            $this->model->add_coureur_to_equipe_and_etape($id_equipe, $id_etape, $coureur_data);
            echo "Coureur ajouté avec succès!";
        } else {
            echo "Aucune donnée reçue.";
        }
    }
	
	public function get_points_par_equipe_et_categorie_avec_rang() {
		$results = $this->model->get_points_par_equipe_et_categorie();
		
		$ranked_results = array();
		$current_category = null;
		$current_rank = 1;
		$previous_points = null;
		$rank_counter = 1;
	
		foreach ($results as $result) {
			if ($current_category != $result['id_categorie']) {
				$current_category = $result['id_categorie'];
				$current_rank = 1;
				$previous_points = null;
				$rank_counter = 1;
			}
	
			if ($previous_points !== null && $previous_points != $result['points_totaux']) {
				$current_rank = $rank_counter;
			}
	
			$ranked_results[] = array_merge($result, ['rang' => $current_rank]);
	
			$previous_points = $result['points_totaux'];
			$rank_counter++;
		}
	
		return $ranked_results;
	}
	
	public function categorie_equipe() {
		$this->load->model('ModelGeneral'); 
		$data['page'] = "categorie_equipe";
		$data['categorie_equipe'] = $this->get_points_par_equipe_et_categorie_avec_rang();
		$this->load->view('header', $data);
		
	}
	
	
}
