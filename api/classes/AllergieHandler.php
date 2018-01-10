<?php
class AllergieHandler
{
    private $_allergies;

    //region Getters/Setters
    /**
     * @return mixed
     */
    public function getAllergies()
    {
        return $this->_allergies;
    }

    /**
     * @param mixed $allergies
     */

    public function setAllergies($allergies)
    {
        $this->_allergies = $allergies;
    }
    //endregion

    public function __construct()
    {
        $this->loadAllergies();
    }

    function loadAllergies(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM allergene;");
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $this->_allergies = array();
            foreach($data as $all){
                $allergie = new Allergie();
                $allergie->hydrate($all);
                array_push($this->_allergies, $allergie);
            }
        }
    }

    function loadAllergieFromEnfant(Enfant $enfant){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM allergene NATURAL JOIN allergieenfant WHERE idEnfant=:idEnfant;");
        $query->bindValue(':idEnfant', $enfant->getIdEnfant(), PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $this->_allergies = array();
            foreach($data as $all){
                $allergie = new Allergie();
                $allergie->hydrate($all);
                array_push($this->_allergies, $allergie);
            }
        }
    }

    public function jsonSerialize()
    {
        $string = '"Allergies": [';
        $cnt = 0;
        foreach($this->getAllergies() as $allergie){
            $string .= $allergie->jsonSerialize() . ', ';
            $cnt++;
        }
        if($cnt > 0)
            $string = substr($string, 0, strlen($string) - 2);
        $string .= ']';
        return $string;
    }
}