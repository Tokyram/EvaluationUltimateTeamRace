<style>
    .cinema {
        display: block;
        /* justify-content: center; */
        margin-left: 400px;
    }

    .salle {
        display: flex;
        /* flex-direction: column; */
        justify-content: center;
    }

    .rangee {
        display: flex;
    }

    .place {
        width: 100px;
        height: 50px;
        margin: 5px;
        border: none;
        display: flex;
        justify-content: space-around;
        align-items: center;
        border-radius: 10px;
        position: relative;
        background-color: rebeccapurple;

    }

    .checkmark{
        color: red !important;
    }

    .btn-valider {
        margin-top: 50px;
        margin-left: 400px;
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 10px;
    }
    .reserved {
    background-color: red; /* Changer la couleur de fond pour une place réservée */
    }

</style>

<?php echo $_SESSION['administrateur']['id'];?>

<form action="<?= base_url('Controller/validerPlace') ?>" method="post">
    <div class="cinema">
        <div class="salle">
            <?php
            // Définir le nombre de colonnes (A à J) et le nombre de lignes (1 à 10)
            $colonnes = range('A', 'J');
            $lignes = range(1, 10);
            $user = $_SESSION['administrateur']['id'];

            // Boucle sur chaque ligne
            foreach ($lignes as $ligne) {
                echo '<div class="rangee">';
                // Boucle sur chaque colonne
                foreach ($colonnes as $colonne) {
                    $place_name = $colonne . $ligne; // Nom de la place (par exemple, A1)
                    $place_found = false;
                    // Recherche de la place correspondante dans la liste des places
                    foreach ($places as $place) {
                        if ($place->colonne === $colonne && $place->ligne == $ligne) {
                            // Si la place est réservée, ajoutez une classe CSS pour la réserver
                            $class = $place->etat == 1 ? 'reserved' : '';
                            echo '<label class="place ' . $class . '">';
                            echo '<input type="radio" name="place" value="' . $place->id . '">'; // Ajout de l'input radio
                            echo $place_name;
                            echo '</label>';
                            // Ajouter des champs cachés pour stocker l'ID de la place sélectionnée et l'ID de la diffusion
                            echo '<input type="hidden" name="id_place" value="' . $place->id . '">';
                            echo '<input type="hidden" name="id_diffusion" value="' . $id_diffusion . '">';
                            // Ajouter un champ caché pour stocker l'ID de l'utilisateur connecté
                            echo '<input type="hidden" name="id_utilisateur" value="' . $user . '">';
                            $place_found = true;
                            break; // Sortie de la boucle une fois que la place est trouvée
                        }
                    }
                    // Si la place n'a pas été trouvée dans la liste des places, affichez une place vide
                    if (!$place_found) {
                        echo '<div class="place empty">' . $place_name . '</div>';
                    }
                }
                echo '</div>'; // Fermeture de la rangée
            }
            ?>
        </div>
    </div>
    <button type="submit" class="btn-valider" value="valider">Valider la place</button>
</form>






