/*CREATION DES TABLES SANS CLES ETRANGERES

CREATION DE LA TABLE RANG*/

CREATE TABLE rang
(
    idRang INT(2) AUTO_INCREMENT,
    nomRang VARCHAR(30),

    CONSTRAINT PK_Rang PRIMARY KEY (idRang)
);

/*CREATION DE LA TABLE ADULTE*/

CREATE TABLE adulte
(
    idAdulte INT(4) AUTO_INCREMENT,
    idRang INT(2) DEFAULT 3,
    nom VARCHAR(30),
    prenom VARCHAR(30),
    mail VARCHAR(30),
    tel VARCHAR(11),
    solde DECIMAL(6,2) DEFAULT 0,
    mdp VARCHAR(255),

    CONSTRAINT PK_Adulte PRIMARY KEY (idAdulte),
    CONSTRAINT FK_AdulteRang FOREIGN KEY (idRang) REFERENCES rang(idRang)
);

/*CREATION DE LA TABLE ENFANT*/

CREATE TABLE enfant
(
    idEnfant INT(4) AUTO_INCREMENT,
    nom VARCHAR(30),
    prenom VARCHAR(30),

    CONSTRAINT PK_Enfant PRIMARY KEY (idEnfant)
);

/*CREATION DE LA TABLE PRODUIT*/

CREATE TABLE produit
(
    idProduit INT(4) AUTO_INCREMENT,
    nomProduit VARCHAR(30),
    descProduit VARCHAR(50),
    prix DECIMAL(6,2) DEFAULT 0,

    CONSTRAINT PK_Produit PRIMARY KEY (idProduit)
);

/*CREATION DE LA TABLE ALLERGENE*/

CREATE TABLE allergene
(
    idAllergene INT(3) AUTO_INCREMENT,
    nomAllergene VARCHAR(30),
    descAllergene VARCHAR(50),

    CONSTRAINT PK_Allergene PRIMARY KEY (idAllergene)
);

/*CREATION DE LA TABLE CATEGORIEENFANT*/

CREATE TABLE categorieenfant
(
    idCat INT(2) AUTO_INCREMENT,
    nom VARCHAR(30),

    CONSTRAINT PK_CategorieEnfant PRIMARY KEY (idCat)
);

/*CREATION DE LA TABLE CATEGORIEPRODUIT*/

CREATE TABLE categorieproduit
(
    idCat INT(2) AUTO_INCREMENT,
    nom VARCHAR(30),

    CONSTRAINT PK_CategorieProduit PRIMARY KEY (idCat)
);

/* CREATION DES TABLES NECESSITANT DES CLES ETRANGERES

CREATION DE LA TABLE PARENT*/

CREATE TABLE parent
(
    idAdulte INT(4),
    idEnfant INT(4),

    CONSTRAINT PK_Parent PRIMARY KEY (idAdulte,idEnfant),
    CONSTRAINT FK_AdulteParent FOREIGN KEY(idAdulte) REFERENCES adulte(idAdulte),
    CONSTRAINT FK_EnfantParent FOREIGN KEY(idEnfant) REFERENCES enfant(idEnfant)
);

/*CREATION DE LA TABLE CATENFANT*/

CREATE TABLE catenfant
(
    idEnfant INT(4),
    idCat INT(2),

    CONSTRAINT PK_CatEnfant PRIMARY KEY (idEnfant,idCat),
    CONSTRAINT FK_EnfantCat FOREIGN KEY(idEnfant) REFERENCES enfant(idEnfant),
    CONSTRAINT FK_CatCat FOREIGN KEY(idCat) REFERENCES categorieenfant(idCat)
);

/*CREATION DE LA TABLE STOCK*/

CREATE TABLE stock
(
    idProduit INT(4),
    quantite INT(4) DEFAULT 0,

    CONSTRAINT PK_Stock PRIMARY KEY (idProduit,quantite),
    CONSTRAINT FK_ProduitStock FOREIGN KEY (idProduit) REFERENCES produit(idProduit)
);

/*CREATION DE LA TABLE CONSOMMATION*/

CREATE TABLE consommation
(
    idConso INT(5) AUTO_INCREMENT,
    idEnfant INT(4),
    total DECIMAL(6,2),
    dateConso DATE,

    CONSTRAINT PK_Consommation PRIMARY KEY (idConso,idEnfant),
    CONSTRAINT FK_EnfantConso FOREIGN KEY (idEnfant) REFERENCES enfant(idEnfant)
);

/*CREATION DE LA TABLE DETAILCONSO*/

CREATE TABLE detailconso
(
    idConso INT(5),
    idProduit INT(4),
    quantite INT(2) DEFAULT 0,

    CONSTRAINT PK_DetailConso PRIMARY KEY (idConso,idProduit),
    CONSTRAINT FK_ConsoDetailConso FOREIGN KEY (idConso) REFERENCES consommation(idConso),
    CONSTRAINT FK_ProduitDetailConso FOREIGN KEY (idProduit) REFERENCES produit(idProduit)
);

/*CREATION DE LA TABLE ALLERGIEENFANT*/

CREATE TABLE allergieenfant
(
    idEnfant INT(4),
    idAllergene INT(3),

    CONSTRAINT PK_AllergieEnfant PRIMARY KEY (idEnfant,idAllergene),
    CONSTRAINT FK_EnfantAll_E FOREIGN KEY(idEnfant) REFERENCES enfant(idEnfant),
    CONSTRAINT FK_AllAll_E FOREIGN KEY(idAllergene) REFERENCES allergene(idAllergene)
);

/*CREATION DE LA TABLE ALLERGIEPRODUIT*/

CREATE TABLE allergieproduit
(
    idProduit INT(4),
    idAllergene INT(3),

    CONSTRAINT PK_AllergieProduit PRIMARY KEY (idProduit,idAllergene),
    CONSTRAINT FK_ProduitAll_P FOREIGN KEY(idProduit) REFERENCES produit(idProduit),
    CONSTRAINT FK_AllAllE FOREIGN KEY(idAllergene) REFERENCES allergene(idAllergene)
);

/*CREATION DE LA TABLE COMPTE*/

CREATE TABLE compte
(
    idEnfant INT(4),
    solde DECIMAL(6,2),

    CONSTRAINT PK_Compte PRIMARY KEY (idEnfant),
    CONSTRAINT FK_EnfantCompte FOREIGN KEY (idEnfant) REFERENCES enfant(idEnfant)
);

/*CREATION DE LA TABLE CATPRODUIT*/

CREATE TABLE catproduit
(
    idProduit INT(4),
    idCat INT(2),

    CONSTRAINT PK_CatProduit PRIMARY KEY (idProduit, idCat),
    CONSTRAINT FK_ProduitCatProduit FOREIGN KEY (idProduit) REFERENCES produit(idProduit),
    CONSTRAINT FK_CatCatProduit FOREIGN KEY (idCat) REFERENCES categorieproduit(idCat)
);

/*CREATION DE LA TABLE COURSE*/

CREATE TABLE course
(
    idCourse INT(4) AUTO_INCREMENT,
    idAdulte INT(4),
    total DECIMAL(6,2) DEFAULT 0,
    dateCourse DATE,

    CONSTRAINT PK_Course PRIMARY KEY(idCourse,idAdulte),
    CONSTRAINT FK_AdulteCourse FOREIGN KEY (idAdulte) REFERENCES adulte(idAdulte)
);

/*CREATION DE LA TABLE COURSEDETAIL*/

CREATE TABLE detailcourse
(
    idCourse INT(4),
    idProduit INT(4),
    quantite INT(3),

    CONSTRAINT PK_DetailCourse PRIMARY KEY (idCourse,idProduit),
    CONSTRAINT FK_ProduitDetailCourse FOREIGN KEY (idProduit) REFERENCES course(idCourse)
);

/*CREATION De LA TABLE OPERATIONSOLDE*/

CREATE TABLE operationsolde
(
    idOperation INT(5) PRIMARY KEY AUTO_INCREMENT,
    idAdulte INT(4),
    idEnfant INT(4),
    montant DECIMAL(6,2),
    dateOpe DATE,

    CONSTRAINT FK_AdulteOperation FOREIGN KEY (idAdulte) REFERENCES adulte(idAdulte),
    CONSTRAINT FK_EnfantOperation FOREIGN KEY (idEnfant) REFERENCES enfant(idEnfant)
);

CREATE TABLE connexion
(
    idAdulte INT(4) PRIMARY KEY,
    token VARCHAR(72) DEFAULT NULL,
    CONSTRAINT FK_CONNEXION FOREIGN KEY (idAdulte) REFERENCES adulte(idAdulte)
);

CREATE TABLE stockgestion
(
    idProduit INT(4) PRIMARY KEY,
    alert BOOLEAN DEFAULT FALSE
);