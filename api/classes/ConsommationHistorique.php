<?php

class ConsommationHistorique
{
    private $_idConso, $_enfant, $_total, $_dateConso, $_produits;

    //region Getter/Setter

    /**
     * @return int
     */
    public function getIdConso()
    {
        return $this->_idConso;
    }

    /**
     * @param int $idConso
     */
    public function setIdConso($idConso)
    {
        $this->_idConso = $idConso;
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
    public function getTotal()
    {
        return $this->_total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->_total = $total;
    }

    /**
     * @return DateTime
     */
    public function getDateConso()
    {
        return $this->_dateConso;
    }

    /**
     * @param string $dateConso
     */
    public function setDateConso($dateConso)
    {
        $this->_dateConso = new DateTime($dateConso);
    }

    //endregion

    public function hydrate(array $data)
    {
        if (isset($data['idConso']))
            $this->setIdConso($data['idConso']);
        if (isset($data['idEnfant']))
            $this->setEnfant($data['idEnfant']);
        if (isset($data['total']))
            $this->setTotal($data['total']);
        if (isset($data['dateConso']))
            $this->setDateConso($data['dateConso']);
    }

    public function loadProduits()
    {
        $db = DatabaseObject::connect();

        $query = $db->prepare("SELECT * FROM detailconso WHERE idConso=:id");
        $query->bindValue(':id', $this->getIdConso(), PDO::PARAM_INT);
        $data = array();
        try {
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit();
        }
        if (is_bool($data)) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_11']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_11']['Message'] . '"}';
            exit();
        }
        $this->_produits = array();
        foreach ($data as $produit) {
            $prod = new Produit();
            $prod->hydrate($produit);
            array_push($this->_produits, $prod);
        }
    }

    public function getProduit()
    {
        return $this->_produits;
    }

    public function produitJson()
    {
        $string = '"Produits": [';
        $cnt = 0;
        foreach ($this->getProduit() as $produit) {
            $string .= $produit->getIdProduit() . ', ';
            $cnt++;
        }
        if ($cnt > 0)
            $string = substr($string, 0, strlen($string) - 2);
        $string .= ']';
        return $string;
    }

    public function jsonSerialize()
    {
        return '{"ID": ' . $this->getIdConso() . ', "Enfant": ' . $this->getEnfant()->jsonSerialize() . ', "Total" : ' . $this->getTotal() . ', "DateConso" : "' . $this->getDateConso()->format("d/m/Y") . '", ' . $this->produitJson() . '}';
    }

}