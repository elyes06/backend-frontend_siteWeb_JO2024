CREATE TABLE Ville(
	IdVille VARCHAR(10),
	NomVille VARCHAR(30),
	CONSTRAINT pk_Ville PRIMARY KEY(IdVille)
);

CREATE TABLE Categorie(
	IdCategorie VARCHAR(10),
	NomCategorie VARCHAR(30),
	Genre VARCHAR(1) CHECK(Genre IN('H','F','M')),
	CONSTRAINT pk_Categorie PRIMARY KEY(IdCategorie)	
);

CREATE TABLE Discipline(
	IdDiscipline VARCHAR(10),
	NomDiscipline VARCHAR(30),
	RecordDiscipline VARCHAR(30),
	IdCategorie VARCHAR(10),
	CONSTRAINT pk_Discipline PRIMARY KEY(IdDiscipline),
	CONSTRAINT fk_Discipline FOREIGN KEY(IdCategorie) references Categorie(IdCategorie) ON DELETE Cascade
);

CREATE TABLE Participants(
	IdParticipant VARCHAR(10),
	Nom VARCHAR(30),
	Prenom VARCHAR(30),
	Nationalite VARCHAR(30),
	DateNaissance DATE,
	CONSTRAINT pk_Participants PRIMARY KEY(IdParticipant)
);

CREATE TABLE Entraineurs(
	IdEntraineur VARCHAR(10),
	IdParticipant VARCHAR(10),
	Diplome VARCHAR(30),
	CONSTRAINT fk_Entraineur FOREIGN KEY(IdParticipant) references Participants(IdParticipant) ON DELETE Cascade,
	CONSTRAINT pk_Entraineur PRIMARY KEY(IdEntraineur)
);

CREATE TABLE Athletes(
	IdAthlete VARCHAR(10),
	IdParticipant VARCHAR(10),
	Poids NUMBER(5,2),
	Taille NUMBER(5,2),
	IdEntraineur VARCHAR(10),
	CONSTRAINT fk_Athletes1 FOREIGN KEY(IdParticipant) references Participants(IdParticipant) ON DELETE Cascade,
	CONSTRAINT fk_Athletes2 FOREIGN KEY(IdEntraineur) references Entraineurs(IdEntraineur) ON DELETE Cascade,
	CONSTRAINT pk_Athletes PRIMARY KEY(IdAthlete)
);

CREATE TABLE Personnel(
	IdPersonnel VARCHAR(10),
	IdParticipant VARCHAR(10),
	Fonction VARCHAR(30),
	CONSTRAINT pk_Personnel PRIMARY KEY(IdPersonnel),
	CONSTRAINT fk_Personnel FOREIGN KEY(IdParticipant) references Participants(IdParticipant) ON DELETE Cascade
);

CREATE TABLE Comite(
	IdComite VARCHAR(10),
	IdParticipant VARCHAR(10),
	MdpCrypte VARCHAR(32),
	CONSTRAINT fk_Comite1 FOREIGN KEY(IdParticipant) references Participants(IdParticipant) ON DELETE Cascade,
	CONSTRAINT pk_Comite PRIMARY KEY(IdComite)
);

CREATE TABLE Equipe(
	IdEquipe VARCHAR(10),
	IdEntraineur VARCHAR(10),
	CONSTRAINT pk_Equipe PRIMARY KEY(IdEquipe),
	CONSTRAINT fk_Equipe FOREIGN KEY(IdEntraineur) references Entraineurs(IdEntraineur) ON DELETE Cascade
);

CREATE TABLE Specialise(
	IdAthlete VARCHAR(10),
	IdDiscipline VARCHAR(10),
	CONSTRAINT fk_Specialise1 FOREIGN KEY(IdAthlete) references Athletes(IdAthlete) ON DELETE Cascade,
	CONSTRAINT fk_Specialise2 FOREIGN KEY(IdDiscipline) references Discipline(IdDiscipline) ON DELETE Cascade,
	CONSTRAINT pk_Specialise PRIMARY KEY(IdAthlete,IdDiscipline)
);

CREATE TABLE Competition(
	IdCompetition VARCHAR(10),
	DateCompetition DATE,
	HoraireCompetition DATE,
	PhaseCompetition VARCHAR(20) CHECK(PhaseCompetition IN('Huitieme de Finale','Quart de Finale','Demi Finale','Finale')),
	IdVille VARCHAR(10),
	IdDiscipline VARCHAR(10),
	CONSTRAINT pk_Competition PRIMARY KEY(IdCompetition),
	CONSTRAINT fk_Competition1 FOREIGN KEY(IdDiscipline) references Discipline(IdDiscipline) ON DELETE Cascade,
	CONSTRAINT fk_Competition2 FOREIGN KEY(IdVille) references Ville(IdVille) ON DELETE Cascade
);

CREATE TABLE Immeuble(
	IdImmeuble VARCHAR(10),
	IdVille VARCHAR(10),
	CONSTRAINT fk_Immeuble FOREIGN KEY(IdVille) references Ville(IdVille) ON DELETE Cascade,
	CONSTRAINT pk_Immeuble PRIMARY KEY(IdImmeuble)
);

CREATE TABLE Chambre(
	IdHabitation VARCHAR(10),
	IdChambre VARCHAR(10),
	IdImmeuble VARCHAR(10),
	NbrLits INT,
	CONSTRAINT fk_Chambre FOREIGN KEY(IdImmeuble) references Immeuble(IdImmeuble) ON DELETE Cascade,
	CONSTRAINT pk_Chambre PRIMARY KEY(IdHabitation)
);

CREATE TABLE TypeMedaille(
	IdMedaille INT,
	Medaille VARCHAR(1) CHECK(Medaille IN('N','B','A','O')),
	CONSTRAINT pk_TypeMedaille PRIMARY KEY(IdMedaille)
);

CREATE TABLE A_Palmares(
	IdAthlete VARCHAR(10),
	IdDiscipline VARCHAR(10),
	AnneePalmares INT,
	IdMedaille INT,
	MeilleurRecord VARCHAR(12),
	CONSTRAINT fk_A_Palmares1 FOREIGN KEY(IdAthlete) references Athletes(IdAthlete) ON DELETE Cascade,
	CONSTRAINT fk_A_Palmares2 FOREIGN KEY(IdDiscipline) references Discipline(IdDiscipline) ON DELETE Cascade,
	CONSTRAINT fk_A_Palmares3 FOREIGN KEY(IdMedaille) references TypeMedaille(IdMedaille) ON DELETE Cascade,
	CONSTRAINT pk_A_Palmares PRIMARY KEY(IdAthlete,IdDiscipline,AnneePalmares)
);

CREATE TABLE InscrisA(
    	IdAthlete VARCHAR(10),
    	IdCompetition VARCHAR(10),
    	ResultatAthlete VARCHAR(12),
    	ClassementAthlete INT,
    	IdMedaille INT,
    	RecordBattu VARCHAR(1) CHECK( RecordBattu IN('N','O')),
    	CONSTRAINT fk_InscrisA1 FOREIGN KEY(IdAthlete) references Athletes(IdAthlete) ON DELETE Cascade,
    	CONSTRAINT fk_InscrisA2 FOREIGN KEY(IdCompetition) references Competition(IdCompetition) ON DELETE Cascade,
    	CONSTRAINT fk_InscrisA3 FOREIGN KEY(IdMedaille) references TypeMedaille(IdMedaille) ON DELETE Cascade,
    	CONSTRAINT pk_InscrisA PRIMARY KEY(IdAthlete,IdCompetition)
);

CREATE TABLE InscrisE(
	IdEquipe VARCHAR(10),
    	IdCompetition VARCHAR(10),
    	ResultatEquipe VARCHAR(12),
    	ClassementEquipe INT,
    	IdMedaille INT,
    	RecordBattu VARCHAR(1) CHECK( RecordBattu IN('N','O')),
    	CONSTRAINT fk_InscrisE1 FOREIGN KEY(IdEquipe) references Equipe(IdEquipe) ON DELETE Cascade,
   	CONSTRAINT fk_InscrisE2 FOREIGN KEY(IdCompetition) references Competition(IdCompetition) ON DELETE Cascade,
    	CONSTRAINT fk_InscrisE3 FOREIGN KEY(IdMedaille) references TypeMedaille(IdMedaille) ON DELETE Cascade,
    	CONSTRAINT pk_InscrisE PRIMARY KEY(IdEquipe,IdCompetition)
);

CREATE TABLE Fait_Partie(
	IdEquipe VARCHAR(10),
	IdAthlete VARCHAR(10),
	Statut VARCHAR(10) CHECK(Statut IN('Titulaire','Remplacant')),
	CONSTRAINT fk_Fait_Partie1 FOREIGN KEY(IdEquipe) references Equipe(IdEquipe) ON DELETE Cascade,
	CONSTRAINT fk_Fait_Partie2 FOREIGN KEY(IdAthlete) references Athletes(IdAthlete) ON DELETE Cascade,
	CONSTRAINT pk_Fait_Partie PRIMARY KEY(IdEquipe,IdAthlete)
);

CREATE TABLE Assigne(
	IdPersonnel VARCHAR(10),
	IdCompetition VARCHAR(10),
	CONSTRAINT fk_Assigne1 FOREIGN KEY(IdPersonnel) references Personnel(IdPersonnel) ON DELETE Cascade,
	CONSTRAINT fk_Assigne2 FOREIGN KEY(IdCompetition) references Competition(IdCompetition) ON DELETE Cascade,
	CONSTRAINT pk_Assigne PRIMARY KEY(IdPersonnel,IdCompetition)
);

CREATE TABLE Arbitre(
	IdPersonnel VARCHAR(10),
	IdCategorie VARCHAR(10),
	CONSTRAINT fk_Arbitre1 FOREIGN KEY(IdPersonnel) references Personnel(IdPersonnel) ON DELETE Cascade,
	CONSTRAINT fk_Arbitre2 FOREIGN KEY(IdCategorie) references Categorie(IdCategorie) ON DELETE Cascade,
	CONSTRAINT pk_Arbitre PRIMARY KEY(IdPersonnel,IdCategorie)
);

CREATE TABLE Location(
	IdParticipant VARCHAR(10),
	IdHabitation VARCHAR(10),
	DateDeb DATE,
	DateFin DATE,
	CONSTRAINT fk_Location1 FOREIGN KEY(IdParticipant) references Participants(IdParticipant) ON DELETE Cascade,
	CONSTRAINT fk_Location2 FOREIGN KEY(IdHabitation) references Chambre(IdHabitation) ON DELETE Cascade,
	CONSTRAINT pk_Location PRIMARY KEY(IdParticipant, IdHabitation, DateDeb)
);

CREATE TABLE Rattachement(
	IdPersonnel VARCHAR(10),
	IdVille VARCHAR(10),
	DateDeb DATE,
	DateFin DATE,
	CONSTRAINT fk_Rattachement1 FOREIGN KEY(IdPersonnel) references Personnel(IdPersonnel) ON DELETE Cascade,
	CONSTRAINT fk_Rattachement2 FOREIGN KEY(IdVille) references Ville(IdVille) ON DELETE Cascade,
	CONSTRAINT pk_Rattachement PRIMARY KEY(IdPersonnel, IdVille, DateDeb)
);

INSERT INTO Categorie(IdCategorie, NomCategorie,Genre) VALUES (
	'CAT001','Sport été','M'
);
INSERT INTO Categorie(IdCategorie, NomCategorie,Genre) VALUES (
	'CAT002','Sport hiver','M'
);
INSERT INTO Categorie(IdCategorie, NomCategorie,Genre) VALUES (
	'CAT003','Sport balle','M'
);
INSERT INTO Categorie(IdCategorie, NomCategorie,Genre) VALUES (
	'CAT004','Sport nage','M'
);
INSERT INTO Categorie(IdCategorie, NomCategorie,Genre) VALUES (
	'CAT005','Athletisme','M'
);


INSERT INTO Discipline(IdDiscipline, NomDiscipline, RecordDiscipline, IdCategorie) VALUES (
	'DIS001','100 mètres haies','9.58s','CAT005'
);
INSERT INTO Discipline(IdDiscipline, NomDiscipline, RecordDiscipline, IdCategorie) VALUES (
	'DIS002','Saut à la perche','6.16m','CAT005'
);
INSERT INTO Discipline(IdDiscipline, NomDiscipline, RecordDiscipline, IdCategorie) VALUES (
	'DIS003','Marathon','2h 00m 35s','CAT005'
);
INSERT INTO Discipline(IdDiscipline, NomDiscipline, RecordDiscipline, IdCategorie) VALUES (
	'DIS004','Natation 100m','46.9s','CAT004'
);
INSERT INTO Discipline(IdDiscipline, NomDiscipline, RecordDiscipline, IdCategorie) VALUES (
	'DIS005','Cyclisme sur route','4h 39m 57s','CAT001'
);
INSERT INTO Discipline(IdDiscipline, NomDiscipline, RecordDiscipline, IdCategorie) VALUES (
	'DIS006','Flag Football','7-1','CAT003'
);


INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0001','Camille','Dubois','France',TO_DATE('15/09/1992','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0002','Lucas','Martin','France',TO_DATE('03/11/1990','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0003','Sophie','Leroux','France',TO_DATE('21/07/1991','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0004','Antoine','Dupont','France',TO_DATE('09/04/1993','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0005','Pierre','Moreau','France',TO_DATE('28/02/1992','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0006','Alexandre','Martin','France',TO_DATE('17/06/1990','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0007','Gabriel','Leroy','France',TO_DATE('05/10/1991','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0008','Nicolas','Lambert','France',TO_DATE('24/12/1992','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0009','Baptiste','Girard','France',TO_DATE('12/08/1993','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0010','Mathieu','Renault','France',TO_DATE('30/05/1991','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0011','Vincent','Bernard','France',TO_DATE('18/03/1990','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0012','Martin','Lefbvre','France',TO_DATE('06/01/1993','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0013','Klaus','Muller','Allemagne',TO_DATE('12/05/1992','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0014','Stefan','Schmidt','Allemagne',TO_DATE('03/07/1993','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0015','Andreas','Wagner','Allemagne',TO_DATE('28/11/1990','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0016','Michael','Richter','Allemagne',TO_DATE('20/04/1991','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0017','Lucas','Moretti','Italie',TO_DATE('15/07/1999','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0018','Tyler','Johnson','USA',TO_DATE('12/09/1998','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0019','Ethan','Williams','USA',TO_DATE('25/04/1995','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0020','Brandon','Davis','USA',TO_DATE('07/11/1992','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0021','Nathan','Thompson','USA',TO_DATE('18/07/1993','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0022','Jacob','Martinez','USA',TO_DATE('03/03/1991','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0023','Ryan','Scott','USA',TO_DATE('20/10/1990','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0024','Christopher','White','USA',TO_DATE('15/06/1996','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0025','Javier','Garcia Perez','Espagne',TO_DATE('15/08/1992','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0026','Alejandro','Marinez Lopez','Espagne',TO_DATE('03/11/1991','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0027','Carlos','Rodriguez Fernandez','Espagne',TO_DATE('27/04/1995','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0028','Takashi','Yamamoto','Japon',TO_DATE('17/07/1989','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0029','Haruki','Nakamura','Japon',TO_DATE('24/04/2000','DD/MM/YYYY')
);
INSERT INTO Participants(IdParticipant, Nom, Prenom, Nationalite, DateNaissance) VALUES (
	'PART0030','Li','Wei','Chine',TO_DATE('12/07/1992','DD/MM/YYYY')
);


INSERT INTO Entraineurs(IdEntraineur, IdParticipant, Diplome) VALUES (
	'ENT001','PART0001','Athlétisme'
);
INSERT INTO Entraineurs(IdEntraineur, IdParticipant, Diplome) VALUES (
	'ENT002','PART0013','Sport nage'
);
INSERT INTO Entraineurs(IdEntraineur, IdParticipant, Diplome) VALUES (
	'ENT003','PART0024','Sport balle'
);
INSERT INTO Entraineurs(IdEntraineur, IdParticipant, Diplome) VALUES (
	'ENT004','PART0026','Athlétisme'
);


INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH001','PART0002',73.2,179.00,'ENT001'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH002','PART0003',68.6,169.04,'ENT001'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH003','PART0004',69.8,173.23,'ENT001'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH004','PART0005',57.0,111.11,'ENT001'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH005','PART0006',90.89,196.04,'ENT001'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH006','PART0014',78.8,174.77,'ENT002'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH007','PART0015',72.7,178.5,'ENT002'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH008','PART0018',81.7,179.74,'ENT003'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH009','PART0019',69.6,169.69,'ENT003'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH010','PART0020',76.8,180.01,'ENT003'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH011','PART0021',68.4,179.99,'ENT003'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH012','PART0025',67.8,176.89,'ENT004'
);
INSERT INTO Athletes(IdAthlete, IdParticipant, Poids, Taille, IdEntraineur) VALUES (
	'ATH013','PART0027',60.7,156.08,'ENT004'
);


INSERT INTO Personnel(IdPersonnel, IdParticipant, Fonction) VALUES (
	'PER001','PART0030','Commentateur'
);
INSERT INTO Personnel(IdPersonnel, IdParticipant, Fonction) VALUES (
	'PER002','PART0029','Arbitre'
);
INSERT INTO Personnel(IdPersonnel, IdParticipant, Fonction) VALUES (
	'PER003','PART0028','Arbitre'
);
INSERT INTO Personnel(IdPersonnel, IdParticipant, Fonction) VALUES (
	'PER004','PART0012','Arbitre'
);
INSERT INTO Personnel(IdPersonnel, IdParticipant, Fonction) VALUES (
	'PER005','PART0011','Arbitre'
);
INSERT INTO Personnel(IdPersonnel, IdParticipant, Fonction) VALUES (
	'PER006','PART0010','Maitre Nageur'
);
INSERT INTO Personnel(IdPersonnel, IdParticipant, Fonction) VALUES (
	'PER007','PART0009','Technicien'
);
INSERT INTO Personnel(IdPersonnel, IdParticipant, Fonction) VALUES (
	'PER008','PART0008','Technicien'
);
INSERT INTO Personnel(IdPersonnel, IdParticipant, Fonction) VALUES (
	'PER009','PART0007','Arbitre'
);


INSERT INTO Comite(IdComite, IdParticipant, MdpCrypte) VALUES(
  'COM001', 'PART0016',
  standard_hash('COM010101.@JO2024','MD5')
);
INSERT INTO Comite(IdComite, IdParticipant, MdpCrypte) VALUES(
  'COM002', 'PART0017',
  standard_hash('COM020202.@JO2024','MD5')
);
INSERT INTO Comite(IdComite, IdParticipant, MdpCrypte) VALUES(
  'COM003', 'PART0022',
  standard_hash('COM030303.@JO2024','MD5')
);
INSERT INTO Comite(IdComite, IdParticipant, MdpCrypte) VALUES(
  'COM004', 'PART0023',
    standard_hash('COM040404.@JO2024','MD5')

);



INSERT INTO Equipe(IdEquipe, IdEntraineur) VALUES(
	'EQU001','ENT001'
);
INSERT INTO Equipe(IdEquipe, IdEntraineur) VALUES(
	'EQU002','ENT003'
);
INSERT INTO Equipe(IdEquipe, IdEntraineur) VALUES(
	'EQU003','ENT002'
);
INSERT INTO Equipe(IdEquipe, IdEntraineur) VALUES(
	'EQU004','ENT004'
);


INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH001','DIS001'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH002','DIS001'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH003','DIS001'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH004','DIS001'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH005','DIS001'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH006','DIS001'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH007','DIS001'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH008','DIS006'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH009','DIS006'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH010','DIS006'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH011','DIS006'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH012','DIS001'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH013','DIS001'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH001','DIS006'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH002','DIS006'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH003','DIS006'
);
INSERT INTO Specialise(IdAthlete, IdDiscipline) VALUES(
	'ATH004','DIS006'
);


INSERT INTO Ville(IdVille, NomVille) VALUES (
	'75056','Paris'
);
INSERT INTO Ville(IdVille, NomVille) VALUES (
	'13055','Marseille'
);
INSERT INTO Ville(IdVille, NomVille) VALUES (
	'69123','Lyon'
);
INSERT INTO Ville(IdVille, NomVille) VALUES (
	'31555','Toulouse'
);
INSERT INTO Ville(IdVille, NomVille) VALUES (
	'O6088','Nice'
);



INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0001',TO_DATE('15/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Huitieme de Finale','75056','DIS001'
);
INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0002',TO_DATE('18/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Quart de Finale','75056','DIS001'
);
INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0003',TO_DATE('21/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Demi Finale','75056','DIS001'
);
INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0004',TO_DATE('24/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Finale','75056','DIS001'
);
INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0005',TO_DATE('17/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Finale','75056','DIS001'
);
INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0006',TO_DATE('20/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Quart de Finale','75056','DIS001'
);
INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0007',TO_DATE('23/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Demi Finale','75056','DIS001'
);
INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0008',TO_DATE('26/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Finale','75056','DIS001'
);
INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0009',TO_DATE('29/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Finale','75056','DIS006'
);
INSERT INTO Competition(IdCompetition, DateCompetition, HoraireCompetition, PhaseCompetition, IdVille, IdDiscipline) VALUES (
	'COM0010',TO_DATE('29/07/2024','DD/MM/YYYY'),TO_DATE('15:00:00','HH24:MI:SS'),'Finale','75056','DIS002'
);


INSERT INTO TypeMedaille(IdMedaille, Medaille) VALUES(
	4,'N'
);
INSERT INTO TypeMedaille(IdMedaille, Medaille) VALUES(
	3,'B'
);
INSERT INTO TypeMedaille(IdMedaille, Medaille) VALUES(
	2,'A'
);
INSERT INTO TypeMedaille(IdMedaille, Medaille) VALUES(
	1,'O'
);


INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH001','DIS001',2023,2,'10.25s'
);
INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH001','DIS001',2022,4,'9.57s'
);
INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH001','DIS001',2021,1,'8.27s'
);
INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH002','DIS001',2023,3,'11.11s'
);
INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH002','DIS001',2022,1,'8.27s'
);
INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH013','DIS001',2021,4,'9.57s'
);
INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH012','DIS001',2021,4,'11.34s'
);
INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH012','DIS001',2020,4,'11.54s'
);
INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH002','DIS001',2020,2,'9.48s'
);
INSERT INTO A_Palmares(IdAthlete, IdDiscipline, AnneePalmares, IdMedaille, MeilleurRecord) VALUES(
	'ATH012','DIS001',2019,1,'11.54s'
);



INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH001','COM0001','9.58s',8,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH002','COM0001','8.26s',7,4,'O'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH001','COM0002','10.55s',5,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH002','COM0002','12.57s',9,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH001','COM0003','9.58s',4,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH002','COM0003','9.68s',5,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH001','COM0004','10.46s',4,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH002','COM0004','9.58s',1,1,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH001','COM0005','9.58s',5,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH002','COM0005','9.98s',6,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH001','COM0006','10.26s',6,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH002','COM0006','11.26s',7,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH001','COM0007','9.01s',6,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH002','COM0007','11.04s',9,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH001','COM0008','12.59s',9,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH003','COM0008','13.00s',10,4,'N'
);
INSERT INTO InscrisA(IdAthlete, IdCompetition, ResultatAthlete, ClassementAthlete, IdMedaille, RecordBattu) VALUES(
    'ATH004','COM0010','6.15m',1,1,'O'
);


INSERT INTO InscrisE(IdEquipe, IdCompetition, ResultatEquipe, ClassementEquipe, IdMedaille,RecordBattu) VALUES(
    'EQU001','COM0009','8',1,1,'O'
);
INSERT INTO InscrisE(IdEquipe, IdCompetition, ResultatEquipe, ClassementEquipe, IdMedaille,RecordBattu) VALUES(
    'EQU002','COM0009','2',2,2,'N'
);


INSERT INTO Fait_Partie(IdEquipe,IdAthlete,Statut) VALUES (
	'EQU001','ATH001','Titulaire'
);
INSERT INTO Fait_Partie(IdEquipe,IdAthlete,Statut) VALUES (
	'EQU001','ATH002','Remplacant'
);
INSERT INTO Fait_Partie(IdEquipe,IdAthlete,Statut) VALUES (
	'EQU001','ATH003','Titulaire'
);
INSERT INTO Fait_Partie(IdEquipe,IdAthlete,Statut) VALUES (
	'EQU001','ATH004','Titulaire'
);
INSERT INTO Fait_Partie(IdEquipe,IdAthlete,Statut) VALUES (
	'EQU002','ATH008','Titulaire'
);
INSERT INTO Fait_Partie(IdEquipe,IdAthlete,Statut) VALUES (
	'EQU002','ATH009','Titulaire'
);
INSERT INTO Fait_Partie(IdEquipe,IdAthlete,Statut) VALUES (
	'EQU002','ATH010','Titulaire'
);
INSERT INTO Fait_Partie(IdEquipe,IdAthlete,Statut) VALUES (
	'EQU002','ATH011','Remplacant'
);


INSERT INTO Arbitre(IdPersonnel, IdCategorie) VALUES (
	'PER002','CAT005'
);
INSERT INTO Arbitre(IdPersonnel, IdCategorie) VALUES (
	'PER003','CAT005'
);
INSERT INTO Arbitre(IdPersonnel, IdCategorie) VALUES (
	'PER004','CAT005'
);
INSERT INTO Arbitre(IdPersonnel, IdCategorie) VALUES (
	'PER005','CAT005'
);
INSERT INTO Arbitre(IdPersonnel, IdCategorie) VALUES (
	'PER009','CAT005'
);
INSERT INTO Arbitre(IdPersonnel, IdCategorie) VALUES (
	'PER002','CAT003'
);
INSERT INTO Arbitre(IdPersonnel, IdCategorie) VALUES (
	'PER005','CAT003'
);
INSERT INTO Arbitre(IdPersonnel, IdCategorie) VALUES (
	'PER009','CAT003'
);


INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER002','COM0002'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER002','COM0006'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER003','COM0002'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER009','COM0006'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER002','COM0001'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER002','COM0003'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER002','COM0004'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER002','COM0005'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER002','COM0007'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER002','COM0008'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER009','COM0009'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER001','COM0001'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER001','COM0002'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER001','COM0003'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER001','COM0004'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER001','COM0005'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER001','COM0006'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER001','COM0007'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER001','COM0008'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER001','COM0009'
);
INSERT INTO Assigne(IdPersonnel,IdCompetition) VALUES (
	'PER007','COM0009'
);


INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55ARE','75056'
);
INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55ARB','75056'
);
INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55AEE','75056'
);
INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55ARA','13055'
);
INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55ARZ','75056'
);
INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55ARC','75056'
);
INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55ARP','13055'
);
INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55ARD','13055'
);
INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55ARK','13055'
);
INSERT INTO Immeuble(IdImmeuble, IdVille) VALUES (
	'CF55ARL','75056'
);


INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0001','1','CF55ARE',3
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0002','2','CF55ARE',3
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0003','3','CF55ARE',2
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0004','4','CF55ARE',3
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0005','5','CF55ARE',4
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0006','1','CF55ARB',3
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0007','2','CF55ARB',5
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0008','3','CF55ARB',3
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0009','4','CF55ARB',2
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0010','5','CF55ARB',3
);
INSERT INTO Chambre(IdHabitation, IdChambre, IdImmeuble, NbrLits) VALUES (
	'HAB0011','6','CF55ARB',6
);

INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0001','HAB0001',TO_DATE('20/07/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0002','HAB0001',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0003','HAB0001',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0004','HAB0002',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0005','HAB0002',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0006','HAB0002',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0007','HAB0003',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0008','HAB0003',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0009','HAB0004',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0010','HAB0004',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0011','HAB0004',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0012','HAB0005',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0013','HAB0005',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0014','HAB0005',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0015','HAB0005',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0016','HAB0006',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0017','HAB0006',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0018','HAB0006',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0019','HAB0007',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0020','HAB0007',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0021','HAB0007',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0022','HAB0007',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0023','HAB0007',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0024','HAB0008',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0025','HAB0008',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0026','HAB0008',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0027','HAB0009',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0028','HAB0009',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0029','HAB0010',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);
INSERT INTO Location (IdParticipant, IdHabitation, DateDeb, DateFin) VALUES (
	'PART0030','HAB0010',TO_DATE('20/08/2024','DD/MM/YYYY'),TO_DATE('05/09/2024','DD/MM/YYYY')
);

INSERT INTO Rattachement(IdPersonnel, IdVille, DateDeb, DateFin) VALUES (
	'PER001','75056',TO_DATE('01/01/2024','DD/MM/YYYY'),TO_DATE('31/12/2024','DD/MM/YYYY')
);
INSERT INTO Rattachement(IdPersonnel, IdVille, DateDeb, DateFin) VALUES (
	'PER002','75056',TO_DATE('01/01/2024','DD/MM/YYYY'),TO_DATE('31/12/2024','DD/MM/YYYY')
);
INSERT INTO Rattachement(IdPersonnel, IdVille, DateDeb, DateFin) VALUES (
	'PER003','75056',TO_DATE('01/01/2024','DD/MM/YYYY'),TO_DATE('31/12/2024','DD/MM/YYYY')
);
INSERT INTO Rattachement(IdPersonnel, IdVille, DateDeb, DateFin) VALUES (
	'PER004','75056',TO_DATE('01/01/2024','DD/MM/YYYY'),TO_DATE('31/12/2024','DD/MM/YYYY')
);
INSERT INTO Rattachement(IdPersonnel, IdVille, DateDeb, DateFin) VALUES (
	'PER005','75056',TO_DATE('01/01/2024','DD/MM/YYYY'),TO_DATE('31/12/2024','DD/MM/YYYY')
);
INSERT INTO Rattachement(IdPersonnel, IdVille, DateDeb, DateFin) VALUES (
	'PER006','75056',TO_DATE('01/01/2024','DD/MM/YYYY'),TO_DATE('31/12/2024','DD/MM/YYYY')
);
INSERT INTO Rattachement(IdPersonnel, IdVille, DateDeb, DateFin) VALUES (
	'PER007','75056',TO_DATE('01/01/2024','DD/MM/YYYY'),TO_DATE('31/12/2024','DD/MM/YYYY')
);
INSERT INTO Rattachement(IdPersonnel, IdVille, DateDeb, DateFin) VALUES (
	'PER008','75056',TO_DATE('01/01/2024','DD/MM/YYYY'),TO_DATE('31/12/2024','DD/MM/YYYY')
);
INSERT INTO Rattachement(IdPersonnel, IdVille, DateDeb, DateFin) VALUES (
	'PER009','75056',TO_DATE('01/01/2024','DD/MM/YYYY'),TO_DATE('31/12/2024','DD/MM/YYYY')
);
COMMIT;