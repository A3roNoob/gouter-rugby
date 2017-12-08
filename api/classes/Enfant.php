<?php

class Enfant implements JsonSerializable
{
    private $_idEnfant, $_idParent, $_nom, $_prenom, $_solde, $_allergies;

    //region Getter - Setters

    /**
     * @return int
     */
    public function getIdEnfant()
    {
        return $this->_idEnfant;
    }

    /**
     * @param int $idEnfant
     */
    public function setIdEnfant($idEnfant)
    {
        $this->_idEnfant = $idEnfant;
    }

    /**
     * @return int
     */
    public function getIdParent()
    {
        return $this->_idParent;
    }

    /**
     * @param int $idParent
     */
    public function setIdParent($idParent)
    {
        $this->_idParent = $idParent;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->_nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->_nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->_prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->_prenom = $prenom;
    }

    /**
     * @return float
     */
    public function getSolde()
    {
        return $this->_solde;
    }

    /**
     * @param float $solde
     */
    public function setSolde($solde)
    {
        $this->_solde = $solde;
    }

    /**
     * @return AllergieHandler
     */
    public function getAllergies()
    {
        return $this->_allergies;
    }

    /**
     * @param AllergieHandler $allergies
     */
    public function setAllergies(AllergieHandler $allergies)
    {
        $this->_allergies = $allergies;
    }
    //endregion

    public function hydrate($data)
    {
        if (is_array($data)) {
            if (isset($data['idEnfant']))
                $this->setIdEnfant($data['idEnfant']);
            if (isset($data['idAdulte']))
                $this->setIdParent($data['idAdulte']);
            if (isset($data['nom']))
                $this->setNom($data['nom']);
            if (isset($data['prenom']))
                $this->setPrenom($data['prenom']);
            if (isset($data['solde']))
                $this->setSolde($data['solde']);
        }
    }

    public static function createEnfant(array $data)
    {
        $enf = new self();
        $enf->hydrate($data);
        return $enf;
    }

    public static function loadById($id)
    {
        $db = DatabaseObject::connect();

        $query = $db->prepare("SELECT * FROM enfant NATURAL JOIN parent WHERE enfant.idEnfant=:id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $data = array();
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit();
        }
        if (is_bool($data)) {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_11']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_11']['Message'] . '"}';
            exit();
        }
        $enfant = new self();
        $enfant->hydrate($data);
        $enfant->loadSolde();
        $allergie = new AllergieHandler();
        $allergie->loadAllergieFromEnfant($enfant);
        $enfant->setAllergies($allergie);
        return $enfant;
    }

    public function ajouterSolde($somme)
    {
        if ($somme + $this->getSolde() <= SOLDE_LIMIT) {
            $db = DatabaseObject::connect();

            $query = $db->prepare("REPLACE INTO compte(idEnfant, solde) VALUES(:id, :solde);");
            $query->bindValue(':id', $this->getIdEnfant(), PDO::PARAM_INT);
            $query->bindValue(':solde', ($this->getSolde() + $somme));
            try {
                $query->execute();
            } catch (PDOException $e) {
                echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
                exit(1);
            }
        } else {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_9']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_9']['Message'] . '"}';
            exit(1);
        }
        $this->loadSolde();
    }

    public function loadSolde()
    {
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT solde FROM compte WHERE idEnfant=:id;");
        $query->bindValue(':id', $this->getIdEnfant(), PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if (is_bool($data)) {
            $this->setSolde("0");
            return;
        }
        $this->setSolde($data['solde']);
    }

    function jsonSerialize()
    {
        if(is_null($this->getAllergies()))
        {
            $allergie = new AllergieHandler();
            $allergie->loadAllergieFromEnfant($this);
            $this->setAllergies($allergie);
        }
        return '{"ID" : ' . $this->getIdEnfant() . ', "Nom" : "' . $this->getNom() . '", "Prenom" : "' . $this->getPrenom() . '", "Solde" : ' . $this->getSolde() . ', '.$this->getAllergies()->jsonSerialize().'}';
    }

    /**
     * @param Enfant $enfant
     * @param float $montant
     */
    public function transferer(Enfant $enfant, $montant)
    {
        if (!is_null($enfant)) {
            $this->loadSolde();
            $enfant->loadSolde();
            if ($this->getSolde() - $montant >= 0) {
                if ($enfant->getSolde() + $montant <= SOLDE_LIMIT) {
                    $db = DatabaseObject::connect();
                    //TRANSACTION
                    if ($db->beginTransaction()) {

                        $query = $db->prepare("REPLACE INTO compte(idEnfant, solde) VALUES(:enfant, :montant);");
                        $query->bindValue(':enfant', $this->getIdEnfant(), PDO::PARAM_INT);
                        $query->bindValue(':montant', $this->getSolde() - $montant);
                        try {
                            $query->execute();
                        } catch (PDOException $e) {
                            $db->rollBack();
                            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
                            exit(1);
                        }
                        $query->bindValue(':enfant', $enfant->getIdEnfant(), PDO::PARAM_INT);
                        $query->bindValue(':montant', $enfant->getSolde() + $montant);
                        try {
                            $query->execute();
                        } catch (PDOException $e) {
                            $db->rollBack();
                            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
                            exit(1);
                        }
                    }
                    $db->commit();

                    $enfant->loadSolde();
                    $this->loadSolde();
                } else {
                    echo '{"Code" : "' . $GLOBALS['CODE']['CODE_9']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_9']['Message'] . '"}';
                    exit(1);
                }
            } else {
                echo '{"Code" : "' . $GLOBALS['CODE']['CODE_13']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_13']['Message'] . '"}';
                exit(1);
            }
        } else {
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_11']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_11']['Message'] . '"}';
            exit(1);
        }
    }

    public function ajouterAllergie(Allergie $all){
        $db = DatabaseObject::connect();

        $query = $db->prepare("REPLACE INTO allergieenfant VALUES (:idEnfant, :idAllergene);");
        $query->bindValue(':idEnfant', $this->getIdEnfant(), PDO::PARAM_INT);
        $query->bindValue(':idAllergene', $all->getIdAllergene(), PDO::PARAM_INT);
        try{
            $query->execute();
        }catch(PDOException $e){
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
    }

    public function retirerAllergie(Allergie $all){
        $db = DatabaseObject::connect();

        $query = $db->prepare("DELETE FROM allergieenfant WHERE idEnfant=:idEnfant AND idAllergene=:idAllergene;");
        $query->bindValue(':idEnfant', $this->getIdEnfant(), PDO::PARAM_INT);
        $query->bindValue(':idAllergene', $all->getIdAllergene(), PDO::PARAM_INT);
        try{
            $query->execute();
        }catch(PDOException $e){
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
    }

    public function checkAllergie(Allergie $all){
        foreach($this->getAllergies()->getAllergies() as $allergie){
            if($all->getIdAllergene() == $allergie->getIdAllergene())
                return true;
        }
        return false;
    }
}