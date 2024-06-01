
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
    <?php }?>

    <?php if(!isset($_SESSION['utilisateur'])) {?>

      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
          <div class="card-title">Affectation temps du coureur dans l'etape <?php echo $etape; ?></div>
          <hr>

            <form action="<?= base_url("Controller/insert_temps_coureur"); ?>" method="post"  id="packForm" enctype="multipart/form-data">
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
	
	
