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

    public function jsonSerialize()
    {
        return '{"Id": ' . $this->getIdProduit() . ', "Nom": "' . $this->getNom() . '", "Description": "'.$this->getDescProduit().'", "Prix": '.$this->getPrix().', "Quantite": '.is_null($this->getQuantite()) ? 0 : $this->getQuantite() .'}';
    }
}