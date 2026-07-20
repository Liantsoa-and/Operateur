SELECT 
    o.id AS id_operateur,
    o.nom,
    COALESCE(SUM(t.frais), 0) AS gain
FROM operateur o
JOIN prefixe p ON p.id_operateur = o.id
JOIN client c ON SUBSTR(c.numero, 1, 3) = p.debut_numero
JOIN transactions t ON t.id_client = c.id
JOIN bareme b ON t.id_bareme = b.id
JOIN type_operation to_ ON b.id_type_operation = to_.id
WHERE o.id = ?
GROUP BY o.id, o.nom;