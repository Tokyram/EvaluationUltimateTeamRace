
<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

 
	  
	<div class="row">

      <div class="col-12 col-lg-4 col-xl-4">
        <div class="card">
            <div class="card-body">
            <div class="card-title">Ajouter un penalite a un equipe</div>
            <hr>
                  <?php if(isset($erreur)) { ?>
                      <div class="alert alert-danger" style="height:50px; display:flex;justify-content:center;align-items:center; border-radius:50px"><?php echo $erreur; ?></div>
                  <?php } ?>
                  
              <form action="<?= base_url("Controller/add_penalite_equile"); ?>" method="post"  id="packForm" enctype="multipart/form-data">
            <div class="form-group">
              <label for="input-6">Selectionne un etape</label>
                <select name="id_etape" class="form-control form-control-rounded" id="input-6">
                    <?php foreach($etapes as $data): ?>
                        <option value="<?php echo $data->id; ?>"><?php echo $data->nom; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
              <label for="input-6">Selectionne un Equipe</label>
                <select name="id_equipe" class="form-control form-control-rounded" id="input-6">
                    <?php foreach($equipes as $data): ?>
                        <option value="<?php echo $data->id; ?>"><?php echo $data->nom; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
            <label for="input-6">Ajouter la pénalite</label>
            <input type="time" step="1" name="temps_penalite_equipe" class="form-control form-control-rounded" id="input-6" placeholder="importer le fichier">
           </div>
            
            <div class="form-group">
              <button type="submit" class="btn btn-light btn-round px-5"><i class="icon-lock"></i> Register</button>
            </div>
            </form>
          </div>
          </div>
        </div>

    
	
	

	 <div class="col-12 col-lg-4 col-xl-4">
	   <div class="card">
	     <div class="card-header">Listes des des penalites
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
     <!-- Confirmation Modal -->
          <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <h2 style="color:black">Êtes-vous sûr de vouloir supprimer cette pénalité ?</h2>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                  <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Oui</button>
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
                        <th>Penalite</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($penalite_equipe as $data): ?> 
                        <tr>
                            <td><?php echo $data['nom_etape']; ?></td>
                            <td>Equipe <?php echo $data['nom_equipe']; ?></td>
                            <td><p style="color:#ffd400"><?php echo $data['temps_penalite_equipe']; ?></p></td>
                            <td>
                                <a style="background-color: red; border:none;" 
                                  href="javascript:void(0);" 
                                  onclick="showConfirmationModal('<?= base_url('Controller/supprimer/' . $data['id_penalite']); ?>');" 
                                  class="btn btn-light btn-round px-5">
                                    <svg style="color: white;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

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
	
    <script type="text/javascript">
    function showConfirmationModal(url) {
        $('#confirmationModal').modal('show');
        document.getElementById('confirmDeleteBtn').onclick = function() {
            window.location.href = url;
        };
    }
</script>

