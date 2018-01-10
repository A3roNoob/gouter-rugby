<?php

class Rang implements JsonSerializable
{
    private $_idRang, $_nom;

    //region Getter/Setter
    /**
     * @return int
     */
    public function getIdRang()
    {
        return $this->_idRang;
    }

    /**
     * @param int $idRang
     */
    public function setIdRang($idRang)
    {
        $this->_idRang = $idRang;
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
    //endregion

    public function hydrate(array $data){
        if(isset($data['idRang']))
            $this->setIdRang($data['idRang']);
        if(isset($data['nomRang']))
            $this->setNom($data['nomRang']);
    }

    public static function loadById($id){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM rang WHERE idRang=:id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if (is_bool($data)) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_23']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_23']['Message'] . '"}';
            exit(1);
        }

        $rang = new self();
        $rang->hydrate($data);
        return $rang;
    }

    public function jsonSerialize(){
        return '{"ID": '.$this->getIdRang() . ', "Nom": "' . $this->getNom() . '"}';
    }
}