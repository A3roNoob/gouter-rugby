/* CREATION DES RANGS */
INSERT INTO rang(nomRang) VALUES ('President'), ('Parent benevole'), ('Parent');

/* CREATION DES ADULTES*/
INSERT INTO adulte (nom, prenom, tel, mail, mdp) VALUES
  ('Horn','Merritt','0384373435','merritt.horn@neque.net', '31a9d83e188e5c3af82911c2f7b6fd8f'),
  ('Mcguire','Blythe','0206928802','blythe.mcguire@mit.edu', '9622537551976859729f86d870fde25b'),
  ('Larson','Gannon','0781811731','test@test.ca', '098f6bcd4621d373cade4e832627b4f6');

INSERT INTO adulte (nom, prenom, tel, mail) VALUES
  ('Deleon','Irma','0373791995','metus.In@dictumerat.com'),
  ('Herrera','Britanney','0651833598','egestas.phatra@atarcubulum.net'),
  ('Rhodes','Montana','0917754526','lobortis.quam@semperNam.org'),
  ('Burton','Pandora','0336760472','ornare.elit@ligulaAliq.ca'),
  ('Glenn','Leonard','0170010620','Lorem.ipsum@feugiat.ca'),
  ('Riddle','Kylan','0694061757','dictum@arcu.co.uk'),
  ('Sandoval','Francesca','0409725287','mollis@cursus.org'),
  ('Owens','Reed','0472810301','erat.vel.pede@nislQuisque.org'),
  ('Carson','May','0331971341','Praesent.eu@Curausornare.edu'),
  ('Bennett','Heidi','0348322216','tincidunt.ac@eu.co.uk'),
  ('Sampson','Garrison','0799637353','In@lectusconvallisest.com'),
  ('Mccoy','Timon','0275267553','eget.magnasse@enimSed.com'),
  ('Carey','Kevyn','0134369953','vulputate@et.net'),
  ('Malone','Igor','0896824715','Fusce@ac.net'),
  ('Burns','Kato','0670258859','diam@lorem.ca'),
  ('Prince','Ethan','0999762336','nec@scealiquet.ca'),
  ('Stanley','Kaitlin','0763798484','laoreet@justo.net');

/*INSERTION DES ENFANTS*/
INSERT INTO enfant (nom,prenom) VALUES
  ('Horn','Anne'),
  ('Horn','Scott'),
  ('Mcguire','Cade'),
  ('Larson','Kamal'),
  ('Larson','Flynn'),
  ('Larson','Timon'),
  ('Deleon','Mollie'),
  ('Herrera','Vielka'),
  ('Rhodes','Genevieve'),
  ('Burton','Malachi'),
  ('Burton','Remedios'),
  ('Glenn','Jessamine'),
  ('Riddle','Thomas'),
  ('Owens','Rigel'),
  ('Carson','Craig'),
  ('Carson','Ivan'),
  ('Bennett','Zenia'),
  ('Sampson','Blake'),
  ('Sampson','Arden'),
  ('Mccoy','Jessamine'),
  ('Carey','Erasmus'),
  ('Carey','Akeem'),
  ('Carey','Dillon'),
  ('Malone','Lev'),
  ('Malone','Kessie'),
  ('Burns','Illiana'),
  ('Burns','Jaime'),
  ('Burns','Fulton'),
  ('Prince','Hoyt'),
  ('Stanley','Jack');

/*ASSOCIATION DES ENFANTS AVEC LES PARENTS */
INSERT INTO parent (idAdulte, idEnfant) VALUES
  (1,1),(1,2),            /*Horn*/
  (2,3),                  /*McGuire*/
  (3,4),(3,5),(3,6),       /*Larson*/
  (4,7),                  /*Deleon*/
  (5,8),                  /*Herrera*/
  (6,9),                  /*Rhodes*/
  (7,10),(7,11),          /*Burton*/
  (8,12),                 /*Glenn*/
  (9,13),                 /*Riddle*/
  (11,14),                /*Owens*/
  (12,15),(12,16),        /*Carson*/
  (13,17),                /*Bennett*/
  (14,18),(14,19),        /*Sampson*/
  (15,20),                /*McCoy*/
  (16,21),(16,22),(16,23),/*Carey*/
  (17,24),(17,25),        /*Malone*/
  (18,26),(18,27),(18,28), /*Burns*/
  (19,29),                /*Prince*/
  (20,30);                /*Stanley*/

INSERT INTO compte(idEnfant, solde) VALUES
  (1, 90.02),
  (2, 176.36),
  (3, 155.27),
  (4, 195.48),
  (5, 98.96),
  (6, 174.71),
  (7, 167.53),
  (8, 20.56),
  (9, 29.86),
  (10, 97.46),
  (11, 189.94),
  (12, 188.35),
  (13, 13.73),
  (14, 187.32),
  (15, 94.40),
  (16, 118.07),
  (17, 13.71),
  (18, 32.57),
  (19, 87.71),
  (20, 49.53),
  (21, 22.72),
  (22, 38.42),
  (23, 131.78),
  (24, 19.90),
  (25, 56.26),
  (26, 66.40),
  (27, 8.83),
  (28, 83.96),
  (29, 162.75),
  (30, 124.05);


INSERT INTO categorieenfant(nom) VALUES
  ('Moins de 6'),
  ('Moins de 8'),
  ('Moins de 10'),
  ('Moins de 12'),
  ('Moins de 14');

INSERT INTO catenfant VALUES
  (1, 5),
  (2, 2),
  (3, 1),
  (4, 4),
  (5, 3),
  (6, 1),
  (7, 3),
  (8, 5),
  (9, 2),
  (10, 1),
  (11, 5),
  (12, 2),
  (13, 5),
  (14, 5),
  (15, 5),
  (16, 4),
  (17, 4),
  (18, 4),
  (19, 2),
  (20, 2),
  (21, 1),
  (22, 2),
  (23, 3),
  (24, 4),
  (25, 3),
  (26, 2),
  (27, 4),
  (28, 4),
  (29, 3),
  (30, 4);

INSERT INTO allergene(nomAllergene, descAllergene) VALUES
  ('gluten', 'ble, seigle, orge, avoine, epeautre, kamut...'),
  ('oeufs', 'Oeufs et produits a base d\'oeufs'),
  ('lait', 'Lait et produits a base de lait de vache'),
  ('arachide', 'Arachide et produits a base d\'arachide'),
  ('soja', 'Soja et produits a base de soja');

INSERT INTO allergieenfant VALUES
  (2, 2),
  (3, 4),
  (17, 1),
  (18, 4),
  (19, 5),
  (21, 3),
  (22, 2),
  (26, 5),
  (27, 1);

INSERT INTO categorieproduit(nom) VALUES
  ('Boisson'),
  ('Sucrerie'),
  ('Gateau');

INSERT INTO produit(nomProduit, descProduit, prix) VALUES
  ('Chocolat Blanc', '1 cons = 1/12', 0.20),
  ('Chocolat au lait', '1 cons = 1/12', 0.18),
  ('Paquet de bonbon', '1 cons = 1', 1.50),
  ('Cannette', '1 cons = 1', 1.20),
  ('Savanne', '1 cons = 1/20', 0.40),
  ('Oreo', '1 cons = 2/14', 0.30),
  ('Chocolat noir', '1 cons = 1/12', 0.20),
  ('Choco BN', '1 cons = 2 gateaux', 2.15),
  ('Pied de porc', '1 cons = 2 pieds', 27.65),
  ('Panse de brebis farcie', '1 cons = 1 panse', 12.20),
  ('Goulash', '1 cons = 700g', '14.00'),
  ('Jambes de grenouilles', '1 cons = 5 jambes', 5.50);

INSERT INTO produitcompose(nomProduit, descProduit) VALUES ('Gouter simple', 'Savanne/Canette'),
  ('Gouter FRANCAIS', 'Jambes de grenouilles/Pied de porc/Canette');

INSERT INTO composproduit VALUES (1, 5),(1, 4), /* un gouter composé d'un savanne et d'une canette*/
  (2, 9), (2, 12), (2, 4);

INSERT INTO catproduit VALUES
  (1, 2),
  (2, 2),
  (3, 2),
  (4, 1),
  (5, 3),
  (6, 3);

INSERT INTO allergieproduit VALUES
  (2, 3),
  (5, 3),
  (5, 2);


/* NB present dans stock = nb*fract ex : 3 plaquettes de chocolat blanc = 3 * 12 = 36*/
INSERT INTO stock VALUES
  (1, 36),
  (2, 48),
  (3, 20),
  (4, 20),
  (5, 100),/* 100 = 5 * 1 savanne (1 savanne = 20/20) */
  (6, 42);