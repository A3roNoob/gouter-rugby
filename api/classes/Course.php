<?php

class Course
{
    private $_id, $_produits, $_montant, $_date, $_failProduit;

    //region Getter/Setter
    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return float
     */
    public function getMontant()
    {
        return $this->_montant;
    }

    /**
     * @param float $montant
     */
    public function setMontant($montant)
    {
        $this->_montant = $montant;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->_date = $date;
    }
    //endregion

    //format json {"Produits": [{"idproduit": 2, "quantite": 300},{"idproduit": 1, "quantite": 20}]}
    public function enregistrerProduits($jsonString)
    {
        $produits = json_decode($jsonString, true);
        if (isset($produits["Produits"])) {
            $produits = $produits["Produits"];
            $this->_produits = array();
            $this->_failProduit = array();
            foreach ($produits as $produit) {
                $prod = Produit::createCourseProduit($produit["idproduit"], $produit["quantite"]);
                if (!$prod->verifierProduit()) {
                    array_push($this->_failProduit, $prod->getIdProduit());
                }else
                    array_push($this->_produits, $prod);
            }
            if (count($this->_failProduit) > 0) {
                $strId = join(",", $this->_failProduit);
                echo '{"Code" : ' . $GLOBALS['CODE']['CODE_18']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_18']['Message'] . '", "IDS" : [' . $strId . ']}';
                exit(1);
            }
        } else {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_17']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_17']['Message'] . '"}';
            exit(1);
        }
    }

    public function validerCourse()
    {
        $db = DatabaseObject::connect();
        $db->beginTransaction();
        $query = $db->prepare("INSERT INTO course(idAdulte, total, dateCourse) VALUES(:id, :total, :date)");
        $query->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $query->bindValue(':total', $this->getMontant());
        $query->bindValue(':date', $this->getDate()->format("Y-m-d"));
        try{
            $query->execute();
        }catch(PDOException $e){
            $db->rollBack();
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        $idCourse = $db->lastInsertId();
        $query = $db->prepare("INSERT INTO detailcourse VALUES(:idC, :idP, :qte)");
        $queryStock = $db->prepare("INSERT INTO stock VALUES(:idP, :qte) ON DUPLICATE KEY UPDATE quantite=quantite+:qte;");
        $query->bindValue(':idC', $idCourse, PDO::PARAM_INT);
        foreach($this->_produits as $produit){
            $query->bindValue(':idP', $produit->getIdProduit(), PDO::PARAM_INT);
            $query->bindValue(':qte', $produit->getQuantite(), PDO::PARAM_INT);
            $queryStock->bindValue(':idP', $produit->getIdProduit(), PDO::PARAM_INT);
            $queryStock->bindValue(':qte', $produit->getQuantite(), PDO::PARAM_INT);
            try{
                $query->execute();
                $queryStock->execute();
            }catch(PDOException $e){
                $db->rollBack();
                echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
                exit(1);
            }
        }
        $db->commit();

    }

}