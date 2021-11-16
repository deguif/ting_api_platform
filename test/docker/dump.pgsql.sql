CREATE TABLE "user" (
    firstname character varying(25) PRIMARY KEY,
    lastname character varying(25)
);

CREATE TABLE "filter" (
    name character varying(25) PRIMARY KEY,
    value character varying(25),
    value_partial character varying(25),
    value_start character varying(25),
    value_end character varying(25),
    value_word_start character varying(25),
    value_ipartial character varying(25)
);
