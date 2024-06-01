
<?php
  $user = $this->session->userdata('utilisateur');
  $user2 = $this->session->userdata('administrateur');

  if ($user ) {
      echo 'Nom d\'utilisateur : ' . $user['nom'];
  } elseif($user2) {
      echo 'Nom d\'utilisateur : ' . $user2['nom'];
  }else{
      echo "Aucun utilisateur connecté.";
  }

?>

<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

      <div class="row mt-3">
        <div class="col-lg-4">
           <div class="card profile-card-2">
            <div class="card-img-block">
                <img class="img-fluid" src="https://via.placeholder.com/800x500" alt="Card image cap">
            </div>
            <div class="card-body pt-5">
                <!-- <img src="https://via.placeholder.com/110x110" alt="profile-image" class="profile"> -->
                <h2 class="card-title">

                <?php
                    $user = $this->session->userdata('utilisateur');
                    $user2 = $this->session->userdata('administrateur');

                    if ($user ) {
                        echo 'Equipe : ' . $user['nom'];
                    } elseif($user2) {
                        echo 'Equipe : ' . $user2['nom'];
                    }else{
                        echo "Aucun utilisateur connecté.";
                    }

                    ?>
                </h2>
                <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
                <div class="icon-block">
                  <a href="javascript:void();"><i class="fa fa-facebook bg-facebook text-white"></i></a>
				  <a href="javascript:void();"> <i class="fa fa-twitter bg-twitter text-white"></i></a>
				  <a href="javascript:void();"> <i class="fa fa-google-plus bg-google-plus text-white"></i></a>
                </div>
            </div>

            <div class="card-body border-top border-light">
                 <div class="media align-items-center">
                   <div>
                       <!-- <img src="assets/images/timeline/html5.svg" class="skill-img" alt="skill img"> -->
                   </div>
                     <div class="media-body text-left ml-3">
                       <div class="progress-wrapper">
                         <p>HTML5 <span class="float-right">65%</span></p>
                         <div class="progress" style="height: 5px;">
                          <div class="progress-bar" style="width:65%"></div>
                         </div>
                        </div>                   
                    </div>
                  </div>
                  <hr>
                  
                  
              </div>
        </div>

        </div>

        <div class="col-lg-8">
           <div class="card">
            <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-primary top-icon nav-justified">
                <li class="nav-item">
                    <a href="javascript:void();" data-target="#profile" data-toggle="pill" class="nav-link active"><i class="icon-user"></i> <span class="hidden-xs">ETAPES</span></a>
                </li>
              
            </ul>
            <div class="tab-content p-3">
                <div class="tab-pane active" id="profile">
                    <h5 class="mb-3">Liste des Etapes</h5>
                    <!-- <a href="<?= base_url('Controller/form?id=' . $data->id) ?>" class="btn btn-light btn-round px-5"><i class="icon-note"></i>Assiger un coureur</a> -->
                    <div class="row">
                       
                        <div class="col-md-12">
                            <!-- <h5 class="mt-2 mb-3"><span class="fa fa-clock-o ion-clock float-right"></span> Recent Activity</h5> -->
                             <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                        <th>Numero</th>

                                        <th>Nom etape</th>

                                        <th>Distance</th>
                                        <th>NB coureur</th>
                                        <th>Assigner</th>
                                    </tr> 
                                </thead>
                                <tbody>  
                                <?php foreach($etapes as $data): ?>                           
                                    <tr>
                                        <td>
                                        <?php echo $data->rang_etape; ?>
                                        </td>
                                        <td>
                                        <?php echo $data->nom; ?>
                                        </td>
                                        <td>
                                        <?php echo $data->longueur; ?> km
                                        </td>
                                        <td>
                                        <?php echo $data->nb_coureur; ?>
                                        </td>
                                        <td>
                                        <a href="<?= base_url('Controller/form?id=' . $data->id) ?>" class="btn btn-light btn-round px-5"><i class="icon-note"></i> Assiger un coureur</a>
                                        </td>
                                        
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!--/row-->
                </div>
               
            </div>
        </div>
      </div>
      </div>
        
    </div>

	<!--start overlay-->
		  <div class="overlay toggle-menu"></div>
		<!--end overlay-->
	
    </div>
    <!-- End container-fluid-->
   </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
