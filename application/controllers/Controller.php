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
		$data['coureur'] = $this->model->findAll('coureur', 'nom');
		$data['equipe_coureur'] = $this->model->get_where_equipe_coureur($id_user);
		$data['etape_coureur'] = $this->model->get_where_etape_coureur($id);
		$this->load->view('header',$data);
	}

	public function insert_etape_coureur(){
		$input = $this->input->post();
		
		$data = array(
			"id_etape" => $input['etape'],
			"id_coureur" => $input['coureur'],
		);

		$this->model->save('etape_coureur',$data);
		redirect(base_url('controller/form?id='.$input['etape']));

	}

	public function insert_temps_coureur() {
		$input = $this->input->post();
		
		// Convertir les dates en format DATETIME
		$date_depart = date("Y-m-d H:i:s", strtotime($input['depart']));
		$date_arriver = date("Y-m-d H:i:s", strtotime($input['arriver']));
	
		// Données à insérer dans la base de données
		$data = array(
			"date_depart" => $date_depart,
			"date_arriver" => $date_arriver
		);
	
		// Mise à jour des données dans la table etape_coureur
		$this->model->update2('etape_coureur', 'id_etape', 'id_coureur', $input['etape'], $input['coureur'], $data);
	
		// Calculer les points et insérer les résultats uniquement pour les coureurs mis à jour
		$this->model->calculate_and_insert_results();
	
		// Redirection vers une autre page
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
        try {
            $inputs = $this->input->post();
            $login = $this->model->login($inputs, 'utilisateur');
            if($login != null) {
                if($login['type_utilisateur'] == 1) {
                    $_SESSION['administrateur'] = $login;
                    $this->liste();
                }
                if($login['type_utilisateur'] != 1) {
                    $_SESSION['utilisateur'] = $login;
                    $this->profil();
                }
            }
            else throw new PDOException("Vérifiez votre nom d'utilisateur et mot de passe!!");
        }
        catch(Exception $ex) {
            $data['erreur'] = $ex->getMessage();
            // $data['page']='login';
			$this->load->view('login');
			// $this->load->view('header',$data);
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
	
}
