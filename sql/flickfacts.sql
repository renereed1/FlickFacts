--
-- PostgreSQL database dump
--

-- Dumped from database version 16.3
-- Dumped by pg_dump version 17.2 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: flickfacts; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA flickfacts;


ALTER SCHEMA flickfacts OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: movies; Type: TABLE; Schema: flickfacts; Owner: postgres
--

CREATE TABLE flickfacts.movies
(
    id          uuid                                   NOT NULL,
    created_at  timestamp with time zone DEFAULT now() NOT NULL,
    title       text                                   NOT NULL,
    description text,
    CONSTRAINT movie_title_check CHECK ((length(title) > 0))
);


ALTER TABLE flickfacts.movies
    OWNER TO postgres;

--
-- Name: sales; Type: TABLE; Schema: flickfacts; Owner: postgres
--

CREATE TABLE flickfacts.sales
(
    id         uuid                     NOT NULL,
    theater_id uuid                     NOT NULL,
    movie_id   uuid                     NOT NULL,
    price      numeric(10, 2)           NOT NULL,
    quantity   numeric(10, 2)           NOT NULL,
    created_at timestamp with time zone NOT NULL
);


ALTER TABLE flickfacts.sales
    OWNER TO postgres;

--
-- Name: theaters; Type: TABLE; Schema: flickfacts; Owner: postgres
--

CREATE TABLE flickfacts.theaters
(
    id         uuid                                   NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    name       text                                   NOT NULL,
    CONSTRAINT theater_name_check CHECK ((length(name) > 0))
);


ALTER TABLE flickfacts.theaters
    OWNER TO postgres;

--
-- Name: tickets; Type: TABLE; Schema: flickfacts; Owner: postgres
--

CREATE TABLE flickfacts.tickets
(
    id         uuid                     NOT NULL,
    created_at timestamp with time zone NOT NULL,
    theater_id uuid                     NOT NULL,
    movie_id   uuid                     NOT NULL,
    price      numeric(10, 2)           NOT NULL,
    total      integer                  NOT NULL,
    available  integer                  NOT NULL,
    CONSTRAINT ticket_check CHECK (((available >= 0) AND (available <= total))),
    CONSTRAINT ticket_price_check CHECK ((price >= (0)::numeric)),
    CONSTRAINT ticket_total_check CHECK ((total >= 0))
);


ALTER TABLE flickfacts.tickets
    OWNER TO postgres;

--
-- Name: movies movie_pkey; Type: CONSTRAINT; Schema: flickfacts; Owner: postgres
--

ALTER TABLE ONLY flickfacts.movies
    ADD CONSTRAINT movie_pkey PRIMARY KEY (id);


--
-- Name: sales sale_pkey; Type: CONSTRAINT; Schema: flickfacts; Owner: postgres
--

ALTER TABLE ONLY flickfacts.sales
    ADD CONSTRAINT sale_pkey PRIMARY KEY (id);


--
-- Name: theaters theater_pkey; Type: CONSTRAINT; Schema: flickfacts; Owner: postgres
--

ALTER TABLE ONLY flickfacts.theaters
    ADD CONSTRAINT theater_pkey PRIMARY KEY (id);


--
-- Name: tickets ticket_pkey; Type: CONSTRAINT; Schema: flickfacts; Owner: postgres
--

ALTER TABLE ONLY flickfacts.tickets
    ADD CONSTRAINT ticket_pkey PRIMARY KEY (id);


--
-- Name: tickets_movie_id_theater_id_available_index; Type: INDEX; Schema: flickfacts; Owner: postgres
--

CREATE INDEX tickets_movie_id_theater_id_available_index ON flickfacts.tickets USING btree (movie_id, theater_id, available);


--
-- Name: sales sales_movies_id_fk; Type: FK CONSTRAINT; Schema: flickfacts; Owner: postgres
--

ALTER TABLE ONLY flickfacts.sales
    ADD CONSTRAINT sales_movies_id_fk FOREIGN KEY (movie_id) REFERENCES flickfacts.movies (id);


--
-- Name: sales sales_theaters_id_fk; Type: FK CONSTRAINT; Schema: flickfacts; Owner: postgres
--

ALTER TABLE ONLY flickfacts.sales
    ADD CONSTRAINT sales_theaters_id_fk FOREIGN KEY (theater_id) REFERENCES flickfacts.theaters (id);


--
-- Name: tickets tickets_movies_id_fk; Type: FK CONSTRAINT; Schema: flickfacts; Owner: postgres
--

ALTER TABLE ONLY flickfacts.tickets
    ADD CONSTRAINT tickets_movies_id_fk FOREIGN KEY (movie_id) REFERENCES flickfacts.movies (id);


--
-- Name: tickets tickets_theaters_id_fk; Type: FK CONSTRAINT; Schema: flickfacts; Owner: postgres
--

ALTER TABLE ONLY flickfacts.tickets
    ADD CONSTRAINT tickets_theaters_id_fk FOREIGN KEY (theater_id) REFERENCES flickfacts.theaters (id);


--
-- PostgreSQL database dump complete
--

ALTER TABLE flickfacts.sales
    ADD COLUMN discount numeric(5, 2) NOT NULL DEFAULT 0.00;

ALTER TABLE flickfacts.sales
    ADD COLUMN final_price numeric(5, 2) NOT NULL DEFAULT 0.00;

ALTER TABLE flickfacts.Sales
    ALTER COLUMN discount TYPE smallint USING discount::smallint;

