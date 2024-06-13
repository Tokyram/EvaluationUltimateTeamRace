CREATE SCHEMA race;

CREATE  TABLE race.categorie ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       
 ) engine=InnoDB;

CREATE  TABLE race.coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	numero_dossard       VARCHAR(100)       ,
	genre                VARCHAR(100)       ,
	date_naissance       DATE       
 ) engine=InnoDB;

CREATE  TABLE race.etape ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	longueur             DOUBLE       ,
	nb_coureur           INT       ,
	rang_etape           INT       
 ) engine=InnoDB;

CREATE  TABLE race.etape_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_etape             INT       ,
	id_coureur           INT       ,
	date_depart          DATETIME(6)       ,
	date_arriver         DATETIME(6)       
 ) engine=InnoDB;

CREATE  TABLE race.resultat ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_etape             INT       ,
	id_coureur           INT       ,
	rang                 INT       ,
	point                INT       
 ) engine=InnoDB;

CREATE  TABLE race.utilisateur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	nom                  VARCHAR(100)       ,
	login                VARCHAR(100)       ,
	mdp                  VARCHAR(255)       ,
	type_utilisateur     INT       
 ) engine=InnoDB;

CREATE  TABLE race.categorie_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_categorie         INT       ,
	id_coureur           INT       
 ) engine=InnoDB;

CREATE  TABLE race.equipe_coureur ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	id_equipe            INT       ,
	id_coureur           INT       
 ) engine=InnoDB;

ALTER TABLE race.categorie_coureur ADD CONSTRAINT fk_categorie_coureur_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.categorie_coureur ADD CONSTRAINT fk_categorie_coureur_categorie FOREIGN KEY ( id_categorie ) REFERENCES race.categorie( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.equipe_coureur ADD CONSTRAINT fk_equipe_coureur_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.equipe_coureur ADD CONSTRAINT fk_equipe_coureur_utilisateur FOREIGN KEY ( id_equipe ) REFERENCES race.utilisateur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.etape_coureur ADD CONSTRAINT fk_etape_coureur_etape FOREIGN KEY ( id_etape ) REFERENCES race.etape( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE race.etape_coureur ADD CONSTRAINT fk_etape_coureur_coureur FOREIGN KEY ( id_coureur ) REFERENCES race.coureur( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

 <!-- <div class="table-responsive">

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

              
                <table class="table align-items-center">
                <?php foreach ($results as $result): ?>
                  <tbody>
                    <tr>
                        <td><i class="fa fa-circle text-white mr-2"></i><?= htmlspecialchars($result['rang']) ?></td>
                        <td><?= htmlspecialchars($result['nom_equipe']) ?></td>
                        <td><?= htmlspecialchars($result['points_totaux']) ?></td>
                    </tr>
                  </tbody>
                  <?php endforeach; ?>
                </table>

                                <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun résultat trouvé.</p>
                <?php endif; ?>
              </div> -->
