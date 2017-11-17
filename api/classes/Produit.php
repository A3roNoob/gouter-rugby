<?php
class Produit{
    private $_idProduit,$_nom,$_descProduit,$_prix;

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
    //endregion
    
    private function hydrate($data){
        if(is_array($data)){
            if(isset($data['idProduit']))
                $this->setIdProduit($data['idProduit']);
            if(isset($data['nom']))
                $this->setNom($data['nom']);
            if(isset($data['descProduit']))
                $this->setDescProduit($data['descProduit']);
            if(isset($data['prix']))
                $this->setPrix($data['prix']);
        }
    }
}