<?php

class TransactionLogger
{

    /**
     * @param int $idAdulte
     * @param int $idEnfant
     * @param float $montant
     */
    public function logOperationFonds($idAdulte, $idEnfant, $montant){
        $db = DatabaseObject::connect();

        $query = $db->prepare("INSERT INTO operationsolde(idAdulte, idEnfant, montant, dateOpe) VALUES (:idAdulte, :idEnfant, :montant, (SELECT SYSDATE() FROM DUAL));");
        $query->bindValue(':idAdulte', $idAdulte, PDO::PARAM_INT);
        $query->bindValue(':idEnfant', $idEnfant, PDO::PARAM_INT);
        $query->bindValue(':montant', $montant);
        try{
            $query->execute();
        }catch(PDOException $e){
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
    }
}