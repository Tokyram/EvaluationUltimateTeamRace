
<div class="col-lg-10" style="margin-left:260px; margin-top:100px;">
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php echo base_url("Controller/resetData"); ?>" class="btn btn-danger">Réinitialiser les données</a>
        </div>
        </div>
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
                                        <a href="<?= base_url('Controller/form?id=' . $data->id) ?>" class="btn btn-light btn-round px-5"><i class="icon-note"></i> Temps coureur</a>
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