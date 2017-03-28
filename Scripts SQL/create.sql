DROP TABLE Manager;
DROP TABLE Serveur;
DROP TABLE Cuisinier;
DROP TABLE PeriodeCarte;
DROP TABLE PrixElement;
DROP TABLE QuantiteElement;
DROP TABLE BoissonOfferte;
DROP TABLE AssocMenuPlat;
DROP TABLE Menu;
DROP TABLE QuantiteIngredient;
DROP TABLE Ingredient;
DROP TABLE Plat;
DROP TABLE Commande;
DROP TABLE Boisson;
DROP TABLE Carte;
DROP TABLE Element;
DROP TABLE Restaurant;

CREATE TABLE restaurant 
	(idRestaurant INTEGER NOT NULL,
	nomRestaurant VARCHAR(50) NOT NULL,
	adresse VARCHAR(100) NOT NULL,
	ville VARCHAR(50) NOT NULL,
	pays VARCHAR(50) NOT NULL,
	PRIMARY KEY (idRestaurant),
	UNIQUE (nomRestaurant,adresse,ville,pays));

CREATE TABLE cuisinier 
	(idEmploye INTEGER NOT NULL,
	nom VARCHAR(50) NOT NULL,
	prenom VARCHAR(50) NOT NULL,
	dateNaissance DATE NOT NULL,
	dateAnciennete DATE NOT NULL,
	specialite VARCHAR(50) NOT NULL,
	idRestaurant INTEGER,
	PRIMARY KEY (idEmploye),
	FOREIGN KEY (idRestaurant) REFERENCES restaurant(idRestaurant),
	UNIQUE (nom,prenom,dateNaissance)
	);

CREATE TABLE manager
	(idEmploye INTEGER NOT NULL,
	nom VARCHAR(50) NOT NULL,
	prenom VARCHAR(50) NOT NULL,
	dateNaissance DATE NOT NULL,
	dateAnciennete DATE NOT NULL,
	idRestaurant INTEGER,
	PRIMARY KEY (idEmploye),
	FOREIGN KEY (idRestaurant) REFERENCES restaurant(idRestaurant),
	UNIQUE (nom,prenom,dateNaissance)
	);

CREATE TABLE serveur 
	(idEmploye INTEGER NOT NULL,
	nom VARCHAR(50) NOT NULL,
	prenom VARCHAR(50) NOT NULL,
	dateNaissance DATE NOT NULL,
	dateAnciennete DATE NOT NULL,
	authorisationAccueil BOOLEAN NOT NULL,
	idRestaurant INTEGER,
	PRIMARY KEY (idEmploye),
	FOREIGN KEY (idRestaurant) REFERENCES restaurant(idRestaurant),
	UNIQUE (nom,prenom,dateNaissance)
	);

CREATE TABLE carte 
	(idCarte INTEGER NOT NULL,
	nomCarte VARCHAR(60) NOT NULL,
	PRIMARY KEY (idCarte)
	);

CREATE TABLE commande
	(idCommande INTEGER,
	idRestaurant INTEGER,
	dateCommande DATE NOT NULL,
	PRIMARY KEY(idCommande, idRestaurant),
	FOREIGN KEY (idRestaurant) REFERENCES restaurant(idRestaurant)
	);


CREATE TABLE element
	(idELement INTEGER PRIMARY KEY
	);

CREATE TABLE menu
	(idElement INTEGER,
	nomMenu VARCHAR(50) NOT NULL,
	PRIMARY KEY (idElement),
	FOREIGN KEY (idElement) REFERENCES element(idElement)	
	);

CREATE TABLE plat 
	(idElement INTEGER PRIMARY KEY,
	nomPlat VARCHAR(50),
	categorie VARCHAR(50) NOT NULL,
	entree BOOLEAN NOT NULL,
	plat BOOLEAN NOT NULL,
	dessert BOOLEAN NOT NULL,
	FOREIGN KEY(idElement) REFERENCES element(idElement)
	);


CREATE TABLE boisson
	(nomBoisson VARCHAR(50),
	type VARCHAR(40),
	PRIMARY KEY(nomBoisson)
	);

CREATE TABLE boissonOfferte
	(idElement INTEGER PRIMARY KEY,
	nomBoisson VARCHAR(50) NOT NULL,
	volume NUMERIC NOT NULL,
	anneeProduction INTEGER,
	UNIQUE(nomBoisson, volume, anneeProduction),
	FOREIGN KEY(idElement) REFERENCES element(idElement),
	FOREIGN KEY (nomBoisson) REFERENCES boisson(nomBoisson),
	CHECK(volume > 0)
	);


CREATE TABLE ingredient 
	(nomIngredient VARCHAR(50),
	liquide BOOLEAN NOT NULL,
	PRIMARY KEY (nomIngredient)
	);

CREATE TABLE periodeCarte
	(idCarte INTEGER,
	idRestaurant INTEGER,
	dateDebut DATE,
	dateFin DATE NOT NULL,
	PRIMARY KEY(idCarte, idRestaurant, dateDebut),
	FOREIGN KEY (idCarte) REFERENCES carte(idCarte),
	FOREIGN KEY (idRestaurant) REFERENCES restaurant(idRestaurant),
	CHECK (dateDebut<dateFin)
	);

CREATE TABLE quantiteElement
	(idElement INTEGER,
	idCommande INTEGER,
	idRestaurant INTEGER,
	quantite INTEGER NOT NULL,
	PRIMARY KEY(idElement, idCommande, idRestaurant),
	FOREIGN KEY (idElement) REFERENCES element(idElement),
	FOREIGN KEY (idCommande, idRestaurant) REFERENCES commande(idCommande, idRestaurant),
	CHECK(quantite > 0)
	);

CREATE TABLE prixElement
	(idElement INTEGER,
	idCarte INTEGER,
	prixElement NUMERIC NOT NULL,
	PRIMARY KEY(idElement, idCarte),
	FOREIGN KEY(idElement) REFERENCES element(idElement),
	FOREIGN KEY(idCarte) REFERENCES carte(idCarte),
	CHECK(prixElement > 0)	
	);

CREATE TABLE assocMenuPlat
	(idMenu INTEGER,
	idPlat INTEGER,
	PRIMARY KEY(idMenu, idPlat),
	FOREIGN KEY(idMenu) REFERENCES menu(idElement),
	FOREIGN KEY(idPlat) REFERENCES plat(idElement)
	);

CREATE TABLE quantiteIngredient
	(idElement INTEGER,
	nomIngredient VARCHAR(50),
	quantiteIngredient NUMERIC NOT NULL,
	PRIMARY KEY(idElement, nomIngredient),
	FOREIGN KEY (idElement) REFERENCES element(idElement),
	CHECK(quantiteIngredient > 0)
	);
