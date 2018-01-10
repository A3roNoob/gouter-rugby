<?php
/**
 * Created by IntelliJ IDEA.
 * User: boutr
 * Date: 10/01/2018
 * Time: 11:46
 */

class AdulteHandler implements JsonSerializable
{
    private $_adultes;

    function __construct(){
        $this->loadAdultes();
    }

    function loadAdultes(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM adulte");
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $this->_adultes = array();
            foreach($data as $ad){
                $adulte = new Adulte();
                $adulte->hydrate($ad);
                array_push($this->_adultes, $adulte);
            }
        }
    }

    public function getAdultes(){
        return $this->_adultes;
    }

    public function jsonSerialize()
    {
        $string = '"Adultes": [';
        $cnt = 0;
        foreach($this->getAdultes() as $adulte){
            $string .= $adulte->jsonSerialize() . ', ';
            $cnt++;
        }
        if($cnt > 0)
            $string = substr($string, 0, strlen($string) - 2);
        $string .= ']';
        return $string;
    }

}