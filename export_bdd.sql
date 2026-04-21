--
-- PostgreSQL database dump
--

\restrict 4seu0vGpDmqALvlnR6afL3rTbDGtTKH2noQDwYnHUMUgxFjun8S2soGD5eZhkHv

-- Dumped from database version 16.13
-- Dumped by pg_dump version 16.13

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: agence; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.agence (
    id integer NOT NULL,
    num_agence character varying(255) NOT NULL,
    nom_agence character varying(255) DEFAULT NULL::character varying,
    adresse_agence character varying(255) DEFAULT NULL::character varying,
    tel_agence integer
);


ALTER TABLE public.agence OWNER TO app;

--
-- Name: agence_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.agence_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.agence_id_seq OWNER TO app;

--
-- Name: agence_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.agence_id_seq OWNED BY public.agence.id;


--
-- Name: client; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.client (
    id integer NOT NULL,
    num_client character varying(255) NOT NULL,
    rais_sociale character varying(255) DEFAULT NULL::character varying,
    siren bigint,
    code_ape integer,
    adresse_client character varying(255) DEFAULT NULL::character varying,
    telephone_client integer,
    email_client character varying(255) DEFAULT NULL::character varying,
    duree_deplacement character varying(255) DEFAULT NULL::character varying,
    distance_km numeric(10,2) DEFAULT NULL::numeric,
    agence_id integer NOT NULL
);


ALTER TABLE public.client OWNER TO app;

--
-- Name: client_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.client_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.client_id_seq OWNER TO app;

--
-- Name: client_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.client_id_seq OWNED BY public.client.id;


--
-- Name: contrat_maintenance; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.contrat_maintenance (
    id integer NOT NULL,
    num_contrat character varying(255) NOT NULL,
    date_signature timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    date_echeance timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.contrat_maintenance OWNER TO app;

--
-- Name: contrat_maintenance_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.contrat_maintenance_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.contrat_maintenance_id_seq OWNER TO app;

--
-- Name: contrat_maintenance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.contrat_maintenance_id_seq OWNED BY public.contrat_maintenance.id;


--
-- Name: controler; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.controler (
    id integer NOT NULL,
    num_serie character varying(255) NOT NULL,
    num_intervenant character varying(255) NOT NULL,
    temps_passe character varying(255) DEFAULT NULL::character varying,
    commentaire text
);


ALTER TABLE public.controler OWNER TO app;

--
-- Name: controler_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.controler_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.controler_id_seq OWNER TO app;

--
-- Name: controler_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.controler_id_seq OWNED BY public.controler.id;


--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO app;

--
-- Name: famille; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.famille (
    id integer NOT NULL,
    code_famille character varying(255) NOT NULL,
    libelle_famille character varying(255) NOT NULL
);


ALTER TABLE public.famille OWNER TO app;

--
-- Name: famille_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.famille_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.famille_id_seq OWNER TO app;

--
-- Name: famille_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.famille_id_seq OWNED BY public.famille.id;


--
-- Name: intervention; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.intervention (
    id integer NOT NULL,
    date_visite date,
    heure_visite time(0) without time zone DEFAULT NULL::time without time zone,
    client_id integer NOT NULL,
    technicien_id integer NOT NULL,
    titre character varying(255) DEFAULT NULL::character varying,
    description text,
    statut character varying(50) DEFAULT NULL::character varying,
    gravite character varying(50) DEFAULT NULL::character varying
);


ALTER TABLE public.intervention OWNER TO app;

--
-- Name: intervention_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.intervention_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.intervention_id_seq OWNER TO app;

--
-- Name: intervention_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.intervention_id_seq OWNED BY public.intervention.id;


--
-- Name: materiel; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.materiel (
    id integer NOT NULL,
    num_serie character varying(255) NOT NULL,
    date_vente timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    date_installation timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    prix_vente numeric(10,2) DEFAULT NULL::numeric,
    emplacement character varying(255) DEFAULT NULL::character varying,
    client_id integer NOT NULL,
    type_materiel_id integer NOT NULL,
    contrat_maintenance_id integer
);


ALTER TABLE public.materiel OWNER TO app;

--
-- Name: materiel_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.materiel_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.materiel_id_seq OWNER TO app;

--
-- Name: materiel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.materiel_id_seq OWNED BY public.materiel.id;


--
-- Name: messenger_messages; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.messenger_messages (
    id bigint NOT NULL,
    body text NOT NULL,
    headers text NOT NULL,
    queue_name character varying(190) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    available_at timestamp(0) without time zone NOT NULL,
    delivered_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.messenger_messages OWNER TO app;

--
-- Name: messenger_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.messenger_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.messenger_messages_id_seq OWNER TO app;

--
-- Name: messenger_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.messenger_messages_id_seq OWNED BY public.messenger_messages.id;


--
-- Name: type_contrat; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.type_contrat (
    id integer NOT NULL,
    ref_type_contrat character varying(255) NOT NULL,
    delail_intervention character varying(255) DEFAULT NULL::character varying,
    taux_applicable character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.type_contrat OWNER TO app;

--
-- Name: type_contrat_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.type_contrat_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.type_contrat_id_seq OWNER TO app;

--
-- Name: type_contrat_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.type_contrat_id_seq OWNED BY public.type_contrat.id;


--
-- Name: type_materiel; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.type_materiel (
    id integer NOT NULL,
    ref_interne character varying(255) NOT NULL,
    libelle_type_materiel character varying(255) NOT NULL,
    famille_id integer NOT NULL
);


ALTER TABLE public.type_materiel OWNER TO app;

--
-- Name: type_materiel_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.type_materiel_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.type_materiel_id_seq OWNER TO app;

--
-- Name: type_materiel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.type_materiel_id_seq OWNED BY public.type_materiel.id;


--
-- Name: utilisateur; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.utilisateur (
    id integer NOT NULL,
    type_utilisateur character varying(50) NOT NULL,
    nom character varying(255) DEFAULT NULL::character varying,
    prenom character varying(255) DEFAULT NULL::character varying,
    email character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL,
    agence_id integer NOT NULL,
    matricule character varying(20) DEFAULT NULL::character varying
);


ALTER TABLE public.utilisateur OWNER TO app;

--
-- Name: utilisateur_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.utilisateur_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.utilisateur_id_seq OWNER TO app;

--
-- Name: utilisateur_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.utilisateur_id_seq OWNED BY public.utilisateur.id;


--
-- Name: agence id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.agence ALTER COLUMN id SET DEFAULT nextval('public.agence_id_seq'::regclass);


--
-- Name: client id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.client ALTER COLUMN id SET DEFAULT nextval('public.client_id_seq'::regclass);


--
-- Name: contrat_maintenance id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.contrat_maintenance ALTER COLUMN id SET DEFAULT nextval('public.contrat_maintenance_id_seq'::regclass);


--
-- Name: controler id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.controler ALTER COLUMN id SET DEFAULT nextval('public.controler_id_seq'::regclass);


--
-- Name: famille id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.famille ALTER COLUMN id SET DEFAULT nextval('public.famille_id_seq'::regclass);


--
-- Name: intervention id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.intervention ALTER COLUMN id SET DEFAULT nextval('public.intervention_id_seq'::regclass);


--
-- Name: materiel id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.materiel ALTER COLUMN id SET DEFAULT nextval('public.materiel_id_seq'::regclass);


--
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Name: type_contrat id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.type_contrat ALTER COLUMN id SET DEFAULT nextval('public.type_contrat_id_seq'::regclass);


--
-- Name: type_materiel id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.type_materiel ALTER COLUMN id SET DEFAULT nextval('public.type_materiel_id_seq'::regclass);


--
-- Name: utilisateur id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.utilisateur ALTER COLUMN id SET DEFAULT nextval('public.utilisateur_id_seq'::regclass);


--
-- Data for Name: agence; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.agence (id, num_agence, nom_agence, adresse_agence, tel_agence) FROM stdin;
11	AG001	Agence Paris Centre	15 Rue de la R├®publique, 75001 Paris	\N
12	AG002	Agence Lyon	8 Place Bellecour, 69002 Lyon	\N
\.


--
-- Data for Name: client; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.client (id, num_client, rais_sociale, siren, code_ape, adresse_client, telephone_client, email_client, duree_deplacement, distance_km, agence_id) FROM stdin;
21	CLI001	Boulangerie Martin	12345678901234	1071	23 Rue du Pain, 75003 Paris	145678901	contact@boulangerie-martin.fr	25	8.50	11
22	CLI002	Pharmacie Centrale	98765432109876	4773	45 Avenue de la Sant├®, 75014 Paris	156789012	info@pharmacie-centrale.fr	15	5.20	11
23	CLI003	Restaurant Le Gourmet	45678901234567	5610	8 Place du March├®, 69001 Lyon	478901234	reservation@legourmet.fr	40	15.00	12
24	CLI004	Garage Dupuis	11223344556677	4520	112 Route Nationale, 69003 Lyon	478112233	contact@garage-dupuis.fr	30	12.30	12
25	CLI005	Librairie Pages	99887766554433	4761	5 Rue des Livres, 75005 Paris	143556677	librairie@pages.fr	20	6.80	12
\.


--
-- Data for Name: contrat_maintenance; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.contrat_maintenance (id, num_contrat, date_signature, date_echeance) FROM stdin;
\.


--
-- Data for Name: controler; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.controler (id, num_serie, num_intervenant, temps_passe, commentaire) FROM stdin;
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20251215145942	2026-03-16 09:10:02	312
DoctrineMigrations\\Version20260316120000	2026-03-16 09:18:09	13
DoctrineMigrations\\Version20260316121500	2026-03-16 09:19:24	30
\.


--
-- Data for Name: famille; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.famille (id, code_famille, libelle_famille) FROM stdin;
\.


--
-- Data for Name: intervention; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.intervention (id, date_visite, heure_visite, client_id, technicien_id, titre, description, statut, gravite) FROM stdin;
30	2026-03-06	10:00:00	23	23	\N	\N	ouverte	moyenne
31	2026-03-07	11:00:00	24	21	\N	\N	ouverte	moyenne
32	2026-03-07	15:00:00	25	24	\N	\N	ouverte	moyenne
33	2026-03-08	09:30:00	21	22	\N	\N	ouverte	moyenne
34	2026-03-10	08:00:00	22	23	\N	\N	ouverte	moyenne
36	2026-03-11	10:30:00	24	21	Intervention souris	Intervenant pour la souris	ouverte	moyenne
28	2026-03-05	09:00:00	21	23	\N	\N	ouverte	moyenne
29	2026-03-05	14:30:00	22	21	\N	\N	ouverte	moyenne
35	2026-03-10	16:00:00	23	21	\N	\N	ouverte	moyenne
\.


--
-- Data for Name: materiel; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.materiel (id, num_serie, date_vente, date_installation, prix_vente, emplacement, client_id, type_materiel_id, contrat_maintenance_id) FROM stdin;
\.


--
-- Data for Name: messenger_messages; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) FROM stdin;
\.


--
-- Data for Name: type_contrat; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.type_contrat (id, ref_type_contrat, delail_intervention, taux_applicable) FROM stdin;
\.


--
-- Data for Name: type_materiel; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.type_materiel (id, ref_interne, libelle_type_materiel, famille_id) FROM stdin;
\.


--
-- Data for Name: utilisateur; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.utilisateur (id, type_utilisateur, nom, prenom, email, roles, password, agence_id, matricule) FROM stdin;
21	technicien	Martin	Pierre	pierre.martin@cashcash.fr	["ROLE_TECH"]	$2y$13$nHUtzm9kMyiiX998KfFWWevlT2w5HuMsQkfl1kgwj.bnLMAA0fPTS	11	TECH001
22	technicien	Dubois	Marie	marie.dubois@cashcash.fr	["ROLE_TECH"]	$2y$13$Sx1/rZJ8jIQqZaLr5MXQhOfadG2/FO1SbgWiLpCZzCemjjv.nHaJu	11	TECH002
23	technicien	Bernard	Lucas	lucas.bernard@cashcash.fr	["ROLE_TECH"]	$2y$13$oIy4dLvW0htpPH0ck6EjAeQVr7FlvxeumbB7G9GSMtCV6e9e.RUP2	12	TECH003
24	technicien	Petit	Emma	emma.petit@cashcash.fr	["ROLE_TECH"]	$2y$13$nf0WG6bGlwyw/lo.mwKZIeuNv6xcityxgXifk/ZnkrkJc6fq9FhG.	12	TECH004
25	gestionnaire	Dupont	Jean	jean.dupont@cashcash.fr	["ROLE_ADMIN"]	$2y$13$5ffuyZk0YiXIKtwSXSjuaOI6wI/MypwJJGuvv3LsdROkuQe5ijbpi	11	GEST001
26	gestionnaire	test	test	test@test.fr	["ROLE_USER","ROLE_GERANT"]	$2y$13$PFtpZdtRLj36zYrSR3FMZeCLdtD2wnPGIWbDBrV9ZCgMoc5QyeuKi	11	test001
28	admin	Dubois	Lucas	dubois.lucas@cashcash.fr	["ROLE_USER","ROLE_ADMIN"]	$2y$13$jtL/SkgL2r5JuaiKoDC60O0nuAnEsz5OCKWjuTl6esxvntNXDDGwe	11	ADM002
27	admin	Poulain	Lohan	lohan.poulain@cashcash.fr	["ROLE_USER","ROLE_ADMIN"]	$2y$13$TtxwhXxImHZWfiNrMo4kneP46.2ScpwUnswe8GFc.S/OSXPjxbhGC	12	ADM001
\.


--
-- Name: agence_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.agence_id_seq', 12, true);


--
-- Name: client_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.client_id_seq', 25, true);


--
-- Name: contrat_maintenance_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.contrat_maintenance_id_seq', 1, false);


--
-- Name: controler_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.controler_id_seq', 1, false);


--
-- Name: famille_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.famille_id_seq', 1, false);


--
-- Name: intervention_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.intervention_id_seq', 36, true);


--
-- Name: materiel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.materiel_id_seq', 1, false);


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.messenger_messages_id_seq', 1, false);


--
-- Name: type_contrat_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.type_contrat_id_seq', 1, false);


--
-- Name: type_materiel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.type_materiel_id_seq', 1, false);


--
-- Name: utilisateur_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.utilisateur_id_seq', 28, true);


--
-- Name: agence agence_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.agence
    ADD CONSTRAINT agence_pkey PRIMARY KEY (id);


--
-- Name: client client_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT client_pkey PRIMARY KEY (id);


--
-- Name: contrat_maintenance contrat_maintenance_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.contrat_maintenance
    ADD CONSTRAINT contrat_maintenance_pkey PRIMARY KEY (id);


--
-- Name: controler controler_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.controler
    ADD CONSTRAINT controler_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: famille famille_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.famille
    ADD CONSTRAINT famille_pkey PRIMARY KEY (id);


--
-- Name: intervention intervention_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.intervention
    ADD CONSTRAINT intervention_pkey PRIMARY KEY (id);


--
-- Name: materiel materiel_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.materiel
    ADD CONSTRAINT materiel_pkey PRIMARY KEY (id);


--
-- Name: messenger_messages messenger_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.messenger_messages
    ADD CONSTRAINT messenger_messages_pkey PRIMARY KEY (id);


--
-- Name: type_contrat type_contrat_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.type_contrat
    ADD CONSTRAINT type_contrat_pkey PRIMARY KEY (id);


--
-- Name: type_materiel type_materiel_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.type_materiel
    ADD CONSTRAINT type_materiel_pkey PRIMARY KEY (id);


--
-- Name: utilisateur utilisateur_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_pkey PRIMARY KEY (id);


--
-- Name: idx_18d2b09119eb6921; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_18d2b09119eb6921 ON public.materiel USING btree (client_id);


--
-- Name: idx_18d2b0915d91dd3e; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_18d2b0915d91dd3e ON public.materiel USING btree (type_materiel_id);


--
-- Name: idx_18d2b09195269dc1; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_18d2b09195269dc1 ON public.materiel USING btree (contrat_maintenance_id);


--
-- Name: idx_1d1c63b3d725330d; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_1d1c63b3d725330d ON public.utilisateur USING btree (agence_id);


--
-- Name: idx_75ea56e016ba31db; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_75ea56e016ba31db ON public.messenger_messages USING btree (delivered_at);


--
-- Name: idx_75ea56e0e3bd61ce; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_75ea56e0e3bd61ce ON public.messenger_messages USING btree (available_at);


--
-- Name: idx_75ea56e0fb7336f0; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_75ea56e0fb7336f0 ON public.messenger_messages USING btree (queue_name);


--
-- Name: idx_c7440455d725330d; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_c7440455d725330d ON public.client USING btree (agence_id);


--
-- Name: idx_d11814ab13457256; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_d11814ab13457256 ON public.intervention USING btree (technicien_id);


--
-- Name: idx_d11814ab19eb6921; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_d11814ab19eb6921 ON public.intervention USING btree (client_id);


--
-- Name: idx_d52d976d97a77b84; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_d52d976d97a77b84 ON public.type_materiel USING btree (famille_id);


--
-- Name: uniq_1d1c63b31f0af90d; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_1d1c63b31f0af90d ON public.utilisateur USING btree (matricule);


--
-- Name: uniq_1d1c63b3e7927c74; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_1d1c63b3e7927c74 ON public.utilisateur USING btree (email);


--
-- Name: materiel fk_18d2b09119eb6921; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.materiel
    ADD CONSTRAINT fk_18d2b09119eb6921 FOREIGN KEY (client_id) REFERENCES public.client(id);


--
-- Name: materiel fk_18d2b0915d91dd3e; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.materiel
    ADD CONSTRAINT fk_18d2b0915d91dd3e FOREIGN KEY (type_materiel_id) REFERENCES public.type_materiel(id);


--
-- Name: materiel fk_18d2b09195269dc1; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.materiel
    ADD CONSTRAINT fk_18d2b09195269dc1 FOREIGN KEY (contrat_maintenance_id) REFERENCES public.contrat_maintenance(id);


--
-- Name: utilisateur fk_1d1c63b3d725330d; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT fk_1d1c63b3d725330d FOREIGN KEY (agence_id) REFERENCES public.agence(id);


--
-- Name: client fk_c7440455d725330d; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.client
    ADD CONSTRAINT fk_c7440455d725330d FOREIGN KEY (agence_id) REFERENCES public.agence(id);


--
-- Name: intervention fk_d11814ab13457256; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.intervention
    ADD CONSTRAINT fk_d11814ab13457256 FOREIGN KEY (technicien_id) REFERENCES public.utilisateur(id);


--
-- Name: intervention fk_d11814ab19eb6921; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.intervention
    ADD CONSTRAINT fk_d11814ab19eb6921 FOREIGN KEY (client_id) REFERENCES public.client(id);


--
-- Name: type_materiel fk_d52d976d97a77b84; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.type_materiel
    ADD CONSTRAINT fk_d52d976d97a77b84 FOREIGN KEY (famille_id) REFERENCES public.famille(id);


--
-- PostgreSQL database dump complete
--

\unrestrict 4seu0vGpDmqALvlnR6afL3rTbDGtTKH2noQDwYnHUMUgxFjun8S2soGD5eZhkHv

