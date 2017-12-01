<?php

class EnfantHandler implements JsonSerializable
{
    private $_enfants;

    function __construct(){
        $this->loadEnfants();
    }

    function loadEnfants(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM enfant NATURAL JOIN parent");
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : "' . $GLOBALS['CODE']['CODE_5']['Code'] . '", "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $this->_enfants = array();
            foreach($data as $ef){
                $enfant = new Enfant();
                $enfant->hydrate($ef);
                array_push($this->_enfants, $enfant);
            }
        }
    }

    public function getEnfants(){
        return $this->_enfants;
    }

    public function jsonSerialize()
    {
        $string = '"Enfants": [';
        $cnt = 0;
        foreach($this->getEnfants() as $enfant){
            $enfant->loadSolde();
            $string .= $enfant->jsonSerialize() . ', ';
            $cnt++;
        }
        if($cnt > 0)
            $string = substr($string, 0, strlen($string) - 2);
        $string .= ']';
        return $string;
    }
}