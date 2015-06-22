--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.3
-- Dumped by pg_dump version 9.2.2
-- Started on 2014-08-25 00:17:47

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 187 (class 3079 OID 11727)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2034 (class 0 OID 0)
-- Dependencies: 187
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 180 (class 1259 OID 24684)
-- Name: priorities; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE priorities (
    id integer NOT NULL,
    name character varying(50)
);


ALTER TABLE public.priorities OWNER TO postgres;

--
-- TOC entry 179 (class 1259 OID 24682)
-- Name: priorities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE priorities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.priorities_id_seq OWNER TO postgres;

--
-- TOC entry 2035 (class 0 OID 0)
-- Dependencies: 179
-- Name: priorities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE priorities_id_seq OWNED BY priorities.id;


--
-- TOC entry 175 (class 1259 OID 24640)
-- Name: profile; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE profile (
    id integer NOT NULL,
    name character varying(30),
    description character varying(200)
);


ALTER TABLE public.profile OWNER TO postgres;

--
-- TOC entry 174 (class 1259 OID 24638)
-- Name: profile_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE profile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.profile_id_seq OWNER TO postgres;

--
-- TOC entry 2036 (class 0 OID 0)
-- Dependencies: 174
-- Name: profile_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE profile_id_seq OWNED BY profile.id;


--
-- TOC entry 169 (class 1259 OID 24605)
-- Name: projects; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE projects (
    id integer NOT NULL,
    name character varying(30)
);


ALTER TABLE public.projects OWNER TO postgres;

--
-- TOC entry 170 (class 1259 OID 24608)
-- Name: projects_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.projects_id_seq OWNER TO postgres;

--
-- TOC entry 2037 (class 0 OID 0)
-- Dependencies: 170
-- Name: projects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE projects_id_seq OWNED BY projects.id;


--
-- TOC entry 182 (class 1259 OID 24692)
-- Name: statu; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE statu (
    id integer NOT NULL,
    descripcion character varying(50)
);


ALTER TABLE public.statu OWNER TO postgres;

--
-- TOC entry 181 (class 1259 OID 24690)
-- Name: status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.status_id_seq OWNER TO postgres;

--
-- TOC entry 2038 (class 0 OID 0)
-- Dependencies: 181
-- Name: status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE status_id_seq OWNED BY statu.id;


--
-- TOC entry 168 (class 1259 OID 24602)
-- Name: tasks; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tasks (
    id integer NOT NULL,
    title character varying,
    projects_id integer,
    status_id integer,
    priorities_id integer,
    start timestamp without time zone,
    "end" timestamp without time zone,
    realized integer,
    descripcion character varying(250),
    types_id integer,
    "time" time without time zone
);


ALTER TABLE public.tasks OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 24671)
-- Name: tasks_changes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tasks_changes (
    id integer NOT NULL,
    tasks_id integer,
    status_id integer,
    priorities_id integer,
    "end" timestamp without time zone,
    realized integer,
    description character varying(200)
);


ALTER TABLE public.tasks_changes OWNER TO postgres;

--
-- TOC entry 177 (class 1259 OID 24669)
-- Name: tasks_changes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tasks_changes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tasks_changes_id_seq OWNER TO postgres;

--
-- TOC entry 2039 (class 0 OID 0)
-- Dependencies: 177
-- Name: tasks_changes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tasks_changes_id_seq OWNED BY tasks_changes.id;


--
-- TOC entry 171 (class 1259 OID 24616)
-- Name: tasks_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tasks_id_seq OWNER TO postgres;

--
-- TOC entry 2040 (class 0 OID 0)
-- Dependencies: 171
-- Name: tasks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tasks_id_seq OWNED BY tasks.id;


--
-- TOC entry 186 (class 1259 OID 24759)
-- Name: types; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE types (
    id integer NOT NULL,
    name character varying(30)
);


ALTER TABLE public.types OWNER TO postgres;

--
-- TOC entry 185 (class 1259 OID 24757)
-- Name: types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.types_id_seq OWNER TO postgres;

--
-- TOC entry 2041 (class 0 OID 0)
-- Dependencies: 185
-- Name: types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE types_id_seq OWNED BY types.id;


--
-- TOC entry 173 (class 1259 OID 24632)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    name character varying(30),
    password character varying(32),
    profile_id integer NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 172 (class 1259 OID 24630)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 2042 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 176 (class 1259 OID 24646)
-- Name: users_profiles_fk_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_profiles_fk_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_profiles_fk_seq OWNER TO postgres;

--
-- TOC entry 2043 (class 0 OID 0)
-- Dependencies: 176
-- Name: users_profiles_fk_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_profiles_fk_seq OWNED BY users.profile_id;


--
-- TOC entry 184 (class 1259 OID 24726)
-- Name: users_task; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users_task (
    id integer NOT NULL,
    user_id integer,
    task_id integer
);


ALTER TABLE public.users_task OWNER TO postgres;

--
-- TOC entry 183 (class 1259 OID 24724)
-- Name: users_task_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_task_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_task_id_seq OWNER TO postgres;

--
-- TOC entry 2044 (class 0 OID 0)
-- Dependencies: 183
-- Name: users_task_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_task_id_seq OWNED BY users_task.id;


--
-- TOC entry 1973 (class 2604 OID 24687)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY priorities ALTER COLUMN id SET DEFAULT nextval('priorities_id_seq'::regclass);


--
-- TOC entry 1971 (class 2604 OID 24643)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profile ALTER COLUMN id SET DEFAULT nextval('profile_id_seq'::regclass);


--
-- TOC entry 1968 (class 2604 OID 24610)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY projects ALTER COLUMN id SET DEFAULT nextval('projects_id_seq'::regclass);


--
-- TOC entry 1974 (class 2604 OID 24695)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY statu ALTER COLUMN id SET DEFAULT nextval('status_id_seq'::regclass);


--
-- TOC entry 1967 (class 2604 OID 24618)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks ALTER COLUMN id SET DEFAULT nextval('tasks_id_seq'::regclass);


--
-- TOC entry 1972 (class 2604 OID 24674)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks_changes ALTER COLUMN id SET DEFAULT nextval('tasks_changes_id_seq'::regclass);


--
-- TOC entry 1976 (class 2604 OID 24762)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY types ALTER COLUMN id SET DEFAULT nextval('types_id_seq'::regclass);


--
-- TOC entry 1969 (class 2604 OID 24635)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 1970 (class 2604 OID 24648)
-- Name: profile_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN profile_id SET DEFAULT nextval('users_profiles_fk_seq'::regclass);


--
-- TOC entry 1975 (class 2604 OID 24729)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_task ALTER COLUMN id SET DEFAULT nextval('users_task_id_seq'::regclass);


--
-- TOC entry 2020 (class 0 OID 24684)
-- Dependencies: 180
-- Data for Name: priorities; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2045 (class 0 OID 0)
-- Dependencies: 179
-- Name: priorities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('priorities_id_seq', 1, false);


--
-- TOC entry 2015 (class 0 OID 24640)
-- Dependencies: 175
-- Data for Name: profile; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO profile VALUES (1, 'Admin', 'administrador');


--
-- TOC entry 2046 (class 0 OID 0)
-- Dependencies: 174
-- Name: profile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('profile_id_seq', 1, true);


--
-- TOC entry 2009 (class 0 OID 24605)
-- Dependencies: 169
-- Data for Name: projects; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO projects VALUES (3, 'otra');
INSERT INTO projects VALUES (4, 'jonathan');
INSERT INTO projects VALUES (5, 'lala');
INSERT INTO projects VALUES (6, 'asldfkj');
INSERT INTO projects VALUES (1, 'ahora');
INSERT INTO projects VALUES (2, 'new project old');


--
-- TOC entry 2047 (class 0 OID 0)
-- Dependencies: 170
-- Name: projects_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('projects_id_seq', 6, true);


--
-- TOC entry 2022 (class 0 OID 24692)
-- Dependencies: 182
-- Data for Name: statu; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO statu VALUES (1, 'Openned');
INSERT INTO statu VALUES (2, 'Started');
INSERT INTO statu VALUES (3, 'Done');


--
-- TOC entry 2048 (class 0 OID 0)
-- Dependencies: 181
-- Name: status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('status_id_seq', 3, true);


--
-- TOC entry 2008 (class 0 OID 24602)
-- Dependencies: 168
-- Data for Name: tasks; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO tasks VALUES (1, 'sasd', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (2, 'sasds', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (3, 'tiene que adna', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (4, 'bien', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (5, 'facil', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (7, 'nada', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (9, 'no hay ninguna que pueda hacer ahora, pero estoy probando que pasa', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (10, 'no hay ninguna que pueda hacer ahora, pero estoy probando que pasa, ahora te quiero ver', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (11, 'bueno task', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (12, 'bueno task con usuario', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (13, 'a ver', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (14, '1_', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (15, '2_', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (19, '+jo', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (20, 'as +Juan +Jonathan', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (21, 'as ', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (22, 'otra bien ', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (23, 'no se ve', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (24, 'a si', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (25, 'asi ', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (17, 'bien', 4, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (18, 'asf', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (6, 'claro', 4, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (28, 'empezar con algo', 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (27, 'termina', 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (26, 'tarea nueva', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (16, 'kbueno', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (30, 'asfd ', 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (29, 'terminar ', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (31, 'tarea para todos ', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO tasks VALUES (32, 'ass ksd asd asd asd asd asd ljj ljk ksd', 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL);


--
-- TOC entry 2018 (class 0 OID 24671)
-- Dependencies: 178
-- Data for Name: tasks_changes; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2049 (class 0 OID 0)
-- Dependencies: 177
-- Name: tasks_changes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tasks_changes_id_seq', 1, false);


--
-- TOC entry 2050 (class 0 OID 0)
-- Dependencies: 171
-- Name: tasks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tasks_id_seq', 32, true);


--
-- TOC entry 2026 (class 0 OID 24759)
-- Dependencies: 186
-- Data for Name: types; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 2051 (class 0 OID 0)
-- Dependencies: 185
-- Name: types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('types_id_seq', 1, false);


--
-- TOC entry 2013 (class 0 OID 24632)
-- Dependencies: 173
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO users VALUES (12, 'Juan', 'asdfasdf', 1);
INSERT INTO users VALUES (8, 'Jonathan', 'asdfasdf', 1);


--
-- TOC entry 2052 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_id_seq', 12, true);


--
-- TOC entry 2053 (class 0 OID 0)
-- Dependencies: 176
-- Name: users_profiles_fk_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_profiles_fk_seq', 8, true);


--
-- TOC entry 2024 (class 0 OID 24726)
-- Dependencies: 184
-- Data for Name: users_task; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO users_task VALUES (8, 8, 1);
INSERT INTO users_task VALUES (10, 8, 12);
INSERT INTO users_task VALUES (11, 8, 13);
INSERT INTO users_task VALUES (12, 8, 14);
INSERT INTO users_task VALUES (13, 8, 15);
INSERT INTO users_task VALUES (14, 8, 10);
INSERT INTO users_task VALUES (15, 8, 16);
INSERT INTO users_task VALUES (16, 8, 17);
INSERT INTO users_task VALUES (17, 8, 18);
INSERT INTO users_task VALUES (18, 8, 19);
INSERT INTO users_task VALUES (19, 12, 20);
INSERT INTO users_task VALUES (20, 8, 20);
INSERT INTO users_task VALUES (21, 12, 21);
INSERT INTO users_task VALUES (22, 8, 21);
INSERT INTO users_task VALUES (23, 8, 22);
INSERT INTO users_task VALUES (24, 12, 22);
INSERT INTO users_task VALUES (25, 8, 23);
INSERT INTO users_task VALUES (26, 8, 24);
INSERT INTO users_task VALUES (27, 12, 25);
INSERT INTO users_task VALUES (28, 8, 26);
INSERT INTO users_task VALUES (29, 8, 27);
INSERT INTO users_task VALUES (30, 8, 28);
INSERT INTO users_task VALUES (31, 8, 29);
INSERT INTO users_task VALUES (32, 12, 30);
INSERT INTO users_task VALUES (33, 12, 31);
INSERT INTO users_task VALUES (34, 8, 32);


--
-- TOC entry 2054 (class 0 OID 0)
-- Dependencies: 183
-- Name: users_task_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_task_id_seq', 34, true);


--
-- TOC entry 1987 (class 2606 OID 24645)
-- Name: profiles_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profile
    ADD CONSTRAINT profiles_pk PRIMARY KEY (id);


--
-- TOC entry 1991 (class 2606 OID 24689)
-- Name: proirities_pl; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY priorities
    ADD CONSTRAINT proirities_pl PRIMARY KEY (id);


--
-- TOC entry 1982 (class 2606 OID 24615)
-- Name: projects_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY projects
    ADD CONSTRAINT projects_pk PRIMARY KEY (id);


--
-- TOC entry 1993 (class 2606 OID 24697)
-- Name: status_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY statu
    ADD CONSTRAINT status_pk PRIMARY KEY (id);


--
-- TOC entry 1980 (class 2606 OID 24623)
-- Name: taks_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tasks
    ADD CONSTRAINT taks_pk PRIMARY KEY (id);


--
-- TOC entry 1989 (class 2606 OID 24676)
-- Name: tasks_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tasks_changes
    ADD CONSTRAINT tasks_pk PRIMARY KEY (id);


--
-- TOC entry 1997 (class 2606 OID 24764)
-- Name: types_id; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY types
    ADD CONSTRAINT types_id PRIMARY KEY (id);


--
-- TOC entry 1985 (class 2606 OID 24637)
-- Name: users_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pk PRIMARY KEY (id);


--
-- TOC entry 1995 (class 2606 OID 24731)
-- Name: users_task_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users_task
    ADD CONSTRAINT users_task_pk PRIMARY KEY (id);


--
-- TOC entry 1977 (class 1259 OID 24629)
-- Name: fki_projects_tasks_fk; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_projects_tasks_fk ON tasks USING btree (projects_id);


--
-- TOC entry 1978 (class 1259 OID 24770)
-- Name: fki_tasks_types_fk; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_tasks_types_fk ON tasks USING btree (types_id);


--
-- TOC entry 1983 (class 1259 OID 24658)
-- Name: fki_users_profiles_fk; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_users_profiles_fk ON users USING btree (profile_id);


--
-- TOC entry 2000 (class 2606 OID 24719)
-- Name: proirities_task_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks
    ADD CONSTRAINT proirities_task_fk FOREIGN KEY (priorities_id) REFERENCES priorities(id);


--
-- TOC entry 1998 (class 2606 OID 24699)
-- Name: projects_tasks_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks
    ADD CONSTRAINT projects_tasks_fk FOREIGN KEY (projects_id) REFERENCES projects(id);


--
-- TOC entry 2005 (class 2606 OID 24752)
-- Name: tasks_changes_priorities_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks_changes
    ADD CONSTRAINT tasks_changes_priorities_fk FOREIGN KEY (priorities_id) REFERENCES priorities(id);


--
-- TOC entry 2004 (class 2606 OID 24747)
-- Name: tasks_changes_status_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks_changes
    ADD CONSTRAINT tasks_changes_status_fk FOREIGN KEY (status_id) REFERENCES statu(id);


--
-- TOC entry 2003 (class 2606 OID 24742)
-- Name: tasks_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks_changes
    ADD CONSTRAINT tasks_fk FOREIGN KEY (tasks_id) REFERENCES tasks(id);


--
-- TOC entry 1999 (class 2606 OID 24714)
-- Name: tasks_status_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks
    ADD CONSTRAINT tasks_status_fk FOREIGN KEY (status_id) REFERENCES statu(id);


--
-- TOC entry 2001 (class 2606 OID 24765)
-- Name: tasks_types_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tasks
    ADD CONSTRAINT tasks_types_fk FOREIGN KEY (types_id) REFERENCES types(id);


--
-- TOC entry 2002 (class 2606 OID 24784)
-- Name: users_profiles_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_profiles_fk FOREIGN KEY (profile_id) REFERENCES profile(id);


--
-- TOC entry 2006 (class 2606 OID 24774)
-- Name: users_tasks_tasks_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_task
    ADD CONSTRAINT users_tasks_tasks_fk FOREIGN KEY (task_id) REFERENCES tasks(id);


--
-- TOC entry 2007 (class 2606 OID 24779)
-- Name: users_tasks_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_task
    ADD CONSTRAINT users_tasks_users_fk FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2033 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2014-08-25 00:17:49

--
-- PostgreSQL database dump complete
--

ALTER TABLE users_task DROP CONSTRAINT users_tasks_tasks_fk;

ALTER TABLE users_task
  ADD CONSTRAINT users_tasks_tasks_fk FOREIGN KEY (task_id)
      REFERENCES tasks (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE users_task DROP CONSTRAINT users_tasks_users_fk;

ALTER TABLE users_task
  ADD CONSTRAINT users_tasks_users_fk FOREIGN KEY (user_id)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE tasks DROP CONSTRAINT projects_tasks_fk;

ALTER TABLE tasks
  ADD CONSTRAINT projects_tasks_fk FOREIGN KEY (projects_id)
      REFERENCES projects (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE tasks DROP CONSTRAINT tasks_status_fk;

ALTER TABLE tasks
  ADD CONSTRAINT tasks_status_fk FOREIGN KEY (status_id)
      REFERENCES statu (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE tasks DROP CONSTRAINT tasks_types_fk;

ALTER TABLE tasks
  ADD CONSTRAINT tasks_types_fk FOREIGN KEY (types_id)
      REFERENCES types (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE tasks DROP CONSTRAINT proirities_task_fk;

ALTER TABLE tasks
  ADD CONSTRAINT proirities_task_fk FOREIGN KEY (priorities_id)
      REFERENCES priorities (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE users DROP CONSTRAINT users_profiles_fk;

ALTER TABLE users
  ADD CONSTRAINT users_profiles_fk FOREIGN KEY (profile_id)
      REFERENCES profile (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;

      
ALTER TABLE tasks ADD COLUMN last_modified timestamp with time zone DEFAULT now();

ALTER TABLE tasks ADD COLUMN created timestamp with time zone DEFAULT now();
UPDATE tasks SET created = last_modified;

CREATE TABLE change_tasks
(
  id serial NOT NULL,
  task_id integer not null,
  title character varying,
  projects_id integer,
  status_id integer,
  priorities_id integer,
  start timestamp without time zone,
  "end" timestamp without time zone,
  realized integer,
  descripcion character varying(250),
  types_id integer,
  "time" time without time zone,
  created timestamp with time zone DEFAULT now(),
  CONSTRAINT change_tasks_pk PRIMARY KEY (id)
);
ALTER TABLE change_tasks ADD COLUMN user_id integer NOT NULL;
ALTER TABLE tasks ADD COLUMN user_id integer;

ALTER TABLE change_tasks ADD COLUMN revisado BOOLEAN DEFAULT FALSE NOT NULL;
ALTER TABLE users ADD COLUMN mail character varying (100);
CREATE TABLE revision
(
  id serial NOT NULL,
  user_name character varying(30),
  date timestamp,
  message text,
  created timestamp default now() not null,
  CONSTRAINT revision_pk PRIMARY KEY (id)
);

	
CREATE OR REPLACE FUNCTION plainto_or_tsquery (TEXT) RETURNS tsquery AS $$
SELECT to_tsquery( regexp_replace( trim($1), E'[\\s\'|:&()!]+','|','g') );
$$ LANGUAGE SQL STRICT IMMUTABLE;
	
ALTER TABLE tasks ADD COLUMN search_title tsvector;

UPDATE tasks SET search_title = setweight(to_tsvector('spanish', coalesce(title, '')), 'A');
	
ALTER TABLE users ADD COLUMN created timestamp not null default now();

ALTER TABLE tasks ALTER COLUMN status_id set default 1;

UPDATE tasks set status_id = 1 where status_id = null;
UPDATE tasks set status_id = 1 where status_id is null;
ALTER TABLE tasks ALTER COLUMN status_id set not null;




ALTER TABLE tasks ADD COLUMN sort integer;
DO $do$
DECLARE p integer;
BEGIN
FOR p IN SELECT id FROM projects order by id LOOP
        CREATE TABLE tmp_sort AS 
        SELECT tasks.id, row_number() over (order by id) as rownum 
            FROM tasks 
            WHERE tasks.status_id = 1 AND projects_id = p 
            ORDER BY id;
        UPDATE tasks SET sort = rownum FROM tmp_sort WHERE tmp_sort.id = tasks.id;
        DROP TABLE tmp_sort;
    END LOOP;
END 
$do$;