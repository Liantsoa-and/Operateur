CREATE TABLE operateur(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE prefixe(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    debut_numero VARCHAR(3) NOT NULL,
    id_operateur INTEGER NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateur(id)
);

CREATE TABLE client(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero VARCHAR(12) NOT NULL UNIQUE
);

CREATE TABLE type_operation(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE bareme(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    description VARCHAR(255) NOT NULL,
    min DECIMAL(10,2) NOT NULL,
    max DECIMAL(10,2) NOT NULL,
    frais DECIMAL(10,2) NOT NULL,
    id_type_operation INTEGER NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

CREATE TABLE transactions(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_transaction VARCHAR(50) NOT NULL UNIQUE,
    montant DECIMAL(10,2) NOT NULL,
    frais DECIMAL(10,2) NOT NULL DEFAULT 0,
    date_transaction DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_client INTEGER NOT NULL,
    id_destinataire INTEGER,
    id_bareme INTEGER NOT NULL,
    FOREIGN KEY (id_client) REFERENCES client(id),
    FOREIGN KEY (id_destinataire) REFERENCES client(id),
    FOREIGN KEY (id_bareme) REFERENCES bareme(id)
);

DROP VIEW IF EXISTS v_solde;

CREATE VIEW v_solde AS
SELECT 
    c.id AS id_client,
    c.numero AS numero_client,
    COALESCE(SUM(
        CASE 
            WHEN to_.type = 'depot' THEN t.montant
            WHEN to_.type = 'retrait' THEN -(t.montant + t.frais)
            WHEN to_.type = 'transfert' AND c.id = t.id_client THEN -(t.montant + t.frais)   -- expéditeur
            WHEN to_.type = 'transfert' AND c.id = t.id_destinataire THEN t.montant -- destinataire
            ELSE 0
        END
    ), 0) AS solde
FROM client c
LEFT JOIN transaction t ON c.id = t.id_client OR c.id = t.id_destinataire
LEFT JOIN bareme b ON t.id_bareme = b.id
LEFT JOIN type_operation to_ ON b.id_type_operation = to_.id
GROUP BY c.id, c.numero;