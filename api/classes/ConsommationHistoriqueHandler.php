<?php

class ConsommationHistoriqueHandler implements JsonSerializable
{
    private $_consommations;

    public function loadEnfantOperations($nbOperations, $idEnfant){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM consommation WHERE idEnfant=:idEnfant LIMIT :limite");
        $query->bindValue(':idEnfant', $idEnfant, PDO::PARAM_INT);
        $query->bindValue(':limite', $nbOperations, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $this->_consommations = array();
            foreach($data as $conso){
                $consommation = new ConsommationHistorique();
                $consommation->hydrate($conso);
                $consommation->loadProduits();
                array_push($this->_consommations, $consommation);
            }
        }
    }

    public function loadOperations($nbOperations){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM consommation LIMIT :limite");
        $query->bindValue(':limite', $nbOperations, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $this->_consommations = array();
            foreach($data as $conso){
                $consommation = new ConsommationHistorique();
                $consommation->hydrate($conso);
                $consommation->loadProduits();
                array_push($this->_consommations, $consommation);
            }
        }
    }

    public function getConsommations(){
        return $this->_consommations;
    }

    public function jsonSerialize(){
        $string = '"Consommations": [';
        $cnt = 0;
        foreach($this->getConsommations() as $consommation){
            $string .= $consommation->jsonSerialize() . ', ';
            $cnt++;
        }
        if($cnt > 0)
            $string = substr($string, 0, strlen($string) - 2);
        $string .= ']';
        return $string;
    }
}