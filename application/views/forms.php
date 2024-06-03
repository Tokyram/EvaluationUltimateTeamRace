


<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

    <div class="row mt-3">
     
    <?php if(!isset($_SESSION['administrateur'])) {?>

      <div class="col-lg-6">
        <div class="card">
           <div class="card-body">
           <div class="card-title">Assigner le coureur</div>
           <hr>
                <?php if(isset($erreur)) { ?>
                    <div class="alert alert-danger" style="height:50px; display:flex;justify-content:center;align-items:center; border-radius:50px"><?php echo $erreur; ?></div>
                <?php } ?>
                
            <form action="<?= base_url("Controller/insert_etape_coureur"); ?>" method="post"  id="packForm" enctype="multipart/form-data">
            <input type="hidden" name="etape" value="<?php echo $etape; ?>">
           <div class="form-group">
            <label for="input-6">Selectionne le coureur</label>
              <select name="coureur" class="form-control form-control-rounded" id="input-6">
                  <?php foreach($equipe_coureur as $data): ?>
                      <option value="<?php echo $data->id_coureur; ?>"><?php echo $data->nom; ?></option>
                  <?php endforeach; ?>
              </select>
           </div>
           
           <div class="form-group">
            <button type="submit" class="btn btn-light btn-round px-5"><i class="icon-lock"></i> Register</button>
          </div>
          </form>
         </div>
         </div>

      </div>
      
      
 <br> 
      
      

    <div class="col-lg-6">      
        <div class="table-responsive">
            <h1>Temps des coureurs pour <br> l'équipe 
            <?php
            $user = $this->session->userdata('utilisateur');
            $user2 = $this->session->userdata('administrateur');

            if ($user ) {
                echo $user['nom'];
            } elseif($user2) {
                echo $user2['nom'];
            } else {
                echo "Aucun utilisateur connecté.";
            } 
            ?> et l'étape <?php echo $etape; ?></h1>
            <br> 
            <br> 
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID Etape</th>
                        <th>Nom Coureur</th>
                        <th>Temps Total (secondes)</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($get_coureurs_with_temps_by_equipe_and_etape as $coureur): ?>
                    <tr>
                        <td><?php echo $coureur['id_etape']; ?></td>
                        <td><?php echo $coureur['nom_coureur']; ?></td>
                        <td><?php echo gmdate("H:i:s", $coureur['temps_total']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h5><i class="icon-user"></i> <?php echo $coureur['coureurs_count']; ?> </span></h5>

        </div>
    </div>


      <!-- <div class="col-lg-6">
        <div class="card">
           <div class="card-body">
           <div class="card-title">Assigner le coureur</div>
           <hr>
           
            <form action="<?= base_url("Controller/add_coureur"); ?>" method="post"  id="packForm" enctype="multipart/form-data">
            <input type="hidden" name="id_equipe" value="<?php echo $user['id']; ?>">
          <input type="hidden" name="id_etape" value="<?php echo $etape; ?>">  
                    
          <div class="form-group">
              <label for="input-6">Nom</label>
              <input type="text" class="form-control form-control-rounded" name="nom" id="nom" placeholder="nom du coureur">
          </div>

          <div class="form-group">
              <label for="input-6">Numéro de dossard</label>
              <input type="text" class="form-control form-control-rounded"name="numero_dossard" id="numero_dossard" placeholder="Numéro de dossard du coureur">
          </div>

          <div class="form-group">
              <label for="input-6">Genre</label>
              <input type="text" class="form-control form-control-rounded" name="genre" id="genre" placeholder="Genre du coureur">
          </div>

          <div class="form-group">
              <label for="input-6">Date de naissance</label>
              <input type="date" class="form-control form-control-rounded" name="date_naissance" id="date_naissance" placeholder="Date de naissance du coureur">
          </div>
           
           <div class="form-group">
            <button type="submit" class="btn btn-light btn-round px-5"><i class="icon-lock"></i> Ajouter coureur</button>
          </div>
          </form>
         </div>
         </div>

      </div> -->

      
   
    <?php }?>

    <?php if(!isset($_SESSION['utilisateur'])) {?>

      <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Affectation temps du coureur dans l'etape <?php echo $etape; ?></div>
                <hr>
                <!-- Afficher les messages d'erreur -->
                <?php if(isset($erreur)) { ?>
                    <div class="alert alert-danger" style="height:50px; display:flex;justify-content:center;align-items:center; border-radius:50px"><?php echo $erreur; ?></div>
                <?php } ?>
                <form action="<?= base_url("Controller/insert_temps_coureur"); ?>" method="post" id="packForm" enctype="multipart/form-data">
                    <input type="hidden" name="etape" value="<?php echo $etape; ?>">

                    <div class="form-group">
                        <label for="input-6">Selectionne le coureur dans l'etape <?php echo $etape; ?> </label>
                        <select name="coureur" class="form-control form-control-rounded" id="input-6">
                            <?php foreach($etape_coureur as $data): ?>
                                <option value="<?php echo $data->id_coureur; ?>"><?php echo $data->nom; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="input-6">Date de depart</label>
                        <input type="datetime-local" class="form-control form-control-rounded" id="input-6" name="depart" step="1" placeholder="Date depart">
                    </div>

                    <div class="form-group">
                        <label for="input-6">Date d'arriver</label>
                        <input type="datetime-local" class="form-control form-control-rounded" id="input-6" name="arriver" step="1" placeholder="Date arriver">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-light btn-round px-5"><i class="icon-lock"></i> Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

      <?php }?>
      <!-- <div class="col-lg-6">
        <div class="card">
           <div class="card-body">
           <div class="card-title">VENTE DE BILLET</div>
           <hr>
            <form action="#" method="post">
           <div class="form-group">
            <label for="input-6">Nom du pack</label>

            <select name="pack" class="form-control form-control-rounded" id="input-6">
                
                      <option value="">wshjnwdfb</option>
              
            </select>
           </div>

           <div class="form-group">
            <button type="submit" class="btn btn-light btn-round px-5"><i class="icon-lock"></i> Acheter </button>
          </div>
          </form>
         </div>
         </div>
      </div> -->

    
    </div><!--End Row-->

	<!--start overlay-->
		  <div class="overlay toggle-menu"></div>
		<!--end overlay-->

    </div>
    <!-- End container-fluid-->
    
   </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
	
