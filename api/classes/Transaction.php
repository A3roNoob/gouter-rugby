<?php

class Transaction implements JsonSerializable
{
    private $_idOpe, $_adulte, $_enfant, $_montant, $_date;

    //region Getter/Setter
    /**
     * @return int
     */
    public function getIdOpe()
    {
        return $this->_idOpe;
    }

    /**
     * @param int $idOpe
     */
    public function setIdOpe($idOpe)
    {
        $this->_idOpe = $idOpe;
    }

    /**
     * @return Adulte
     */
    public function getAdulte()
    {
        return $this->_adulte;
    }

    /**
     * @param int $adulte
     */
    public function setAdulte($adulte)
    {
        $this->_adulte = Adulte::loadById($adulte);
    }

    /**
     * @return Enfant
     */
    public function getEnfant()
    {
        return $this->_enfant;
    }

    /**
     * @param int $enfant
     */
    public function setEnfant($enfant)
    {
        $this->_enfant = Enfant::loadById($enfant);
    }


    /**
     * @return float
     */
    public function getMontant()
    {
        return $this->_montant;
    }

    /**
     * @param float $montant
     */
    public function setMontant($montant)
    {
        $this->_montant = $montant;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->_date = DateTime::createFromFormat("Y-m-d", $data);
    }
    //endregion

    public function hydrate(array $data){
        if(isset($data['idOperation']))
            $this->setIdOpe($data['idOperation']);
        if(isset($data['idAdulte']))
            $this->setAdulte($data['idAdulte']);
        if(isset($data['montant']))
            $this->setMontant($data['montant']);
        if(isset($data['dateOpe']))
            $this->setDate($data['dateOpe']);
    }

    public function jsonSerialize()
    {
        return '{"ID": '.$this->getIdOpe().', "Adulte" : '.$this->getAdulte()->jsonSerialize().', "Enfant": '.$this->getEnfant()->jsonSerialize().', "Montant" : '.$this->getMontant().', "DateOperation" : "'.$this->getDate()->format("d/m/Y").'"}';
    }

}