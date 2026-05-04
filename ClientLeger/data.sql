COPY public.agence (id, num_agence, nom_agence, adresse_agence, tel_agence) FROM stdin;
11	AG001	Agence Paris Centre	15 Rue de la République, 75001 Paris	\N
12	AG002	Agence Lyon	8 Place Bellecour, 69002 Lyon	\N
\.

COPY public.client (id, num_client, rais_sociale, siren, code_ape, adresse_client, telephone_client, email_client, duree_deplacement, distance_km, agence_id) FROM stdin;
21	CLI001	Boulangerie Martin	12345678901234	1071	Paris	145678901	contact@boulangerie.fr	25	8.50	11
22	CLI002	Pharmacie Centrale	98765432109876	4773	Paris	156789012	info@pharmacie.fr	15	5.20	11
23	CLI003	Restaurant Le Gourmet	45678901234567	5610	Lyon	478901234	reservation@gourmet.fr	40	15.00	12
\.

COPY public.utilisateur (id, type_utilisateur, nom, prenom, email, roles, password, agence_id, matricule) FROM stdin;
21	technicien	Martin	Pierre	pierre.martin@cashcash.fr	["ROLE_TECH"]	password	11	TECH001
22	technicien	Dubois	Marie	marie.dubois@cashcash.fr	["ROLE_TECH"]	password	11	TECH002
23	technicien	Bernard	Lucas	lucas.bernard@cashcash.fr	["ROLE_TECH"]	password	12	TECH003
\.

COPY public.intervention (id, date_visite, heure_visite, client_id, technicien_id, statut, gravite) FROM stdin;
30	2026-03-06	10:00:00	23	23	ouverte	moyenne
31	2026-03-07	11:00:00	22	21	ouverte	moyenne
\.

SELECT setval('agence_id_seq', 12, true);
SELECT setval('client_id_seq', 25, true);
SELECT setval('utilisateur_id_seq', 24, true);
SELECT setval('intervention_id_seq', 31, true);