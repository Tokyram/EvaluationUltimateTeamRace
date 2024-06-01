
<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

    <div class="row mt-3">

      <div class="col-lg-6">
        <div class="card">
           <div class="card-body">
           <div class="card-title">VENTE DE BILLET</div>
           <hr>
            <form action="<?=base_url("Controller/ajout_vente_billet");?>" method="post">
           <div class="form-group">
            <label for="input-6">Nom du pack</label>

            <select name="pack" class="form-control form-control-rounded" id="input-6">
                <?php foreach($listepack as $lp){ ?>
                      <option value="<?php echo $lp->nom_pack;?>"><?php echo $lp->nom_pack;?></option>
                <?php }?>
            </select>
            <!-- <input type="text" name="nom" class="form-control form-control-rounded" id="input-6" placeholder="nom du pack"> -->
           </div>

           <!-- <div class="form-group">
              <label for="input-7">Nombre de billet</label>
              <input type="text" name="prix" class="form-control form-control-rounded" id="input-7" placeholder="Nombre de billet">
           </div> -->
        
           <div class="form-group">
            <button type="submit" class="btn btn-light btn-round px-5"><i class="icon-lock"></i> Acheter </button>
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
	
	
