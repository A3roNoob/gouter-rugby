<?php

class ProduitHandler  implements JsonSerializable
{
    private $_produits, $_produitscomposes;

    function __construct(){
        $this->loadProduits();
    }

    function loadProduits(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM produit");
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
                array_push($this->_produits, $prod);
            }
        }
//SELECT produitcompose.idProduitCompos, produit.idProduit, produit.nomProduit, produit.descProduit, produit.prix, produitCompose.nomProduit AS 'nomProduitCompos ', produitcompose.descProduit AS 'descProduitCompos' FROM produitcompose, produit, composproduit WHERE produitcompose.idProduitCompos=composproduit.idProduitCompos AND produit.idProduit=composproduit.idProduit
        $query = $db->prepare("SELECT * FROM produitcompose");
        try{
            $query->execute();
            $produits = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($produits)){
            $this->_produitscomposes = array();
            foreach($produits as $produit){
                $prod = new ProduitCompose();
                $prod->hydrate($produit);
                $prod->associerProduits();
                array_push($this->_produitscomposes, $prod);
            }
        }
    }

    function getProduits(){
        return $this->_produits;
    }

    function getProduitsComposes(){
        return $this->_produitscomposes;
    }

    public function jsonSerialize()
    {
        $string = '"Produits": [';
        foreach($this->getProduits() as $produit){
            $string .= $produit->jsonSerialize() . ', ';
        }
        $string = substr($string, 0, strlen($string) - 2);
        $string .= '], "ProduitsComposes": [';
        foreach($this->getProduitsComposes() as $produit){
            $string .= $produit->jsonSerialize() . ', ';
        }
        $string = substr($string, 0, strlen($string) - 2);
        $string .= ']';
        return $string;
    }
}