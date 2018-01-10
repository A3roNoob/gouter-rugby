<?php

class TransactionHandler
{
    private $_transactions;

    public function loadEnfantOperations($nbOperations, $idEnfant){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM operationsolde WHERE idEnfant=:idEnfant LIMIT :limite");
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
            $this->_transactions = array();
            foreach($data as $tr){
                $transaction = new Transaction();
                $transaction->hydrate($tr);
                array_push($this->_transactions, $transaction);
            }
        }
    }

    public function loadOperations($nbOperations){
        $db = DatabaseObject::connect();
        $query = $db->prepare("SELECT * FROM operationsolde LIMIT :limite");
        $query->bindValue(':limite', $nbOperations, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo '{"Code" : ' . $GLOBALS['CODE']['CODE_5']['Code'] . ', "Message" : "' . $GLOBALS['CODE']['CODE_5']['Message'] . '", "INFOS" : "' . $e->getMessage() . '"}';
            exit(1);
        }

        if(!is_bool($data)){
            $this->_transactions = array();
            foreach($data as $tr){
                $transaction = new Transaction();
                $transaction->hydrate($tr);
                array_push($this->_transactions, $transaction);
            }
        }

    }

    public function getTransactions(){
        return $this->_transactions;
    }

    public function jsonSerialize(){
        $string = '"Transactions": [';
        $cnt = 0;
        foreach($this->getTransactions() as $transaction){
            $string .= $transaction->jsonSerialize() . ', ';
            $cnt++;
        }
        if($cnt > 0)
            $string = substr($string, 0, strlen($string) - 2);
        $string .= ']';
        return $string;
    }

}