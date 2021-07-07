<?php
namespace App\Controllers;

class Schoolclass extends BaseController {
    public function index() {
        $classModel = new \App\Libraries\Schoolclass();
        $osztalyok = $classModel->getAll('ORDER BY rovid_nev');

        return view("admin_layout", array(
            '_page' => view("schoolclass/index", array(
                'osztalyok' => $osztalyok
            ))
        ));
    }

    public function create() {

        $classModel = new \App\Libraries\Schoolclass();

        $osztaly = array();
        $errors = array();

        if ($this->request->getMethod() == "post") {
            $osztaly = $classModel->populateFromArray($this->request->getRawInput());

            $validation = \Config\Services::validation();
            $validation->setRules($classModel->getValidationRules());
            if ($validation->run($osztaly)) {
                $classModel->saveNew($osztaly);
                $this->session->setFlashdata('system_message', 'Új osztály sikeresen elmentve!');
                return redirect()->to(site_url('schoolclass/index'));
            }
            else {
                $errors = $validation->getErrors();
            }
        }
        else {
            $osztaly = $classModel->getNew();
        }

        return view("admin_layout", array(
            '_page' => view("schoolclass/create", array(
                'osztaly' => $osztaly,
                'errors' => $errors
            ))
        ));
    }

    public function edit($id = -1) {
        $id = (int)$id;

        $classModel = new \App\Libraries\Schoolclass();

        $osztaly = array();
        $errors = array();

        if ($this->request->getMethod() == "post") {
            $osztaly = $classModel->populateFromArray($this->request->getRawInput());

            $validation = \Config\Services::validation();
            $validation->setRules($classModel->getValidationRules());
            if ($validation->run($osztaly))
            {
                $classModel->update($osztaly);
                $this->session->setFlashdata('system_message', $osztaly['rovid_nev'] . ' osztály frissítve!');
                return redirect()->to(site_url('schoolclass/index'));
            }
            else {
                $errors = $validation->getErrors();
            }
        }
        else {
            $osztaly = $classModel->get($id);
            if ($osztaly == null) {
                $this->session->setFlashdata('system_message', 'Nincs ilyen osztály, #' . $id);
                return redirect()->to(site_url('schoolclass/index'));
            }
        }

        return view("admin_layout", array(
            '_page' => view("schoolclass/edit", array(
                'osztaly' => $osztaly,
                'errors' => $errors
            ))
        ));
    }

    public function delete($id) {
        $id = (int)$id;

        $classModel = new \App\Libraries\Schoolclass();
        $osztaly = $classModel->get($id);

        if ($osztaly == null) {
            $this->session->setFlashdata('system_message', 'Nincs ilyen osztály, #' . $id);
            return redirect()->to(site_url('schoolclass/index'));
        }

        $ra = new \App\Libraries\RoomAssignment();
        if ($ra->getSchoolclassOccurs($id) > 0) {
            $this->session->setFlashdata('system_message', 'Nem törölhető, amíg van terem kiosztva az osztálynak!');
            return redirect()->to(site_url('schoolclass/index'));
        }

        $classModel->delete($id);

        $this->session->setFlashdata('system_message', '' . $osztaly['rovid_nev'] . ' (#' . $osztaly['id'] . ') törölve!');
        return redirect()->to(site_url('schoolclass/index'));
    }
}