INSERT INTO restaurant(idRestaurant, nomRestaurant, adresse, ville, pays) VALUES(1, 'Chez Momo', '12 rue du Louvre', 'Paris', 'France'),
(2, 'Bistrot du Terroir', '4 rue de la Pêche', 'Compiègne', 'France');

INSERT INTO cuisinier(idEmploye, nom, prenom, dateNaissance, dateAnciennete, specialite, idRestaurant) VALUES
(1, 'Bonneau', 'Jean', '1956-03-13', '2003-02-06', 'rôtisseur', 1),
(2, 'Dive', 'Nathan', '1994-05-17', '2015-11-02', 'compositeur de salades', 2);
(5, 'Kitori', 'Koya', '1980-04-02', '2010-09-10', 'sushi master', 2);

INSERT INTO manager(idEmploye, nom, prenom, dateNaissance, dateAnciennete, idRestaurant) VALUES (3, 'Verlinfini', 'Satan', '1962-01-24', '2010-02-02', 1);

INSERT INTO serveur(idEmploye, nom, prenom, dateNaissance, dateAnciennete, authorisationAccueil, idRestaurant) VALUES (4, 'Sclave', 'Ashley', '1998-03-15', '2014-01-09', TRUE, 2);

INSERT INTO carte(idCarte, nomCarte) VALUES (1, 'Carte de Noël'), (2, 'Carte de Hanoukka');

INSERT INTO commande(idCommande, idRestaurant, dateCommande) VALUES (1, 1, '2016-04-12');

INSERT INTO element(idELement) VALUES (1),(2),(3),(4);

INSERT INTO menu(idElement, nomMenu) VALUES (1, 'Petites Frimousses');

INSERT INTO plat(idElement, nomPlat, categorie, entree, plat, dessert) VALUES
(2, 'Lasagnes', 'Pâtes', FALSE, TRUE, FALSE),
(3, 'Xiar Bin', 'Raviolis', TRUE, TRUE, FALSE);

INSERT INTO boisson(nomBoisson, type) VALUES ('Gulden Draak', 'Bière');

INSERT INTO boissonOfferte(idElement, nomBoisson, volume, anneeProduction) VALUES (4, 'Gulden Draak', 3.3, NULL);

INSERT INTO ingredient(nomIngredient, liquide) VALUES
('Cheval', FALSE),
('Pâtes', FALSE),
('Boeuf', FALSE),
('Béchamel', TRUE),
('Oignon', FALSE),
('Cinq épices', FALSE),
('Sauce Soja', TRUE);

INSERT INTO periodeCarte (idCarte, idRestaurant, dateDebut, dateFin) VALUES (1,1,'2016-11-14','2017-01-14');

INSERT INTO quantiteElement (idElement, idCommande, idRestaurant, quantite) VALUES (1,1,1,2),(2,1,1,3);

INSERT INTO prixElement (idElement, idCarte, prixElement) VALUES (1,1,12.5), (2,1,10);

INSERT INTO assocMenuPlat (idMenu, idPlat) VALUES (1, 3);

INSERT INTO quantiteIngredient (idElement , nomIngredient ,quantiteIngredient) VALUES (3,'Boeuf',200);
