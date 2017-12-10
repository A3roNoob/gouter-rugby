CREATE OR REPLACE VIEW CompteEnfants AS
	SELECT nom, prenom, solde
    FROM compte NATURAL JOIN enfant;
DELIMITER ;