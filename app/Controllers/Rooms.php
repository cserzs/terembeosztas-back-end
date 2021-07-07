<?php
namespace App\Controllers;

//use CodeIgniter\API\ResponseTrait;
//use Config\Services;

class Rooms extends BaseController {
    public function index() {
        $roomModel = new \App\Libraries\Rooms();
        $termek = $roomModel->getAll('ORDER BY rovid_nev');

        return view("admin_layout", array(
            '_page' => view("rooms/index", array(
                'termek' => $termek
            ))
        ));
    }

    public function create() {

        $roomModel = new \App\Libraries\Rooms();

        $room = array();
        $errors = array();

        if ($this->request->getMethod() == "post") {
            $room = $roomModel->populateFromArray($this->request->getRawInput());

            $validation = \Config\Services::validation();
            $validation->setRules($roomModel->getValidationRules());
            if ($validation->run($room)) {
                $roomModel->saveNew($room);
                $this->session->setFlashdata('system_message', 'Új terem sikeresen elmentve!');
                return redirect()->to(site_url('rooms/index'));
            }
            else {
                $errors = $validation->getErrors();
            }
        }
        else {
            $room = $roomModel->getNew();
        }

        return view("admin_layout", array(
            '_page' => view("rooms/create", array(
                'terem' => $room,
                'errors' => $errors
            ))
        ));
    }

    public function edit($id = -1) {
        $id = (int)$id;

        $roomModel = new \App\Libraries\Rooms();

        $room = array();
        $errors = array();

        if ($this->request->getMethod() == "post") {
            $room = $roomModel->populateFromArray($this->request->getRawInput());

            $validation = \Config\Services::validation();
            $validation->setRules($roomModel->getValidationRules());
            if ($validation->run($room))
            {
                $roomModel->update($room);
                $this->session->setFlashdata('system_message', $room['rovid_nev'] . ' terem frissítve!');
                return redirect()->to(site_url('rooms/index'));
            }
            else {
                $errors = $validation->getErrors();
            }
        }
        else {
            $room = $roomModel->get($id);
            if ($room == null) {
                $this->session->setFlashdata('system_message', 'Nincs ilyen terem, #' . $id);
                return redirect()->to(site_url('rooms/index'));
            }
        }

        return view("admin_layout", array(
            '_page' => view("rooms/edit", array(
                'terem' => $room,
                'errors' => $errors
            ))
        ));
    }

    public function delete($id) {
        $id = (int)$id;

        $roomModel = new \App\Libraries\Rooms();
        $room = $roomModel->get($id);

        if ($room == null) {
            $this->session->setFlashdata('system_message', 'Nincs ilyen terem, #' . $id);
            return redirect()->to(site_url('rooms/index'));
        }

        $ra = new \App\Libraries\RoomAssignment();
        if ($ra->getRoomOccurs($id) > 0) {
            $this->session->setFlashdata('system_message', 'Nem törölhető, amíg ki van osztva!');
            return redirect()->to(site_url('rooms/index'));
        }

        $roomModel->delete($id);

        $this->session->setFlashdata('system_message', '' . $room['rovid_nev'] . ' (#' . $room['id'] . ') törölve!');
        return redirect()->to(site_url('rooms/index'));
    }
}