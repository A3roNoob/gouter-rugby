DELIMITER //
CREATE TRIGGER RuptureStock AFTER UPDATE
  ON stock FOR EACH ROW
  BEGIN
    IF NEW.quantite < 5 THEN
      INSERT INTO stockgestion (idProduit, alert) VALUES(NEW.idProduit, 1)
      ON DUPLICATE KEY UPDATE alert=1;
    ELSE
      INSERT INTO stockgestion (idProduit, alert) VALUES(NEW.idProduit, 0)
      ON DUPLICATE KEY UPDATE alert=0;
    END IF;
  END;