<?php

class ProduitHandler  implements JsonSerializable
{
    private $_produits;

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
    }

    function getProduits(){
        return $this->_produits;
    }

    public function jsonSerialize()
    {
        $string = '"Produits": [';
        foreach($this->getProduits() as $produit){
            $string .= $produit->jsonSerialize() . ', ';
        }
        $string = substr($string, 0, strlen($string) - 2);
        $string .= ']';
        return $string;
    }
}