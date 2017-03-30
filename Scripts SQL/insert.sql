INSERT INTO restaurant(idRestaurant, nomRestaurant, adresse, ville, pays) VALUES
(1, 'French Touch', '12 rue du Louvre', 'Compiègne', 'France'),
(2, 'Bistrot du Terroir', '4 rue de la Pêche', 'Compiègne', 'France');

INSERT INTO cuisinier(idEmploye, nom, prenom, dateNaissance, dateAnciennete, specialite, idRestaurant) VALUES
(1, 'Bonneau', 'Jean', '1956-03-13', '2003-02-06', 'rôtisseur', 1),
(2, 'Dive', 'Nathan', '1994-05-17', '2015-11-02', 'compositeur de salades', 2),
(5, 'Kitori', 'Koya', '1980-04-02', '2010-09-10', 'sushi master', 2),
(7, 'Cantonès', 'Henri', '1985-06-11', '2012-09-15', 'expert friture', 2),
(8, 'Hauven', 'Beth', '1984-03-04', '2009-06-14', 'compositeur de salades', 2);

INSERT INTO manager(idEmploye, nom, prenom, dateNaissance, dateAnciennete, idRestaurant) VALUES
(3, 'Verlinfini', 'Satan', '1962-01-24', '2010-02-02', 1),
(6, 'Mégalo', 'Paul', '1994-12-18', '2016-01-07', 2);

INSERT INTO serveur(idEmploye, nom, prenom, dateNaissance, dateAnciennete, authorisationAccueil, idRestaurant) VALUES
(4, 'Sclave', 'Ashley', '1998-03-15', '2014-01-09', TRUE, 2);

INSERT INTO carte(idCarte, nomCarte) VALUES
(1, 'Carte de Printemps'),
(2, 'Carte des Burgers'),
(3, 'Carte Maestro');

INSERT INTO commande(idCommande, idRestaurant, dateCommande) VALUES (1, 1, '2016-04-12');

INSERT INTO element(idElement) VALUES (1),(2),(3),(4),(5),(6),(7),(8),(9),(10),(11),(12),(13),(14),(15),(16),(17),(18),(19),(20),(21),(22),(23),(24);

INSERT INTO menu(idElement, nomMenu) VALUES
(1, 'Saveurs asiatiques'),
(9, 'Plaisir du terroir'),
(13, 'Menu Etudiant'),
(16, 'Menu Allegro'),
(21, 'Menu Mozart');

INSERT INTO plat(idElement, nomPlat, categorie, entree, plat, dessert) VALUES
(2, 'Lasagnes', 'Pâtes', FALSE, TRUE, FALSE),
(5, 'Baozi', 'Raviolis', TRUE, TRUE, FALSE),
(6, 'Magret de canard à la figue', 'Viandes', FALSE, TRUE, FALSE),
(7, 'Duo de foies gras de canard', 'Pâtés', TRUE, FALSE, FALSE),
(8, 'Gazpacho aux tomates séchées', 'Soupes', TRUE, FALSE, FALSE),
(3, 'Xiar Bing', 'Raviolis', TRUE, TRUE, FALSE),
(10, 'Fromage blanc fermier', 'Fromages', FALSE, FALSE, TRUE),
(11, 'Gâteaux de riz haricot rouge', 'Pâtisseries', FALSE, FALSE, TRUE),
(14, 'Frites fraîches', 'Accompagnements', TRUE, TRUE, FALSE),
(15, 'Burger', 'Sandwiches', FALSE, TRUE, FALSE),
(17, 'Symphonie de gambas en sauce ciboulette', 'Salades', TRUE, TRUE, FALSE),
(18, 'Sonate de porc et ses notes de céleri croquant', 'Viandes', FALSE, TRUE, FALSE),
(19, 'Sorbet macadamia en ballade', 'Crèmes glacées', FALSE, FALSE, TRUE),
(22, 'Legato de stracciatella fondante', 'Crèmes glacées', FALSE, FALSE, TRUE),
(23, 'Aligot andante velouté et sa fondue aux échalotes', 'Accompagnements', FALSE, TRUE, FALSE),
(24, 'Poireaux vinaigrette en Si mineur', 'Salades', TRUE, FALSE, FALSE);

INSERT INTO boisson(nomBoisson, type) VALUES
('Gulden Draak', 'Bière'),
('Thé au riz grillé', 'Thé'),
('Musi-cola', 'Soda');

INSERT INTO boissonOfferte(idElement, nomBoisson, volume, anneeProduction) VALUES
(4, 'Gulden Draak', 3.3, NULL),
(12, 'Thé au riz grillé', 3.3, NULL),
(20, 'Musi-cola', 2.5, NULL);

INSERT INTO ingredient(nomIngredient, liquide) VALUES
('Cheval', FALSE),
('Pâtes', FALSE),
('Boeuf', FALSE),
('Béchamel', TRUE),
('Oignon', FALSE),
('Cinq épices', FALSE),
('Sauce Soja', TRUE);

INSERT INTO periodeCarte (idCarte, idRestaurant, dateDebut, dateFin) VALUES
(1,2,'2016-11-14','2017-01-14'),
(2,1,'2016-11-14','2017-01-14'),
(3,2,'2017-01-14','2017-11-14');

INSERT INTO quantiteElement (idElement, idCommande, idRestaurant, quantite) VALUES
(1,1,1,2),
(2,1,1,3);

INSERT INTO prixElement (idElement, idCarte, prixElement) VALUES
(1,1,15.50),
(2,1,10),
(3,1,4),
(4,2,1.90),
(5,1,4),
(6,1,15.90),
(7,1,7.80),
(8,1,3),
(9,1,28),
(10,1,5.50),
(11,1,6),
(12,1,3.50),
(13,2,3.50),
(14,2,4.90),
(15,2,6.90),
(16,3,29),
(17,3,7.90),
(18,3,16.90),
(19,3,6.90),
(20,3,5.50),
(21,3,24.80),
(22,3,6.50),
(23,3,15.50),
(24,3,7.50);

INSERT INTO assocMenuPlat (idMenu, idPlat) VALUES
(1, 3),(1, 5),(1,11),
(9, 6),(9, 7),(9, 8),(9,10),
(13,14),(13,15),
(16,17),(16,18),(16,19),
(21,22),(21,23),(21,24);

INSERT INTO quantiteIngredient (idElement , nomIngredient ,quantiteIngredient) VALUES
(3,'Boeuf',200);
