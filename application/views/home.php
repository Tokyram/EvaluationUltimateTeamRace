
<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

  <!--Start Dashboard Content-->
  <div class="col-lg-12">
            <a href="<?php echo base_url("Controller/actualise_resultat"); ?>" class="btn btn-light btn-round px-5">Actualiser les resultats</a>
  </div>
  <br>
  <div class="col-lg-12">
            <a href="<?php echo base_url("Controller/generer_categories"); ?>" style="background-color: #ffd400; color:black" class="btn btn-light btn-round px-5">Generer les categories des coureur</a>
  </div>
	<div class="card mt-3">
    <div class="card-content">

        <div class="row row-group m-0">
          
       
            <div class="col-12 col-lg-6 col-xl-3 border-light">
                <div class="card-body">
                  <h5 class="text-white mb-0"> <span class="float-right"><i class="fa fa-shopping-cart"></i></span></h5>
                    <div class="progress my-3" style="height:3px;">
                       <div class="progress-bar" style="width:30%;"></div>
                    </div>
                  <p class="mb-0 text-white small-font">Billet vendu <span class="float-right"><i class="zmdi zmdi-long-arrow-up"></i></span></p>
                </div>
            </div>
           
        </div>

    </div>
 </div>  
	  
	<div class="row">

      <div class="col-12 col-lg-4 col-xl-4">
            <div class="card">
              <div class="card-header">CHART CLASSEMENT EQUIPE
                <div class="card-action">
                <div class="dropdown">
                <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                  <i class="icon-options"></i>
                </a>
                  <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="javascript:void();">Action</a>
                  <a class="dropdown-item" href="javascript:void();">Another action</a>
                  <a class="dropdown-item" href="javascript:void();">Something else here</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="javascript:void();">Separated link</a>
                  </div>
                  </div>
                </div>
              </div>
              <div class="card-body"><p>
                <div class="chart-container-2" style="width: 400px; height:400px; display:flex;justify-content:center;color:#ffffff">
                <?php

                  $equipeLabels = [];
                  $equipePoints = [];
                  foreach ($classement_general_par_equipe_avec_rang as $data) {
                    $equipeLabels[] = $data->nom_equipe; 
                    $equipePoints[] = $data->points_totaux;
                  }
                  ?>
                      <canvas id="myChart" width="200" height="200" style="margin-left:20px;color:#ffffff"></canvas>  <script>
                        var ctx = document.getElementById('myChart').getContext('2d');
                        var myChart = new Chart(ctx, {
                          type: 'pie',
                          data: {
                            labels: <?php echo json_encode($equipeLabels); ?>,  datasets: [{
                              backgroundColor: [
                                "#ffd400",
                                "#e5e5e5",
                                "#191919",
                                "#ffffff",
                                "#1e7145"  
                              ],
                              data: <?php echo json_encode($equipePoints); ?>  }]
                          },
                          options: {
                              fontColor: 'white', 
                              title: {
                                display: true,
                                text: "Classement Général par Équipe",
                                fontColor: 'white'
                              },
                              legend: {
                                labels: {
                                    fontColor: 'white'
                                }
                            }
                            }

                        });
                        </script>
                </div>
            

            </div>
        </div>
        </div>
        <?php if (!empty($categorie_equipe)): 
                        $categories = array();

                  foreach ($categorie_equipe as $result) {
                    if (!isset($categories[$result['id_categorie']])) {
                        $categories[$result['id_categorie']] = array();
                    }
                    $categories[$result['id_categorie']][] = $result;
                }

                    foreach ($categories as $id_categorie => $results):
                      if (!empty($results)):
                          $category_name = $results[0]['nom_categorie'];

                          $categoryLabels = [];
                          $categoryPoints = [];
                          foreach ($results as $data) {
                            $categoryLabels[] = $data['nom_equipe']; // Include "Equipe" for clarity
                            $categoryPoints[] = $data['points_totaux'];
                          }
                    
                         
                          ?>

                     
        <div class="col-12 col-lg-4 col-xl-4">
            <div class="card">
              <div class="card-header">CHART CLASSEMENT CATEGORIE <?= htmlspecialchars($category_name) ?>
                <div class="card-action">
                <div class="dropdown">
                <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                  <i class="icon-options"></i>
                </a>
                  <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="javascript:void();">Action</a>
                  <a class="dropdown-item" href="javascript:void();">Another action</a>
                  <a class="dropdown-item" href="javascript:void();">Something else here</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="javascript:void();">Separated link</a>
                  </div>
                  </div>
                </div>
              </div>
              <div class="card-body"><p>
                <div class="chart-container-2" style="width: 400px; height:400px; display:flex;justify-content:center;color:#ffffff;margin-left:20px">
                      <canvas id="myChart-<?php echo $id_categorie; ?>"  style="color:white;margin-left:20px" width="400" height="400"></canvas>  <script>
                        var ctx = document.getElementById('myChart-<?php echo $id_categorie; ?>').getContext('2d');
                        var myChart = new Chart(ctx, {
                          type: 'pie',  
                          data: {
                            labels: <?php echo json_encode($categoryLabels); ?>,
                            datasets: [{
                              backgroundColor: [
                                "#ffd400",
                                "#e5e5e5",
                                "#191919",
                                "#ffffff",
                                "#1e7145"  
                              ],
                              data: <?php echo json_encode($categoryPoints); ?>
                            }]
                          },
                          options: {
                            title: {
                              display: true,
                              text: "Classement Catégorie: <?php echo $category_name; ?>",
                              fontColor: 'white'
                            },
                            legend: {
                                labels: {
                                    fontColor: 'white'
                                }
                            }
                          }
                        });
                      </script>
              

                

                </div>
              </div>
             

              

            </div>
        </div>

        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun résultat trouvé.</p>
                <?php endif; ?>

                <div class="col-12 col-lg-4 col-xl-4">
            <div class="card">
              <div class="card-header"> CLASSEMENT EQUIPE
                <div class="card-action">
                <div class="dropdown">
                <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                  <i class="icon-options"></i>
                </a>
                  <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="javascript:void();">Action</a>
                  <a class="dropdown-item" href="javascript:void();">Another action</a>
                  <a class="dropdown-item" href="javascript:void();">Something else here</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="javascript:void();">Separated link</a>
                  </div>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table align-items-center">
                <?php foreach($classement_general_par_equipe_avec_rang as $data): ?> 
                  <tbody>
                    <tr>
                      <td><i class="fa fa-circle text-white mr-2"></i> <?php echo $data->rang; ?></td>
                      <td>
                      <a href="<?php echo base_url('Controller/points_totaux_par_equipe_par_etape_equipe?equipe=' . urlencode($data->nom_equipe)); ?>" style="color:black; height:auto; align-items:center;">
                         Equipe <?php echo htmlspecialchars($data->nom_equipe); ?>
                      </a>
                      </td>

                      <td><p style=" color:#ffd400"><?php echo $data->points_totaux; ?></p></td>
                    </tr>
                  </tbody>
                  <?php endforeach; ?>
                </table>
              </div>

              <div class="col-lg-12" style="margin:20px;">
                  <a href="<?php echo base_url("Controller/export_html_pdf"); ?>" style="background-color: #ffd400; color:black;height:115px;display:flex;justify-content:center;align-items:center;margin-right:40px" class="btn btn-light btn-round px-5">Exporter le premier</a>
              </div>

            </div>
        </div>
    
	
	 <div class="col-12 col-lg-12">
	   <div class="card">
	     <div class="card-header">Listes des classement général et les points pour chaque étape
		  <div class="card-action">
             <div class="dropdown">
             <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
              <i class="icon-options"></i>
             </a>
              <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="javascript:void();">Action</a>
              <a class="dropdown-item" href="javascript:void();">Another action</a>
              <a class="dropdown-item" href="javascript:void();">Something else here</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="javascript:void();">Separated link</a>
               </div>
              </div>  
             </div>
		 </div>
	       <div class="table-responsive">
                 <table class="table align-items-center table-flush table-borderless">
                  <thead>
                    <tr>
                      <th>Etape</th>
                      <th>Equipe</th>
                      <th>Points</th>
                    </tr>
                   </thead>
                   <tbody>
                   <?php foreach($points_totaux_par_equipe_par_etape as $data): ?> 
                      <tr>
                          <td>Etape <?php echo $data->id_etape; ?></td>
                          
                          <td>Equipe <?php echo $data->nom_equipe; ?></td>
                          <td><p style=" color:#ffd400"><?php echo $data->points_totaux; ?></p></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
            </div>
	   </div>
	 </div>
	

      <!--End Dashboard Content-->
	  
	<!--start overlay-->
		  <div class="overlay toggle-menu"></div>
		<!--end overlay-->
		
    </div>
    <!-- End container-fluid-->
    
    </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
  
   