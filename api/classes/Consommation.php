<?php

class Consommation
{
    private $_enfant, $_produits, $_produitscomposes, $_date, $_total, $_failProduit, $_failProduitComposes;

    //region Getter/Setter

    /**
     * @return Enfant
     */
    public function getEnfant()
    {
        return $this->_enfant;
    }

    /**
     * @param Enfant $enfant
     */
    public function setEnfant($enfant)
    {
        $this->_enfant = $enfant;
    }

    /**
     * @return mixed
     */
    public function getProduits()
    {
        return $this->_produits;
    }

    /**
     * @param mixed $produits
     */
    public function setProduits($produits)
    {
        $this->_produits = $produits;
    }

    /**
     * @return mixed
     */
    public function getProduitscomposes()
    {
        return $this->_produitscomposes;
    }

    /**
     * @param mixed $produitscomposes
     */
    public function setProduitscomposes($produitscomposes)
    {
        $this->_produitscomposes = $produitscomposes;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->_date = $date;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->_total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->_total = $total;
    }

    //endregion

    public function enregistrerConsommation($jsonString)
    {
        $code = 0;
        $produits = json_decode($jsonString, true);
        if (isset($produits["Produits"])) {
            $code = 1;
            $prods = $produits["Produits"];
            $this->_produits = array();
            $this->_failProduit = array();
            foreach ($prods as $produit) {
                $prod = Produit::loadProduit($produit);
                if (is_bool($prod)) {
                    array_push($this->_failProduit, $produit);
                } else {
                    array_push($this->_produits, $prod);
                }
            }
        }
        if (isset($produits['ProduitsComposes'])) {
            $code = 1;
            $prods = $produits['ProduitsComposes'];
            $this->_produitscomposes = array();
            $this->_failProduitComposes = array();
            foreach ($prods as $produit) {
                $prod = new ProduitCompose();
                $prod->setIdProduit($produit);
                if (!$prod->associerProduits()) {
                    array_push($this->_failProduitComposes, $produit);
                } else {
                    array_push($this->_produitscomposes, $prod);
                }
            }
        }

        if ($code == 0) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_17']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_17']['Message'] . '"}';
            exit(1);
        }
        $strId = $strIdCompo = "";
        if (is_array($this->_failProduitComposes) && count($this->_failProduitComposes) > 0) {
            $strIdCompo = join(",", $this->_failProduitComposes);
            $strIdCompo = ', "IDSCOMPO" : [' . $strIdCompo . ']';
        }
        if (is_array($this->_failProduit) && count($this->_failProduit) > 0) {
            $strId = join(",", $this->_failProduit);
            $strId = ', "IDS" : [' . $strId . ']';
        }

        if (strlen($strId) > 0 && strlen($strIdCompo) > 0) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_18']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_18']['Message'] . '"' . $strId . $strIdCompo . '}';
            exit(1);
        }
    }

    public function calculerTotal()
    {
        $total = 0;
        if (is_array($this->_produits)) {
            foreach ($this->_produits as $produit)
                $total += $produit->getPrix();
        }
        if(is_array($this->_produitscomposes)) {
            foreach ($this->_produitscomposes as $produit) {
                $produit->calculerPrix();
                $total += $produit->getPrix();
            }
        }
        $this->_total = $total;
    }

    public function checkAllergies(){
        $db = DatabaseObject::connect();
        $query = $db->prepare('SELECT DISTINCT idProduit FROM allergieproduit NATURAL JOIN allergieenfant WHERE idEnfant=:idEnfant');
        $query->bindValue(':idEnfant', $this->getEnfant()->getIdEnfant(), PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(count($data) > 0){
            $listAllergie = array();
            foreach($data as $subArray){
                if(is_array($this->_produits)) {
                    foreach ($this->_produits as $produit)
                        if ($produit->getIdProduit() == $subArray["idProduit"])
                            array_push($listAllergie, $subArray["idProduit"]);
                }
                if(is_array($this->_produitscomposes)) {
                    foreach ($this->_produitscomposes as $produitcompose)
                        foreach ($produitcompose->getProduits() as $produit)
                            if ($produit->getIdProduit() == $subArray["idProduit"])
                                array_push($listAllergie, $subArray["idProduit"]);
                }
            }

            if(count($listAllergie) > 0) {
                $allergies = join(",", $listAllergie);
                echo '{"Code" : ' . $GLOBALS['CODE']['CODE_20']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_20']['Message'] . '", "IDS" : [' . $allergies . ']}';
                exit(1);
            }
        }
    }

    public function validerConsommation()
    {
        $db = DatabaseObject::connect();
        $db->beginTransaction();
        $query = $db->prepare("INSERT INTO consommation(idEnfant, total, dateConso) VALUES(:id, :total, :date)");
        $query->bindValue(':id', $this->getEnfant()->getIdEnfant(), PDO::PARAM_INT);
        $query->bindValue(':total', $this->getTotal());
        $query->bindValue(':date', $this->getDate()->format("Y-m-d H:m:s"));
        try {
            $query->execute();
        } catch (PDOException $e) {
            $db->rollBack();
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        $idConso = $db->lastInsertId();

        if(is_array($this->_produits) && count($this->_produits) > 0) {
            $query = $db->prepare("INSERT INTO detailconso VALUES(:idC, :idP, :qte)");
            $queryStock = $db->prepare("UPDATE stock SET quantite=quantite-:qte WHERE idProduit=:idP");
            $query->bindValue(':idC', $idConso, PDO::PARAM_INT);
            foreach ($this->_produits as $produit) {
                $query->bindValue(':idP', $produit->getIdProduit(), PDO::PARAM_INT);
                $query->bindValue(':qte', 1, PDO::PARAM_INT);
                $queryStock->bindValue(':idP', $produit->getIdProduit(), PDO::PARAM_INT);
                $queryStock->bindValue(':qte', 1, PDO::PARAM_INT);
                try {
                    $query->execute();
                    $queryStock->execute();
                } catch (PDOException $e) {
                    $db->rollBack();
                    echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
                    exit(1);
                }
            }
        }

        if(is_array($this->_produitscomposes) && count($this->_produitscomposes) > 0){
            $query = $db->prepare("INSERT INTO detailconso VALUES(:idC, :idP, :qte)");
            $queryStock = $db->prepare("UPDATE stock SET quantite=quantite-:qte WHERE idProduit=:idP");
            $query->bindValue(':idC', $idConso, PDO::PARAM_INT);
            foreach ($this->_produitscomposes as $produitcomposes) {
                foreach($produitcomposes->getProduits() as $produit) {
                    $query->bindValue(':idP', $produit->getIdProduit(), PDO::PARAM_INT);
                    $query->bindValue(':qte', 1, PDO::PARAM_INT);
                    $queryStock->bindValue(':idP', $produit->getIdProduit(), PDO::PARAM_INT);
                    $queryStock->bindValue(':qte', 1, PDO::PARAM_INT);
                    try {
                        $query->execute();
                        $queryStock->execute();
                    } catch (PDOException $e) {
                        $db->rollBack();
                        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
                        exit(1);
                    }
                }
            }
        }
        $query = $db->prepare("UPDATE compte SET solde=solde-:valeur WHERE idEnfant=:id");
        $query->bindValue(':id', $this->getEnfant()->getIdEnfant(), PDO::PARAM_INT);
        $query->bindValue(':valeur', $this->getTotal());
        try{
            $query->execute();
        }catch(PDOException $e){
            $db->rollBack();
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
        $db->commit();

    }
}