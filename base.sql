-- ============================================================
-- base.sql — Mobile Money — V2
-- ============================================================

-- OPERATEUR
CREATE TABLE IF NOT EXISTS operateur (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(255) NOT NULL
);

-- PREFIXE
CREATE TABLE IF NOT EXISTS prefixe (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    debut_numero VARCHAR(3)  NOT NULL,
    id_operateur INTEGER     NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateur(id)
);

-- CLIENT
CREATE TABLE IF NOT EXISTS client (
    id     INTEGER PRIMARY KEY AUTOINCREMENT,
    numero VARCHAR(12) NOT NULL UNIQUE
);

-- TYPE OPERATION
CREATE TABLE IF NOT EXISTS type_operation (
    id   INTEGER PRIMARY KEY AUTOINCREMENT,
    type VARCHAR(50) NOT NULL UNIQUE
);

-- BAREME
CREATE TABLE IF NOT EXISTS bareme (
    id                 INTEGER PRIMARY KEY AUTOINCREMENT,
    description        VARCHAR(255)   NOT NULL,
    min                DECIMAL(10,2)  NOT NULL,
    max                DECIMAL(10,2)  NOT NULL,
    frais              DECIMAL(10,2)  NOT NULL,
    id_type_operation  INTEGER        NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

-- TRANSACTIONS
CREATE TABLE IF NOT EXISTS transactions (
    id                   INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_transaction   VARCHAR(50)   NOT NULL UNIQUE,
    montant              DECIMAL(10,2) NOT NULL,
    frais                DECIMAL(10,2) NOT NULL DEFAULT 0,
    commission_appliquee DECIMAL(10,2) NULL DEFAULT NULL,
    date_transaction     DATETIME      NOT NULL,
    id_client            INTEGER       NOT NULL,
    id_destinataire      INTEGER       NULL,
    id_bareme            INTEGER       NOT NULL,
    FOREIGN KEY (id_client)       REFERENCES client(id),
    FOREIGN KEY (id_destinataire) REFERENCES client(id),
    FOREIGN KEY (id_bareme)       REFERENCES bareme(id)
);

-- CONFIG OPERATEUR (commission inter-opérateur)
CREATE TABLE IF NOT EXISTS config_operateur (
    id                 INTEGER PRIMARY KEY AUTOINCREMENT,
    commission_inter   DECIMAL(5,2) NOT NULL,
    date_modification  DATETIME     NOT NULL
);

-- ============================================================
-- VUE v_solde
-- ============================================================
DROP VIEW IF EXISTS v_solde;
CREATE VIEW v_solde AS
SELECT
    c.id     AS id_client,
    c.numero AS numero_client,
    COALESCE(SUM(
        CASE
            WHEN to_.type = 'depot'     THEN t.montant
            WHEN to_.type IN ('retrait', 'transfert') AND c.id = t.id_client      THEN -(t.montant + t.frais + COALESCE(t.commission_appliquee, 0))
            WHEN to_.type = 'transfert' AND c.id = t.id_destinataire              THEN t.montant
            ELSE 0
        END
    ), 0) AS solde
FROM client c
LEFT JOIN transactions t
    ON c.id = t.id_client OR c.id = t.id_destinataire
LEFT JOIN bareme b
    ON t.id_bareme = b.id
LEFT JOIN type_operation to_
    ON b.id_type_operation = to_.id
GROUP BY c.id, c.numero;

-- ============================================================
-- DONNEES DE BASE
-- ============================================================

-- Opérateurs
INSERT INTO operateur (nom) VALUES ('Telma');
INSERT INTO operateur (nom) VALUES ('Orange');
INSERT INTO operateur (nom) VALUES ('Airtel');

-- Préfixes Telma (notre opérateur, id=1)
INSERT INTO prefixe (debut_numero, id_operateur) VALUES ('033', 1);
INSERT INTO prefixe (debut_numero, id_operateur) VALUES ('037', 1);

-- Préfixes Orange (id=2)
INSERT INTO prefixe (debut_numero, id_operateur) VALUES ('032', 2);
INSERT INTO prefixe (debut_numero, id_operateur) VALUES ('031', 2);

-- Préfixes Airtel (id=3)
INSERT INTO prefixe (debut_numero, id_operateur) VALUES ('034', 3);
INSERT INTO prefixe (debut_numero, id_operateur) VALUES ('038', 3);

-- Types d'opérations
INSERT INTO type_operation (type) VALUES ('depot');
INSERT INTO type_operation (type) VALUES ('retrait');
INSERT INTO type_operation (type) VALUES ('transfert');

-- Barèmes dépôt (id_type_operation = 1) — frais = 0
INSERT INTO bareme (description, min, max, frais, id_type_operation) VALUES ('Dépôt standard', 100, 9999999, 0, 1);

-- Barèmes retrait (id_type_operation = 2)
INSERT INTO bareme (description, min, max, frais, id_type_operation) VALUES ('Retrait 100-50000',    100,    50000,  500,  2);
INSERT INTO bareme (description, min, max, frais, id_type_operation) VALUES ('Retrait 50001-200000', 50001,  200000, 1500, 2);
INSERT INTO bareme (description, min, max, frais, id_type_operation) VALUES ('Retrait 200001+',      200001, 9999999,3000, 2);

-- Barèmes transfert (id_type_operation = 3)
INSERT INTO bareme (description, min, max, frais, id_type_operation) VALUES ('Transfert 100-50000',    100,    50000,  300,  3);
INSERT INTO bareme (description, min, max, frais, id_type_operation) VALUES ('Transfert 50001-200000', 50001,  200000, 1000, 3);
INSERT INTO bareme (description, min, max, frais, id_type_operation) VALUES ('Transfert 200001+',      200001, 9999999,2000, 3);

-- Commission inter-opérateur par défaut : 5%
INSERT INTO config_operateur (commission_inter, date_modification) VALUES (5.00, datetime('now'));

-- Clients de test
INSERT INTO client (numero) VALUES ('0331234567');
INSERT INTO client (numero) VALUES ('0371234567');
INSERT INTO client (numero) VALUES ('0321234567');
INSERT INTO client (numero) VALUES ('0341234567');