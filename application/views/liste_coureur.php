
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
                    <h5 class="mb-3">Liste des Coureurs</h5>
                    <!-- <a href="<?= base_url('Controller/form?id=' . $data->id) ?>" class="btn btn-light btn-round px-5"><i class="icon-note"></i>Assiger un coureur</a> -->
                    <div class="row">
                       
                        <div class="col-md-12">
                            <!-- <h5 class="mt-2 mb-3"><span class="fa fa-clock-o ion-clock float-right"></span> Recent Activity</h5> -->
                             <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>

                                        <th>Classement</th>
                                        <th>Nom du coureur</th>

                                        <th>Genre</th>

                                        <th>Temps initial</th>
                                        <th>Penalite</th>
                                        <th>Temps penaliser</th>
                                    </tr> 
                                </thead>
                                <tbody>  
                                <?php foreach($coureur_etape_temps_penalite as $data): ?>                           
                                    <tr>
                                        <td>
                                        <?php echo $data['rang']; ?>
                                        </td>
                                        <td>
                                        <?php echo $data['nom']; ?>
                                        </td>
                                        <td>
                                        <?php echo $data['genre']; ?> 
                                        </td>
                                        <td>
                                            <?php
                                            $temps_initial = $data['temps_initial'];
                                            $heures = floor($temps_initial / 3600);
                                            $minutes = floor(($temps_initial % 3600) / 60);
                                            $secondes = $temps_initial % 60;
                                            echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                            ?>
                                        </td>

                                        <td>
                                        <?php
                                            $temps_initial = $data['penalite_en_secondes'];
                                            $heures = floor($temps_initial / 3600);
                                            $minutes = floor(($temps_initial % 3600) / 60);
                                            $secondes = $temps_initial % 60;
                                            echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                            ?>
                                        </td>
                                        <td>
                                        <?php
                                            $temps_initial = $data['temps_total'];
                                            $heures = floor($temps_initial / 3600);
                                            $minutes = floor(($temps_initial % 3600) / 60);
                                            $secondes = $temps_initial % 60;
                                            echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                            ?>
                           

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