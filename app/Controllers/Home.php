<?php
namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$classModel = new \App\Libraries\Schoolclass();
		$osztalyok = $classModel->getAll("ORDER BY evfolyam, rovid_nev");

		return view('base_layout', array(
			'_page' => view('home/index', array(
				'osztalyok' => $osztalyok,
			)),
			//'_styles' => 'alma'
		));
	}

    public function showclass($id = -1)
    {
        $id = (int)$id;

		$classModel = new \App\Libraries\Schoolclass();
		$osztaly = $classModel->get($id);
		if ($osztaly == null) {
			return redirect()->to('/');
		}

		$osztalyok = $classModel->getAll("ORDER BY evfolyam, rovid_nev");

		$ra = new \App\Libraries\RoomAssignment();

		$classAssignment = $ra->getClassAssignmentFor($id);
		$result = $ra->convertClassAssignmentForView($classAssignment);

		return view('base_layout', array(
			'_page' => view('home/view', array(
				'osztalyok' => $osztalyok,
				'osztaly' => $osztaly,
				'beosztas' => $result,
				'napok' => array("Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek")
			))
		));

    }

    public function login()
    {
        $loginError = 0;

		$loginManager = new \App\Libraries\LoginManager();
		if ($loginManager->isLoggedIn()) {
			return redirect()->to(site_url('admin/index'));
		}

		if ($this->request->getMethod() == "post") {
            $username = $this->request->getVar("username");
            $password = $this->request->getVar("password");

            $loginError = 1;
            if ($loginManager->login($username, $password))
            {
                return redirect()->to(site_url('admin/index'));
            }
		}

        return view('base_layout', array(
			'_page' => view("home/login", array(
				'loginError' => $loginError,
			))
        ));
    }

}
