<?php

/**
 * KNUT7 K7F (http://framework.artphoweb.com/)
 * KNUT7 K7F (tm) : Rapid Development Framework (http://framework.artphoweb.com/)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link      http://github.com/zebedeu/artphoweb for the canonical source repository
 * @copyright (c) 2015.  KNUT7  Software Technologies AO Inc. (http://www.artphoweb.com)
 * @license   http://framework.artphoweb.com/license/new-bsd New BSD License
 * @author    Marcio Zebedeu - artphoweb@artphoweb.com
 * @version   1.0.2
 */
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 15/01/17
 * Time: 15:11
 */

namespace Module\Clinic\Controllers;

use Ballybran\Helpers\ {
    Http\Hook
};

use Ballybran\Core\Controller\AbstractController;

use Ballybran\Helpers\ {
    Security\Session, Security\Validate, Security\Val
};

class Paciente extends AbstractController {

    private $form;

    public function __construct() {
        parent::__construct();
        $this->form = new Validate(new Val);
        $this->form->setMethod('POST');
    }

    public function index() {
        if (Session::exist()) {
            if (Session::get('role') == 'paciente') {
                $this->view->title = "Marcara Consula";

                $this->view->info = $this->model->getInfoConsulta(Session::get('ID'));
                $this->view->render($this, 'index');
            } else {
                Hook::Header('account/cpanel');
            }
        } else {
            Hook::Header('');
        }
    }

    public function marcarconsulta($id = null) {
        $this->view->title = "Marcara Consula";

        if (Session::exist()) {
            if (Session::get('role') == 'secretaria') {
                if (!is_null($id)) {
                    $this->view->id = $id;
                    $this->view->render($this, 'marcar-consulta');
                }
            } else {
                Hook::Header('account/cpanel');
            }
        } else {
            Hook::Header('');
        }
    }

    public function inserConsulta() {
        $this->form->post('info')->val('maxlength', 1223)
                ->post('usuarios_id')->val('maxlength', 12)->submit();
        $this->model->inserConsulta($this->form->getPostData());
        if (Session::get('role') == 'paciente') {
            Hook::Header('paciente/consultas');
        }
        if (Session::get('role') == 'secretaria') {
            Hook::Header('Dashboard/customers');
        }
    }

    public function prontuario($id = null) {
        $this->view->title = "Pontuari | Paciente";
        if (!Session::exist()) {
            Hook::Header("");
        }

        if (Session::get('role') == "paciente") {
            if (is_null($id)) {
                Hook::Header(" ");
            }
            $this->view->UserPhoto = $this->model->getImageUser(Session::get("ID"))[0];
            $this->view->paciente = $this->model->getPacienteById(Session::get('ID'));

            $this->view->UserPhoto = $this->model->getImageUser(Session::get("ID"))[0];
            $this->view->paciente = $this->model->getPacienteById(Session::get('ID'));
            $this->view->getExame = $this->model->getExamesByPaciente(Session::get("ID"));
            $this->view->medical = $this->model->getMedicamentosByPaciente(Session::get("ID"));
            $this->view->todos = $this->model->todos($id);

            $this->view->render($this, 'pontuario-paciente');
        } else {
            Hook::Header("");
        }
    }

    public function deletePaciente($id) {

        $this->model->deletePaciente($id);
        Hook::Header("account/cpanel");
    }

}
