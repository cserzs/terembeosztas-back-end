<?php
namespace App\Libraries;

class Rooms {
    private $pdo;

    function __construct()
    {
        $this->pdo = \App\Libraries\PDO::get();
    }

    public function getNew()
    {
        return array(
            'id' => -1,
            'nev' => '',
            'rovid_nev' => '',
            'megjegyzes' => '',
        );
    }

    public function getEmpty()
    {
        return array(
            'id' => 0,
            'nev' => '-',
            'rovid_nev' => '-',
            'megjegyzes' => '',
        );
    }

    public function getValidationRules()
    {
        return array(
            'id' => "integer|required",
            'nev' => "required",
            'rovid_nev' => "required"
        );
    }

    public function populateFromArray($data)
    {
        return array(
            'id' => (int)\App\Libraries\Helper::get($data, 'id', -1),
            'nev' => \App\Libraries\Helper::get($data, 'nev'),
            'rovid_nev' => \App\Libraries\Helper::get($data, 'rovid_nev'),
            'megjegyzes' => \App\Libraries\Helper::get($data, 'megjegyzes'),
        );
    }

    public function get($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tb_terem WHERE id = ?;');
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) return null;
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    //  rawArray: true: sima fetch
    //            false: a tomb id-je a terem id-je
    public function getAll($orderBy = '', $rawArray = true)
    {
        $stmt = $this->pdo->query('SELECT * FROM tb_terem ' . $orderBy . ';');
        
        if ($rawArray) return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $temp = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result = array();
        foreach($temp as $v) { $result[ $v['id'] ] = $v; }
        
        return $result;
    }

    public function getAllToApi()
    {
        $stmt = $this->pdo->query('SELECT * FROM tb_terem ORDER BY rovid_nev;');
        
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result = array();
        foreach($rows as $row) {
            $result[] = array(
                "id" => (int)$row['id'],
                'nev' => $row['nev'],
                'rovid_nev' => $row['rovid_nev'],
                'megjegyzes' => empty($row['megjegyzes']) ? "": $row['megjegyzes'],
            );
        }

        return $result;
    }


    public function getAllForPdf($orderBy = '')
    {
        $stmt = $this->pdo->query('SELECT * FROM tb_terem ' . $orderBy . ';');

        $result = array();
        $result[0] = $this->getEmpty();

        $temp = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach($temp as $v) { $result[ $v['id'] ] = $v; }
        
        return $result;
    }

    public function saveNew($room) {
        $stmt = $this->pdo->prepare('INSERT INTO tb_terem(nev, rovid_nev, megjegyzes) VALUES(?, ?, ?);');
        $stmt->execute(array($room['nev'], $room['rovid_nev'], $room['megjegyzes']));
        
        return $this->pdo->lastInsertId();
    }

    public function update($room)
    {
        $stmt = $this->pdo->prepare(
            'UPDATE tb_terem ' .
            'SET nev = ?, rovid_nev = ?, megjegyzes = ? ' .
            'WHERE id = ?;');
        $stmt->execute(array($room['nev'], $room['rovid_nev'], $room['megjegyzes'], $room['id']));
        
        return $room['id'];
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM tb_terem WHERE id = ?;');
        $stmt->execute(array($id));
        return $stmt->rowCount();
    }

}