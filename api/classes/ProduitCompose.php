<?php

class ProduitCompose
{
    private $_idProduit, $_produits, $_nom, $_descProduit, $_prix;

    //region Getter/Setter
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
    public function ajouterProduits($produits)
    {
        array_push($this->_produits, $produits);
    }

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
    //endregion

    public function hydrate($data){
        if(is_array($data)){
            if(isset($data['idProduitCompos']))
                $this->setIdProduit($data['idProduitCompos']);
            if(isset($data['nomProduit']))
                $this->setNom($data['nomProduit']);
            if(isset($data['descProduit']))
                $this->setDescProduit($data['descProduit']);
            $this->_produits = array();
        }
    }

    function calculerPrix(){
        foreach($this->_produits as $produit){
            $this->_prix += $produit->getPrix();
        }
    }

    function associerProduits(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM composproduit NATURAL JOIN produit WHERE idProduitCompos=:id");
        $query->bindValue(':id', $this->getIdProduit(), PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $this->_produits = array();
            foreach($data as $produit){
                $prod = new Produit();
                $prod->hydrate($produit);
                $this->ajouterProduits($prod);
            }
        }


    }

    public function jsonSerialize()
    {
        $this->calculerPrix();
        $string = '{"Id": ' . $this->getIdProduit() . ', "Nom": "' . $this->getNom() . '", "Description": "'.$this->getDescProduit().'", "Prix": '.$this->getPrix().', "Produits": [';
        foreach($this->getProduits() as $produit){
            $string .= $produit->jsonSerialize() . ', ';
        };
        $string = substr($string, 0, strlen($string) - 2);
        $string .= "]}";
        return $string;
    }
}