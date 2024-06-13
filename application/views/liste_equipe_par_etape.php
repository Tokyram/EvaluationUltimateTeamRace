<div class="row">
<div class="col-12 col-lg-12">
	   <div class="card">
	     <div class="card-header">Listes Equipe
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
                      <th>Points totaux</th>
                    </tr>
                   </thead>
                   <tbody>
                   <?php foreach($points_totaux_par_equipe_par_etape_equipe as $data): ?> 
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
     </div>