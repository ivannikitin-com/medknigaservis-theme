USE medknigaservis;


EXPLAIN
SELECT DISTINCT meta_key
FROM mks_postmeta
WHERE meta_key NOT BETWEEN '_' AND '_z'
HAVING meta_key NOT LIKE '\\_%'
ORDER BY meta_key
LIMIT 30
