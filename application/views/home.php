
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
     <div class="col-12 col-lg-8 col-xl-8">
	    <div class="card">
		 <div class="card-header">Site Traffic
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
		 <div class="card-body">
		    <ul class="list-inline">
			  <li class="list-inline-item"><i class="fa fa-circle mr-2 text-white"></i>New Visitor</li>
			  <li class="list-inline-item"><i class="fa fa-circle mr-2 text-light"></i>Old Visitor</li>
			</ul>
			<div class="chart-container-1">
			  <canvas id="chart1"></canvas>
			</div>
		 </div>
		 
		 <div class="row m-0 row-group text-center border-top border-light-3">
		   <div class="col-12 col-lg-4">
		     <div class="p-3">
		       <h5 class="mb-0">45.87M</h5>
			   <small class="mb-0">Overall Visitor <span> <i class="fa fa-arrow-up"></i> 2.43%</span></small>
		     </div>
		   </div>
		   <div class="col-12 col-lg-4">
		     <div class="p-3">
		       <h5 class="mb-0">15:48</h5>
			   <small class="mb-0">Visitor Duration <span> <i class="fa fa-arrow-up"></i> 12.65%</span></small>
		     </div>
		   </div>
		   <div class="col-12 col-lg-4">
		     <div class="p-3">
		       <h5 class="mb-0">245.65</h5>
			   <small class="mb-0">Pages/Visit <span> <i class="fa fa-arrow-up"></i> 5.62%</span></small>
		     </div>
		   </div>
		 </div>
		 
		</div>
	 </div>

     <div class="col-12 col-lg-4 col-xl-4">
        <div class="card">
           <div class="card-header">classement général par équipe

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
           <!-- <div class="card-body">
		     <div class="chart-container-2">
               <canvas id="chart2"></canvas>
			  </div>
           </div> -->
           <div class="table-responsive">
             <table class="table align-items-center">
               <tbody>

               <?php foreach($classement_general_par_equipe_avec_rang as $data): ?> 
                  <tr>
                    <td><i class="fa fa-circle text-white mr-2"></i><?php echo $data->rang; ?></td>
                    <td>Equipe <?php echo $data->nom_equipe; ?></td>
                    <td > <p style=" color:#ffd400"><?php echo $data->points_totaux; ?></p></td>
                  </tr>
                 <?php endforeach; ?>

               </tbody>
             </table>
           </div>
         </div>
     </div>
	</div><!--End Row-->
	
	<div class="row">
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
	</div><!--End Row-->

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
	
