CREATE DATABASE IF NOT EXISTS bdd_pod;

USE bdd_pod;

CREATE TABLE IF NOT EXISTS joueurs
(
    ID_Joueurs                      INTEGER     AUTO_INCREMENT 	        NOT NULL                            COMMENT 'Permet de générer une id pour chaque nouvel utilisateur sur le jeu',
    Pseudo 		            CHAR(20) 		                NOT NULL 		            COMMENT	'Le nouveau joueur se définit un Pseudo qui permet la reconnexion cette valeur est unique',
    Pass                            VARCHAR(70)                         NOT NULL                            COMMENT 'Le mot de passe du joueur',
    Mail                            CHAR(50)                            NOT NULL            	            COMMENT 'Permet d\'éditer le profil unique et permet la reconnexion',
    Valide 		            BOOLEAN 		                NOT NULL	DEFAULT FALSE       COMMENT 'Permet de savoir si l\'utilisateur à validé son compte',
    Chaine_Validation               CHAR(10)                            NOT NULL		            COMMENT 'Coorespond à la chaine envoyé par mail pour finaliser la création du compte',
    Fonds 		            SMALLINT 		                NOT NULL	DEFAULT 0           COMMENT 'Ressources du joueur issue de ses différentes attaques, peut être dilapidée par des joueurs adverses',
    Fonds_Securise 	            SMALLINT 		                NOT NULL	DEFAULT 0           COMMENT 'Ressources du joueur inviolable par un adversaire, dépend du niveau du joueur',
    Revenus 		            SMALLINT 		                NOT NULL	DEFAULT 0	    COMMENT 'Gain régulier du joueur',
    Niveau		            SMALLINT		                NOT NULL	DEFAULT 1           COMMENT 'Niveau du joueur, évolue suivant les attaques du joueur',
    CONSTRAINT 		            pk_ID_Joueurs 	                PRIMARY KEY(ID_Joueurs)             COMMENT 'Clé primaire de l\'identifiant joueur utile pour la table ordinateurs et virus '
);

CREATE TABLE IF NOT EXISTS ordinateurs
(
    ID_Ordinateurs 		    INTEGER AUTO_INCREMENT 	        NOT NULL                            COMMENT 'Permet d\'identifier les machines du jeu',
    IP				    CHAR(10)	                        NOT NULL                            COMMENT 'Adresse IP virtuelle correspondant aux ordinateurs',
    ID_Joueurs			    INTEGER                             NOT NULL                    	    COMMENT 'Identifiant du joueur qui permet de lui affecter une machine',
    Pare_feu			    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Niveau du pare-feu du joueur',
    Anti_Virus			    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Niveau de l\'anti-virus du joueur',
    Porte_Feuille		    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Niveau qui permet d\'obtenir plus de revenus',
    Scanner_Reseau		    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Fonction du jeu',
    FW_Cracker			    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Fonction du jeu',
    SW_Cracker 			    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Fonction du jeu',
    Generateur_de_Miner		    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Fonction du jeu',
    Generateur_de_Backdoor	    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Fonction du jeu',
    Carte_Reseau		    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Niveau de la carte réseau de la machine du joueur',
    Processeur			    INTEGER 		                NOT NULL	DEFAULT 1           COMMENT 'Niveau du processeur de la machine du joueur',
    Disque_Dur			    INTEGER                             NOT NULL	DEFAULT 1           COMMENT 'Niveau de disque dur de la machine du joueur',
    LOG                             TEXT 		                NOT NULL	                    COMMENT 'Fichier texte qui contient les diverses attaques sur la machine',
    CONSTRAINT 			    pk_ID_Ordinateurs                   PRIMARY KEY(ID_Ordinateurs),
    CONSTRAINT 			    fk_ID_Joueurs                       FOREIGN KEY (ID_Joueurs) 	    REFERENCES joueurs (ID_Joueurs)
);


CREATE TABLE IF NOT EXISTS virus
(
    ID_Virus 			    INTEGER AUTO_INCREMENT              NOT NULL	                    COMMENT 'Permet d\'identifier le virus',
    ID_Ordinateurs 		    INTEGER 		                NOT NULL	                    COMMENT	'Permet d\'identifier la machine infectée',
    ID_Joueurs 			    INTEGER			                NOT NULL                            COMMENT 'Permet d\'identifier le joueur qui a infecté',
    Type_Virus 			    CHAR(3) 		                NOT NULL                            COMMENT 'Permet de nommer le type de virus utilisé',
    Niveau                          SMALLINT     		        NOT NULL    DEFAULT 1       COMMENT 'Défini les différents types de virus du plus gentil au plus agressif',
    CONSTRAINT 			    pk_ID_Virus		                PRIMARY KEY (ID_Virus),
    CONSTRAINT 			    fk_ID_Joueurs_Virus  	        FOREIGN KEY(ID_Joueurs) 	        REFERENCES joueurs(ID_Joueurs),
    CONSTRAINT 			    fk_ID_Ordinateurs                   FOREIGN KEY(ID_Ordinateurs) 	REFERENCES ordinateurs(ID_Ordinateurs)
);

