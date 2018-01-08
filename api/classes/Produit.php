<?php
class Produit implements JsonSerializable{
    private $_idProduit,$_nom,$_descProduit,$_prix, $_quantite;

    //region Getter/Setter
    /**
     * @return int
     */
    public function getIdProduit()
    {
        return $this->_idProduit;
    }

    /**
     * @param int $idProduit
     */
    public function setIdProduit($idProduit)
    {
        $this->_idProduit = $idProduit;
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
    public function getDescProduit()
    {
        return $this->_descProduit;
    }

    /**
     * @param string $descProduit
     */
    public function setDescProduit($descProduit)
    {
        $this->_descProduit = $descProduit;
    }

    /**
     * @return float
     */
    public function getPrix()
    {
        return $this->_prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix($prix)
    {
        $this->_prix = $prix;
    }

    /**
     * @return int
     */
    public function getQuantite()
    {
        return $this->_quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite($quantite)
    {
        $this->_quantite = $quantite;
    }

    //endregion

    public static function createCourseProduit($id, $quantite){
        $prod = new self();
        $prod->setIdProduit($id);
        $prod->setQuantite($quantite);
        return $prod;
    }

    public static function creerProduit($nom, $desc, $prix){
        $prod = new self();
        $prod->setNom($nom);
        $prod->setDescProduit($desc);
        $prod->setPrix($prix);
        return $prod;
    }

    public function enregistrerProduit(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("INSERT INTO produit(nomProduit, descProduit, prix) VALUES(:nom, :descr, :prix)");
        $query->bindValue(':nom', $this->getNom());
        $query->bindValue(':descr', $this->getDescProduit());
        $query->bindValue(':prix', $this->getPrix());

        try{
            $query->execute();
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
    }

    public function hydrate(array $data){
            if(isset($data['idProduit']))
                $this->setIdProduit($data['idProduit']);
            if(isset($data['nomProduit']))
                $this->setNom($data['nomProduit']);
            if(isset($data['descProduit']))
                $this->setDescProduit($data['descProduit']);
            if(isset($data['prix']))
                $this->setPrix($data['prix']);
            if(isset($data['quantite']))
                $this->setQuantite($data['quantite']);
    }

    public static function loadProduit($idProduit){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM produit WHERE idProduit=:id");
        $query->bindValue(':id', $idProduit, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $produit = new self();
            $produit->hydrate($data);
            return $produit;
        }
        return false;
    }

    public function updateProduit(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("UPDATE produit SET nomProduit=:nom, descProduit=:descr, prix=:prix WHERE idProduit=:id");
        $query->bindValue(':id', $this->getIdProduit(), PDO::PARAM_INT);
        $query->bindValue(':nom', $this->getNom());
        $query->bindValue(':descr', $this->getDescProduit());
        $query->bindValue(':prix', $this->getPrix());
        try{
            $query->execute();
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
    }

    public function verifierProduit(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM produit WHERE idProduit=:id");
        $query->bindValue(':id', $this->_idProduit, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!$data)
            return false;
        return true;
    }

    public function supprimerProduit(){
        if($this->verifierProduit()){
            $db = DatabaseObject::connect();
            $query = $db->prepare("DELETE FROM produit WHERE idProduit=:id");
            $query->bindValue(':id', $this->getIdProduit(), PDO::PARAM_INT);
            try{
                $query->execute();
            }catch(PDOException $e){
                echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
                exit(1);
            }
        }else{
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_18']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_18']['Message'] . '", "IDS" : [' . $this->getIdProduit() . ']}';
            exit(1);
        }
    }

    public function jsonSerialize()
    {
        return '{"Id": ' . $this->getIdProduit() . ', "Nom": "' . $this->getNom() . '", "Description": "'.$this->getDescProduit().'", "Prix": '.$this->getPrix().', "Quantite": '.is_null($this->getQuantite()) ? 0 : $this->getQuantite() .'}';
    }
}