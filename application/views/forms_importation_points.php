<style>

input[type="file"]::file-selector-button {
    background-color: #ffd400;
    color: #003202;
    margin-top: -5px;
    border-radius: 20px;
    border : none !important;
    width: 200px;
}




</style>
<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

    <div class="row mt-3">

      <div class="col-lg-6">
        <div class="card">
           <div class="card-body">
           <div class="card-title"><svg xmlns="http://www.w3.org/2000/svg" style="margin: 5px; color:#ffd400;"  width="20" height="20" fill="currentColor" class="bi bi-file-earmark-arrow-up-fill" viewBox="0 0 16 16">
                  <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M6.354 9.854a.5.5 0 0 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 8.707V12.5a.5.5 0 0 1-1 0V8.707z"/>
              </svg> IMPORTER LES NOUVEAUX POINTS</div>
           <hr>
           <form action="<?= base_url("Controller/import_points_csv"); ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                  <label for="csv_file_paiement">Ajouter votre fichier CSV de point</label>
                  <input type="file" name="csv_file" class="form-control form-control-rounded" id="csv_file_paiement" placeholder="Importer le fichier">
              </div>
              <div class="form-group">
                  <button type="submit" class="btn btn-light btn-round px-5"><i class="icon-lock"></i> Register</button>
              </div>
          </form>

         </div>
         </div>
      </div>
     
      
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
	
	
