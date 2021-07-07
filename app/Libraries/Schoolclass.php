<?php
namespace App\Libraries;

class Schoolclass {

    private $pdo;

    function __construct()
    {
        $this->pdo = \App\Libraries\PDO::get();
    }

    public function getNew()
    {
        return array(
            'id' => -1,
            'evfolyam' => '',
            'betujel' => '',
            'nev' => '',
            'rovid_nev' => '',
        );
    }

    public function getValidationRules()
    {
        return array(
            'id' => "integer|required",
            'nev' => "required",
            'rovid_nev' => "required",
            'evfolyam' => "required",
            'betujel' => "required",
        );
    }

    public function populateFromArray($data)
    {
        return array(
            'id' => (int)\App\Libraries\Helper::get($data, 'id', -1),
            'nev' => \App\Libraries\Helper::get($data, 'nev'),
            'rovid_nev' => \App\Libraries\Helper::get($data, 'rovid_nev'),
            'evfolyam' => \App\Libraries\Helper::get($data, 'evfolyam'),
            'betujel' => \App\Libraries\Helper::get($data, 'betujel'),
        );
    }

    public function get($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tb_osztaly WHERE id = ?;');
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) return null;
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    //  rawArray: true: sima fetch
    //            false: a tomb id-je az osztaly id-je
    public function getAll($orderBy = '', $rawArray = true)
    {
        $stmt = $this->pdo->query('SELECT * FROM tb_osztaly ' . $orderBy . ';');
        
        if ($rawArray) return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $temp = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result = array();
        foreach($temp as $v) { $result[ $v['id'] ] = $v; }

        return $result;
    }

    public function getAllToApi()
    {
        $stmt = $this->pdo->query('SELECT * FROM tb_osztaly;');
        
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result = array();
        foreach($rows as $row) {
            $result[] = array(
                'id' => (int)$row['id'],
                'evfolyam' => (int)$row['evfolyam'],
                'betujel' => $row['betujel'],
                'nev' => $row['nev'],
                'rovid_nev' => $row['rovid_nev'],
            );
        }

        return $result;
    }

    public function saveNew($osztaly) {
        $stmt = $this->pdo->prepare(
            'INSERT INTO tb_osztaly(nev, rovid_nev, evfolyam, betujel) VALUES(?, ?, ?, ?);');
        $stmt->execute(array($osztaly['nev'], $osztaly['rovid_nev'], $osztaly['evfolyam'], $osztaly['betujel']));
        
        return $this->pdo->lastInsertId();
    }

    public function update($osztaly)
    {
        $stmt = $this->pdo->prepare(
            'UPDATE tb_osztaly ' .
            'SET nev = ?, rovid_nev = ?, evfolyam = ?, betujel = ? ' .
            'WHERE id = ?;');
        $stmt->execute(array($osztaly['nev'], $osztaly['rovid_nev'], $osztaly['evfolyam'], $osztaly['betujel'], $osztaly['id']));
        
        return $osztaly['id'];
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM tb_osztaly WHERE id = ?;');
        $stmt->execute(array($id));
        return $stmt->rowCount();
    }

}