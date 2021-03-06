<?php

class Adulte
{
    private $_idAdulte, $_nom, $_prenom, $_mail, $_tel, $_solde, $_mdp, $_idRang, $_token, $_enfants;

    //region Getter/Setters

    /**
     * @return int
     */
    public function getIdAdulte()
    {
        return $this->_idAdulte;
    }

    /**
     * @param int $idAdulte
     */
    public function setIdAdulte($idAdulte)
    {
        $this->_idAdulte = $idAdulte;
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
    public function getPrenom()
    {
        return $this->_prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->_prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->_mail;
    }

    /**
     * @param string $mail
     */
    public function setMail($mail)
    {
        $this->_mail = $mail;
    }

    /**
     * @return int
     */
    public function getTel()
    {
        return $this->_tel;
    }

    /**
     * @param int $tel
     */
    public function setTel($tel)
    {
        $this->_tel = $tel;
    }

    /**
     * @return double
     */
    public function getSolde()
    {
        return $this->_solde;
    }

    /**
     * @param double $solde
     */
    public function setSolde($solde)
    {
        $this->_solde = $solde;
    }

    /**
     * @return string
     */
    public function getMdp()
    {
        return $this->_mdp;
    }

    /**
     * @param string $mdp
     */
    public function setMdp($mdp)
    {
        $this->_mdp = $mdp;
    }

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
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    /**
     * @return array
     */
    public function getEnfants()
    {
        return $this->_enfants;
    }

    /**
     * @param array $enfants
     */
    public function setEnfants($enfants)
    {
        $this->_enfants = $enfants;
    }

    //endregion

    public function hydrate(array $data)
    {
            if (isset($data['idAdulte']))
                $this->setIdAdulte($data['idAdulte']);
            if (isset($data['idRang']))
                $this->setIdRang($data['idRang']);
            if (isset($data['nom']))
                $this->setNom($data['nom']);
            if (isset($data['prenom']))
                $this->setPrenom($data['prenom']);
            if (isset($data['mail']))
                $this->setMail($data['mail']);
            if (isset($data['tel']))
                $this->setTel($data['tel']);
            if (isset($data['solde']))
                $this->setSolde($data['solde']);
            $this->setEnfants(array());
    }

    public static function Connexion($mail, $motdepasse)
    {
        $db = DatabaseObject::connect();

        $query = $db->prepare("SELECT * FROM adulte WHERE mail=:mail;");
        $query->bindValue(':mail', $mail);
        $data = array();
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit();
        }

        if (isset($data['mdp']) && $data['mdp'] == md5($motdepasse)) {
            $adulte = new self();
            $adulte->hydrate($data);
            $adulte->loadEnfants();
            return $adulte;
        }
        echo '{"Code" : ' . $GLOBALS['CODE']['CODE_2']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_2']['Message'] . '"}';
        exit();
    }

    public static function loadByLogin($mail)
    {
        $db = DatabaseObject::connect();

        $query = $db->prepare("SELECT * FROM adulte WHERE adulte.mail=:mail");
        $query->bindValue(':mail', $mail);
        $data = array();
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit();
        }
        if(is_bool($data)){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_7']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_7']['Message'] . '"}';
            exit();
        }
        $adulte = new self();
        $adulte->hydrate($data);
        $adulte->loadEnfants();
        return $adulte;
    }

    public static function loadById($id){
        $db = DatabaseObject::connect();

        $query = $db->prepare("SELECT * FROM adulte WHERE adulte.idAdulte=:id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $data = array();
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit();
        }
        if(is_bool($data)){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_7']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_7']['Message'] . '"}';
            exit();
        }
        $adulte = new self();
        $adulte->hydrate($data);
        $adulte->loadEnfants();
        return $adulte;
    }

    public function generateToken()
    {
        $token = md5(time() . md5($this->getMail()) . $this->getIdAdulte());

        $db = DatabaseObject::connect();
        $query = $db->prepare("REPLACE INTO connexion VALUES(:idA, :token)");
        $query->bindValue(':idA', $this->getIdAdulte(), PDO::PARAM_INT);
        $query->bindValue(':token', $token);

        try {
            $query->execute();
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
        $this->setToken($token);
    }

    public function checkToken($token)
    {
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT token FROM connexion WHERE connexion.idAdulte=:idA;");
        $query->bindValue(':idA', $this->getIdAdulte(), PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
        if (is_bool($data))
            return false;

        return $data['token'] == $token && $token != "0";
    }

    public function deleteToken(){
        $db = DatabaseObject::connect();
        $query = $db->prepare("UPDATE connexion SET token=0 WHERE connexion.idAdulte=:idA;");
        $query->bindValue(':idA', $this->getIdAdulte(), PDO::PARAM_INT);
        try {
            $query->execute();
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
    }

    public function loadEnfants()
    {
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM enfant NATURAL JOIN parent WHERE idAdulte=:idA;");
        $query->bindValue(':idA', $this->getIdAdulte(), PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
        }

        if (is_bool($data)) {
            exit();
        }

        foreach ($data as $enfant) {
            $enfant["idParent"] = $this->getIdAdulte();
            $enf = Enfant::createEnfant($enfant);
            $enf->loadSolde();
            $allergie = new AllergieHandler();
            $allergie->loadAllergieFromEnfant($enf);
            $enf->setAllergies($allergie);
            array_push($this->_enfants, $enf);
        }

    }

    public function getEnfantById($id){
        foreach($this->getEnfants() as $enfant)
            if($enfant->getIdEnfant() == $id){
                return $enfant;
            }
        return null;
    }

    public static function creerAdulte($nom, $prenom, $idrang, $mail, $tel, $mdp){
        $adulte = new self();
        $adulte->setNom($nom);
        $adulte->setPrenom($prenom);
        $adulte->setIdRang($idrang);
        $adulte->setMail($mail);
        $adulte->setTel($tel);
        $adulte->setMdp(md5($mdp));
        return $adulte;
    }

    public function enregistrer(){
        if(!is_null($this->getIdAdulte())){
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_22']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_22']['Message'] . '"}';
            exit(1);
        }
        $db = DatabaseObject::connect();
        $db->beginTransaction();

        $query = $db->prepare("INSERT INTO adulte(nom, prenom, idRang, mail, tel, mdp) VALUES (:nom, :prenom, :rang, :mail, :tel, :mdp)");
        $query->bindValue(':nom', $this->getNom());
        $query->bindValue(':prenom', $this->getPrenom());
        $query->bindValue(':mail', $this->getMail());
        $query->bindValue(':tel', $this->getTel());
        $query->bindValue(':mdp', $this->getMdp());
        $query->bindValue(':rang', $this->getIdRang(), PDO::PARAM_INT);
        try{
            $query->execute();
        }catch(PDOException $e){
            $db->rollBack();
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        $this->setIdAdulte($db->lastInsertId());

        $db->commit();
    }

    public function update()
    {
        $db = DatabaseObject::connect();
        $query = $db->prepare("UPDATE adulte SET nom=:nom, prenom=:prenom, mail=:mail, tel=:tel, mdp=:mdp WHERE idadulte=:id");
        $query->bindValue(':id', $this->getIdAdulte(), PDO::PARAM_INT);
        $query->bindValue(':nom', $this->getNom());
        $query->bindValue(':prenom', $this->getPrenom());
        $query->bindValue(':mail', $this->getMail());
        $query->bindValue(':tel', $this->getTel());
        $query->bindValue(':mdp', $this->getMdp());
        try {
            $query->execute();
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
    }

    public function supprimer()
    {
        $db = DatabaseObject::connect();
        $query = $db->prepare("DELETE FROM adulte WHERE idAdulte=:idAdulte");
        $query->bindValue(':idAdulte', $this->getIdAdulte(), PDO::PARAM_INT);
        try {
            $query->execute();
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }
    }
    public function jsonSerialize(){
        return '{"ID" : ' . $this->getIdAdulte() . ', "Nom" : "' . $this->getNom() . '", "Prenom" : "' . $this->getPrenom() . '", "Mail" : "'.$this->getMail().'", "Phone" : "'.$this->getTel().'", "Solde": '.$this->getSolde().', "Rang" : '.$this->getIdRang().'}';

    }
}