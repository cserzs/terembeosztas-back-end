<?php
namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Config\Services;

class Admin extends BaseController {

    use ResponseTrait;

    public function index() {
        return view("admin_layout", array(
            '_page' => view("admin/index")
        ));
    }

    public function editassignment() {
        return view("admin_layout", array(
            '_page' => view("admin/editassignment")
        ));
    }

    public function editCatalog() {
        return view("admin_catalog_layout");
    }

    public function pdfview() {

        $roomModel = new \App\Libraries\Rooms();
        $termek = $roomModel->getAllForPdf("ORDER BY rovid_nev");

        $classModel = new \App\Libraries\Schoolclass();
        $osztalyok = $classModel->getAll("ORDER BY evfolyam, rovid_nev", false);

        $ra = new \App\Libraries\RoomAssignment();
        $beosztas = $ra->getForPdf();

        $napok = array("Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek");
        $idopontok = $ra->getTimesArray();

        $pdfExport = new \App\Libraries\RoombindingExportManager();
        $pdf = $pdfExport->toPdf($osztalyok, $termek, $beosztas, $napok, $idopontok);

        return Services::response()
            ->setStatusCode(200)
            ->setContentType('application/pdf')
            ->setBody($pdf->Output('terembeosztas.pdf', 'I'));
    }

    public function emptyroomspdf() {

        $roomModel = new \App\Libraries\Rooms();
        $termek = $roomModel->getAllForPdf("ORDER BY rovid_nev");

        $ra = new \App\Libraries\RoomAssignment();
        $idopontok = $ra->getTimesArray();
        $urestermek = $ra->getEmptyRooms();

        $napok = array("Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek");
        
        $pdfExport = new \App\Libraries\EmptyroomsExportManager();
        $pdf = $pdfExport->toPdf($termek, $urestermek, $napok, $idopontok);
        
        return Services::response()
            ->setStatusCode(200)
            ->setContentType('application/pdf')
            ->setBody($pdf->Output('urestermek.pdf', 'I'));

    }

    public function logout() {
        $loginManager = new \App\Libraries\LoginManager();
        $loginManager->logout();
        return redirect()->to('/');
    }
}