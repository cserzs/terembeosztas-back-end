<?php
namespace App\Libraries;

class RoomAssignment {

    private $idopontPerNap = 8;

    private $pdo;

    function __construct()
    {
        $this->pdo = \App\Libraries\PDO::get();
    }
    
    public function getTimesPerDay() { return $this->idopontPerNap; }

    /**
     * A lehetseges idopontok egy tombben: array(0, 1, 2, ...)
     * @return array
     */
    public function getTimesArray()
    {
        $idopontok = array();
        for($i = 0; $i <= $this->idopontPerNap; $i++) $idopontok[] = $i;
        return $idopontok;
    }

    //  egy osztaly orarendje
    public function getClassAssignmentFor($osztalyid) {
        $stmt = $this->pdo->prepare(
            'SELECT tb_beosztas.nap AS nap, tb_beosztas.idopont AS idopont, ' .
            'tb_beosztas.pozicio AS pozicio, tb_terem.rovid_nev AS rovid_nev ' .
            'FROM tb_beosztas ' .
            'LEFT JOIN tb_terem ON tb_beosztas.terem_id = tb_terem.id ' . 
            'WHERE tb_beosztas.osztaly_id = ? ' .
            'ORDER BY nap, idopont, pozicio;'
        );

        $stmt->bindValue(1, $osztalyid, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //  egy osztaly beosztasat konvertalja html-hez
    public function convertClassAssignmentForView($data) {
        $result = array();
        for($nap = 0; $nap < 5; $nap++) {
            $result[$nap] = array();
            for($idopont = 0; $idopont < 9; $idopont++) {
                $result[$nap][$idopont] = array();
            }
        }
        foreach($data as $elem) {
            $result[$elem['nap']][$elem['idopont']][$elem['pozicio']] = $elem['rovid_nev'];
        }

        return $result;
    }

    /*  format:
        {
            class_id: id,
            roomcatalog: [ [roomid, roomid], [] ... ]
        }
    */
    public function getCatalogOfDay($day) {
        $stmt = $this->pdo->prepare(
            'SELECT osztaly_id, nap, idopont, pozicio, terem_id ' .
            'FROM tb_beosztas ' .
            'WHERE nap = ? ' .
            'ORDER BY osztaly_id, idopont, pozicio;'
        );
        $stmt->bindValue(1, $day, \PDO::PARAM_INT);
        $stmt->execute();
       
        $classModel = new \App\Libraries\Schoolclass();
        $osztalyok = $classModel->getAll('ORDER BY evfolyam, rovid_nev');
        
        $beosztas = array();
        foreach($osztalyok as $v)
        {
            $id = $v['id'];
            $beosztas[$id] = array();
            for($idopont = 0; $idopont <= $this->getTimesPerDay(); $idopont++) $beosztas[$id][$idopont] = array();
        }
        
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach($rows as $row)
        {
            $beosztas[ $row['osztaly_id'] ][ $row['idopont'] ][] = (int)$row['terem_id'];
        }

        $catalog = array();
        foreach($beosztas as $key => $value) {
            $catalog[] = array(
                "class_id" => $key,
                "roomcatalog" => $value
            );
        }
        
        return $catalog;
    }

    public function getAssignmentOfDay($day, $raw = true) {
        $stmt = $this->pdo->prepare(
            'SELECT osztaly_id, nap, idopont, pozicio, terem_id ' .
            'FROM tb_beosztas ' .
            'WHERE nap = ? ' .
            'ORDER BY osztaly_id, idopont, pozicio;'
        );
        $stmt->bindValue(1, $day, \PDO::PARAM_INT);
        $stmt->execute();
        
        if ($raw)
        {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        $temp = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $classModel = new \App\Libraries\Schoolclass();
        $osztalyok = $classModel->getAll('ORDER BY evfolyam, rovid_nev');
        
        $beosztas = array();
        foreach($osztalyok as $v)
        {
            $id = $v['id'];
            $beosztas[$id] = array();
            for($idopont = 0; $idopont <= $this->getTimesPerDay(); $idopont++) $beosztas[$id][$idopont] = array();
        }
        
        foreach($temp as $v)
        {
            $beosztas[$v['osztaly_id']][$v['idopont']][] = $v['terem_id'];
        }
        
        return $beosztas;

    }

    public function getDuplicateRooms($day)
    {
        $stmt = $this->pdo->prepare(
            'SELECT idopont, terem_id, count(terem_id) AS num ' .
            'FROM tb_beosztas ' .
            'WHERE nap = ? AND terem_id <> 0 ' .
            'GROUP BY nap, idopont, terem_id HAVING num > 1'
        );
        $stmt->bindValue(1, $day, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result = array();
        foreach($rows as $row) {
            $result[] = array(
                'idopont' => (int)$row['idopont'],
                'terem_id' => (int)$row['terem_id'],
                'num' => (int)$row['num']
            );
        }
        return $result;
    } 
    
    public function saveNewAssignment($osztalyid, $nap, $idopont, $pozicio, $teremid) {
		$stmt = $this->pdo->prepare(
            'INSERT INTO tb_beosztas(osztaly_id, nap, idopont, pozicio, terem_id) ' .
            'VALUES(?, ?, ?, ?, ?);'
        );
		$stmt->execute(array($osztalyid, $nap, $idopont, $pozicio, $teremid));
		$rows = $stmt->rowCount();
    }

    public function updateAssignment($osztalyid, $nap, $idopont, $pozicio, $teremid) {
		$stmt = $this->pdo->prepare(
            'UPDATE tb_beosztas ' .
            'SET terem_id = ? ' .
            'WHERE osztaly_id = ? AND nap = ? AND idopont = ? AND pozicio = ?;'
        );
		$stmt->execute(array($teremid, $osztalyid, $nap, $idopont, $pozicio));
		$rows = $stmt->rowCount();
    }

    public function deleteAssignment($osztalyid, $nap, $idopont, $pozicio, $teremid) {
		$stmt = $this->pdo->prepare(
            'DELETE FROM tb_beosztas ' .
            'WHERE osztaly_id = ? AND nap = ? AND idopont = ? AND pozicio = ? AND terem_id = ?;'
        );
		$stmt->execute(array($osztalyid, $nap, $idopont, $pozicio, $teremid));
		$rows = $stmt->rowCount();

    }

    public function deleteOneDayAssignment($nap) {
        $stmt = $this->pdo->prepare('DELETE FROM tb_beosztas WHERE nap = ?;');
		$stmt->execute(array($nap));
		$rows = $stmt->rowCount();

    }

    public function getForPdf() {
        $classModel = new \App\Libraries\Schoolclass();
        $osztalyok = $classModel->getAll('ORDER BY evfolyam, rovid_nev');
        
        $beosztas = array();
        foreach($osztalyok as $osztaly) {
            $id = $osztaly['id'];
            $beosztas[$id] = array();
            for($nap = 0; $nap < 5; $nap++)
            {
                $beosztas[$id][$nap] = array();
                for($idopont = 0; $idopont <= $this->getTimesPerDay(); $idopont++)
                    $beosztas[$id][$nap][$idopont] = array();
            }
        }

        $stmt = $this->pdo->query(
            'SELECT osztaly_id, nap, idopont, pozicio, terem_id ' .
            'FROM tb_beosztas ' .
            'ORDER BY osztaly_id, nap, idopont, pozicio;'
        );
        $temp = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach($temp as $v)
        {
            $beosztas[$v['osztaly_id']][ $v['nap'] ][ $v['idopont'] ][] = $v['terem_id'];
        }
        
        return $beosztas;        
    }

    /**
     * Ures termek a heten.
     * @return array array[nap][idopont] => array(terem_id, ...)
     */
    public function getEmptyRooms()
    {
        $roomModel = new \App\Libraries\Rooms();
        $termek = $roomModel->getAll('ORDER BY rovid_nev');

        //  ne legyenek benne a listaban
        $exceptions = array('133', '417');

        $uresek = array();
        for($i = 0; $i < 5; $i++)
        {
            $uresek[$i] = array();
            for($idopont = 0; $idopont <= $this->idopontPerNap; $idopont++)
            {
                $uresek[$i][$idopont] = array();
                foreach($termek as $terem)
                {
                    if ($idopont > 0 && $idopont < 7 && in_array($terem['rovid_nev'], $exceptions)) continue;
                    $uresek[$i][$idopont][] = $terem['id'];
                }
            }
        }
     
        $stmt = $this->pdo->query(
            'SELECT osztaly_id, nap, idopont, pozicio, terem_id ' .
            'FROM tb_beosztas ' .
            'ORDER BY nap, idopont, pozicio;'
        );
        $temp = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach($temp as $row)
        {
            $nap = $row['nap'];
            $idopont = $row['idopont'];
            $terem = $row['terem_id'];
            
            if (($key = array_search($terem, $uresek[$nap][$idopont])) !== false)
            {
                unset($uresek[$nap][$idopont][$key]);
            }        
        }
        return $uresek;
    }

    public function getRoomOccurs($roomid) {
        $stmt = $this->pdo->prepare('SELECT count(terem_id) AS num FROM tb_beosztas WHERE terem_id = ?;');
        $stmt->bindValue(1, $roomid, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getSchoolclassOccurs($osztalyid) {
        $stmt = $this->pdo->prepare(
            'SELECT count(osztaly_id) AS num FROM tb_beosztas WHERE osztaly_id = ?;');
        $stmt->bindValue(1, $osztalyid, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    
}