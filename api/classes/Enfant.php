<?php
class Enfant implements JsonSerializable
{
    private $_idEnfant, $_idParent, $_nom, $_prenom, $_solde;

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


    //endregion

    public function hydrate($data){
        if(is_array($data)){
            if(isset($data['idEnfant']))
                $this->setIdEnfant($data['idEnfant']);
            if(isset($data['idAdulte']))
                $this->setIdParent($data['idAdulte']);
            if(isset($data['nom']))
                $this->setNom($data['nom']);
            if(isset($data['prenom']))
                $this->setPrenom($data['prenom']);
            if(isset($data['solde']))
                $this->setSolde($data['solde']);
        }
    }

    public static function createEnfant(array $data){
        $enf = new self();
        $enf->hydrate($data);
        return $enf;
    }

    public static function loadById($id){
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
        if(is_bool($data)){
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_11']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_11']['Message'] . '"}';
            exit();
        }
        $enfant = new self();
        $enfant->hydrate($data);
        $enfant->loadSolde();
        return $enfant;
    }

    public function ajouterSolde($somme){
        if($somme+$this->getSolde() <= SOLDE_LIMIT) {
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
        }
        else{
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_9']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_9']['Message'] . '"}';
            exit(1);
        }
        $this->loadSolde();
    }

    public function loadSolde(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT solde FROM compte WHERE idEnfant=:id;");
        $query->bindValue(':id', $this->getIdEnfant(), PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "'.$e->getMessage() .'"}';
            exit(1);
        }

        if(is_bool($data)){
            $this->setSolde("0");
            return;
        }
        $this->setSolde($data['solde']);
    }

    function jsonSerialize()
    {
        return '{"ID" : ' . $this->getIdEnfant() . ', "Nom" : "' . $this->getNom() . '", "Prenom" : "' . $this->getPrenom() . '", "Solde" : ' . $this->getSolde() . '}';
    }
}