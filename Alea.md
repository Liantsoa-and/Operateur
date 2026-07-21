- misy promotion amin'ny frais de transfert
- promotion en % sur le frais de transfert
- meme operateur
- config en base 
- mihena 10% ohatra zany le transfert

- base : configPromo

- anao affichage de changement aloha zah


CREATE TABLE IF NOT EXISTS config_promotion (
    id                 INTEGER PRIMARY KEY AUTOINCREMENT,
    pourcentage        DECIMAL(5,2) NOT NULL,
    date_modification  DATETIME     NOT NULL
);

INSERT INTO config_promotion (pourcentage, date_modification) VALUES (10.00, datetime('now'));
