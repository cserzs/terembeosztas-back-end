<?php
namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Api extends BaseController
{
	use ResponseTrait;

	public function rooms()
	{
		$roomModel = new \App\Libraries\Rooms();

		$termek = array();
		$termek[] = $roomModel->getEmpty();
		$termek = array_merge($termek, $roomModel->getAllToApi());

		return $this->setResponseFormat('json')->respond($termek);
	}

	public function classes() {
		
		$classModel = new \App\Libraries\Schoolclass();
		$osztalyok = $classModel->getAllToApi();
		
		return $this->setResponseFormat('json')->respond($osztalyok);
	}

	public function assignmentOfDay($day = -1) {
		$day = (int)$day;

		$ra = new \App\Libraries\RoomAssignment();

		return $this->setResponseFormat('json')->respond( $ra->getAssignmentOfDay($day, true) );
	}

	public function catalogOfDay($day = 0) {
		$day = (int)$day;

		$ra = new \App\Libraries\RoomAssignment();

		return $this->setResponseFormat('json')->respond( $ra->getCatalogOfDay($day) );
	}

	public function checkduplicate($day = -1) {
		$day = (int)$day;
		//  day ellenorzese, hogy 0-4 kozott van-e
		$ra = new \App\Libraries\RoomAssignment();
		return $this->setResponseFormat('json')->respond( $ra->getDuplicateRooms($day) );
	}

	public function saveAssignment() {

		$osztalyid = (int)$this->request->getVar('osztalyid');
		$nap = (int)$this->request->getVar('nap');
		$idopont = (int)$this->request->getVar('idopont');
		$pozicio = (int)$this->request->getVar('pozicio');
		$teremid = (int)$this->request->getVar('teremid');

		$ra = new \App\Libraries\RoomAssignment();
		$ra->saveNewAssignment($osztalyid, $nap, $idopont, $pozicio, $teremid);

		return $this->respond('', 201);
	}

	public function updateAssignment($osztalyid, $nap, $idopont, $pozicio, $teremid) {

		$osztalyid = (int)$osztalyid;
		$nap = (int)$nap;
		$idopont = (int)$idopont;
		$pozicio = (int)$pozicio;
		$teremid = (int)$teremid;

		$ra = new \App\Libraries\RoomAssignment();
		$ra->updateAssignment($osztalyid, $nap, $idopont, $pozicio, $teremid);
			
		return $this->respond('', 200);
	}

	public function deleteAssignment($osztalyid, $nap, $idopont, $pozicio, $teremid) {
		$osztalyid = (int)$osztalyid;
		$nap = (int)$nap;
		$idopont = (int)$idopont;
		$pozicio = (int)$pozicio;
		$teremid = (int)$teremid;
	
		$ra = new \App\Libraries\RoomAssignment();
		$ra->deleteAssignment($osztalyid, $nap, $idopont, $pozicio, $teremid);
		
		return $this->respondDeleted('Delete confirmed!');
	}

	public function deleteOneDayAssignment($nap) {
		$nap = (int)$nap;
		if ($nap < 0 || $nap > 4) return $this->fail('', 404);
	
		$ra = new \App\Libraries\RoomAssignment();
		$ra->deleteOneDayAssignment($nap);
		
		return $this->respondDeleted('Delete confirmed!');
	}
}
