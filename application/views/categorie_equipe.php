
<div class="row" style="margin-left:270px;margin-top:100px">
  <div class="col-lg-12">
            <a href="<?php echo base_url("Controller/actualise_resultat_categorie"); ?>" class="btn btn-light btn-round px-5">Mettre a jour les resultats</a>
  </div>
  <br></br>
<div class="col-8 col-lg-8" style="display: flex; flex-wrap: wrap; justify-content:space-evenly">
<h1 style="margin:10px">Classement Général par Catégorie</h1>
<br>
<?php if (!empty($categorie_equipe)): 
    $current_category = null;
    $categories = array();

    // Organisez les résultats par catégorie
    foreach ($categorie_equipe as $result) {
        if (!isset($categories[$result['id_categorie']])) {
            $categories[$result['id_categorie']] = array();
        }
        $categories[$result['id_categorie']][] = $result;
    }

    // Affichez les sections par catégorie
    foreach ($categories as $id_categorie => $results):
        if (!empty($results)):
            $category_name = $results[0]['nom_categorie'];
            ?>

	 
	   <div class="card" style="margin:50px;">
     <h3 class="category-title" style="margin:20px">Catégorie: <p style="color:#ffd400;"> <?= htmlspecialchars($category_name) ?></p></h3>

     <section class="category-section">
    <div class="table-responsive">
        <table class="table align-items-center table-flush table-borderless">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Équipe</th>
                    <th>Points Totaux</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                $occurrences = array_count_values(array_column($results, 'points_totaux'));
                $defaultColors = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark']; // Couleurs par défaut
                $colorIndex = 0;
                $colorMap = []; 
                
                foreach ($results as $result): 
                    $points_totaux = $result['points_totaux'];
                    $occurrence = $occurrences[$points_totaux];

                    if (!isset($colorMap[$points_totaux])) {
                        $colorMap[$points_totaux] = $defaultColors[$colorIndex % count($defaultColors)];
                        $colorIndex++; 
                    }
                    $colorClass = $colorMap[$points_totaux];
                ?>
                    <tr>
                        <td><i class="fa fa-circle text-white mr-2"></i><?= htmlspecialchars($result['rang']) ?></td>
                        <td><?= htmlspecialchars($result['nom_equipe']) ?></td>
                        <td class="<?= $colorClass ?>"><?= htmlspecialchars($points_totaux) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>


</div>

        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun résultat trouvé.</p>
<?php endif; ?>
</div>
</div>

