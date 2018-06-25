<?php

/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 30/01/18
 * Time: 06:27
 */

namespace Module\Clinic\Controllers;

use Ballybran\Core\Controller\AbstractController;
use Ballybran\Helpers\Security\Session;

class Events extends AbstractController {

    public function insertEvent() {
        if (!empty($_POST['title'])) {

            $data['start'] = $_POST['start'];
            $data['end'] = $_POST['end'];
            $data['title'] = $_POST['title'];
            $this->model->insertEvent($data);
        }
    }

    public function updatEventSecretaria() {
        if (!empty($_POST['id'])) {

            $data['start'] = $_POST['start'];
            $data['end'] = $_POST['end'];
            $this->model->updatEventSecretaria($data, $_POST['id']);
        }
    }

    public function updatEvent() {
        if (!empty($_POST['id'])) {

            $data['start'] = $_POST['start'];
            $data['end'] = $_POST['end'];
            $this->model->updatEvent($data, $_POST['id']);
        }
    }

    public function updatEvent2() {
        if (!empty($_POST['id'])) {

            $data['title'] = $_POST['title'];
            $this->model->updatEvent2($data, $_POST['id']);
        }
    }

    public function deleteEvent() {
        if (!empty($_POST['Paciente_id'])) {

            $this->model->deleteEvent($_POST['Paciente_id']);
            $this->model->deleteEvent($_POST['Paciente_id']);
            $data['Situacao_id'] = 1;
            $this->model->updateSituacao($data, $_POST['Paciente_id']);
        }
    }

    public function getEvents() {
        echo json_encode($this->model->getEvents());
    }

}
