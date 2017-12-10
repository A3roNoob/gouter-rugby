<?php

class Allergie implements JsonSerializable
{
    private $_idAllergene, $_nomAllergene, $_desc;

    //region - Getters/Setters
    /**
     * @return mixed
     */
    public function getIdAllergene()
    {
        return $this->_idAllergene;
    }

    /**
     * @param mixed $idAllergene
     */
    public function setIdAllergene($idAllergene)
    {
        $this->_idAllergene = $idAllergene;
    }

    /**
     * @return mixed
     */
    public function getNomAllergene()
    {
        return $this->_nomAllergene;
    }

    /**
     * @param mixed $nomAllergene
     */
    public function setNomAllergene($nomAllergene)
    {
        $this->_nomAllergene = $nomAllergene;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * @param mixed $desc
     */
    public function setDesc($desc)
    {
        $this->_desc = $desc;
    }
    //endregion

    public function hydrate(array $data){
        if(isset($data)){
            if(isset($data['idAllergene']))
                $this->setIdAllergene($data['idAllergene']);
            if(isset($data['nomAllergene']))
                $this->setNomAllergene($data['nomAllergene']);
            if(isset($data['descAllergene']))
                $this->setDesc($data['descAllergene']);
        }
    }

    public static function loadById($id){
        $db = DatabaseObject::connect();

        $query = $db->prepare("SELECT * FROM allergene WHERE idAllergene=:id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $data = array();
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit();
        }
        if (is_bool($data)) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_11']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_11']['Message'] . '"}';
            exit();
        }
        $allergie = new self();
        $allergie->hydrate($data);
        return $allergie;
    }

    public function jsonSerialize(){
        return '{"ID": '.$this->getIdAllergene() . ', "Nom": "' . $this->getNomAllergene() . '", "Description": "' . $this->getDesc() . '"}';
    }
}