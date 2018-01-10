<?php

class RangHandler implements JsonSerializable
{
    private $_rangs;

    public function __construct()
    {
        $this->loadRang();
    }

    function loadRang(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM rang;");
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $this->_rangs = array();
            foreach($data as $rg){
                $rang = new Rang();
                $rang->hydrate($rg);
                array_push($this->_rangs, $rang);
            }
        }
    }

    public function getRangs(){
        return $this->_rangs;
    }

    public function jsonSerialize()
    {
        $string = '"Rangs": [';
        $cnt = 0;
        foreach($this->getRangs() as $rang){
            $string .= $rang->jsonSerialize() . ', ';
            $cnt++;
        }
        if($cnt > 0)
            $string = substr($string, 0, strlen($string) - 2);
        $string .= ']';
        return $string;
    }
}