-- ============================================
-- JEU D'ESSAI COMPLET - CashCash
-- Agences + Utilisateurs de test
-- ============================================

-- 1. INSÉRER LES AGENCES
INSERT INTO agence (nom, adresse, telephone, email) VALUES
('Agence Paris', '123 Rue de Paris', '01 23 45 67 89', 'paris@cashcash.fr'),
('Agence Lyon', '456 Rue de Lyon', '04 56 78 90 12', 'lyon@cashcash.fr'),
('Agence Marseille', '789 Rue de Marseille', '04 91 12 34 56', 'marseille@cashcash.fr');

-- 2. INSÉRER LES UTILISATEURS
-- Note: Les mots de passe ci-dessous sont des hashs factices
-- Tu devras les générer via Symfony avec: symfony console security:hash-password

-- Utilisateur Admin
INSERT INTO utilisateur (type_utilisateur, matricule, nom, prenom, email, roles, password, agence_id) VALUES
('admin', 'ADMIN001', 'Dupont', 'Jean', 'admin@cashcash.fr', '["ROLE_ADMIN"]', '$2y$13$abcdefgh...', 1);

-- Utilisateurs Employés
INSERT INTO utilisateur (type_utilisateur, matricule, nom, prenom, email, roles, password, agence_id) VALUES
('employe', 'EMP001', 'Martin', 'Alice', 'alice.martin@cashcash.fr', '["ROLE_USER"]', '$2y$13$abcdefgh...', 1),
('employe', 'EMP002', 'Bernard', 'Bob', 'bob.bernard@cashcash.fr', '["ROLE_USER"]', '$2y$13$abcdefgh...', 2),
('employe', 'EMP003', 'Petit', 'Charlie', 'charlie.petit@cashcash.fr', '["ROLE_USER"]', '$2y$13$abcdefgh...', 3);

-- Utilisateurs Techniciens
INSERT INTO utilisateur (type_utilisateur, matricule, nom, prenom, email, roles, password, agence_id) VALUES
('technicien', 'TECH001', 'Blanc', 'David', 'david.blanc@cashcash.fr', '["ROLE_USER", "ROLE_TECHNICIEN"]', '$2y$13$abcdefgh...', 1),
('technicien', 'TECH002', 'Moreau', 'Eva', 'eva.moreau@cashcash.fr', '["ROLE_USER", "ROLE_TECHNICIEN"]', '$2y$13$abcdefgh...', 1),
('technicien', 'TECH003', 'Nicolas', 'Frank', 'frank.nicolas@cashcash.fr', '["ROLE_USER", "ROLE_TECHNICIEN"]', '$2y$13$abcdefgh...', 2),
('technicien', 'TECH004', 'Renard', 'Grace', 'grace.renard@cashcash.fr', '["ROLE_USER", "ROLE_TECHNICIEN"]', '$2y$13$abcdefgh...', 3);
