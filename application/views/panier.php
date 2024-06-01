<div class="table-responsive" style="margin-top:100px; margin-left: 300px; width: 1500px">
<h5 class="logo-text">Vos achat</h5>
                 <table class="table align-items-center table-flush table-borderless">
                  <thead>
                    <tr>
                      <th>Film</th>
                      <th>Montant</th>
                      <th>Colonne</th>
                      <th>Ligne</th>
                      <th>Date diffusion</th>
                      <th>Heure diffusion</th>
                      <th>Export</th>
                    </tr>
                   </thead>
                   <tbody>
                   <?php foreach($findVenteExport as $fl): ?>
                      <tr>
                          <td><?php echo $fl->nom_film; ?></td>
                          <td><?php echo $fl->tarif; ?></td>
                          <td><?php echo $fl->colonne; ?></td>
                          <td><?php echo $fl->ligne; ?></td>
                          <td><?php echo $fl->date_diffusion; ?></td>
                          <td><?php echo $fl->heure_diffusion; ?></td>
                          <td><a class="btn btn-secondary" href="<?= base_url('Controller/pdf_export/' . $fl->id_vente) ?>">Exporter en pdf</a></td>
                          
                    </tr>
                   <?php endforeach; ?>
                  </tbody>
                </table