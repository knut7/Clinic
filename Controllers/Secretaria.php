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
 * Date: 12/11/17
 * Time: 14:38
 */

namespace Module\Clinic\Controllers;


use Ballybran\Core\Controller\AbstractController;
use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\Security\Session;
use Ballybran\Helpers\Security\Validate;
use Module\Entity\Pessoa;

class Secretaria extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
        $this->form = new Validate();
        $this->form->setMethod('POST');
    }

    public function index()
    {

    }
    public function joinPacienteAndFunc()
    {
        $this->form->post('Funcionarios_id')->val('maxlength', 1223)
            ->post('Paciente_id')->val('maxlength', 12)
            ->post('horas')->val('maxlength', 12)->submit();

        $this->model->joinPacienteAndFunc($this->form->getPostData());
        Hook::Header('secretaria/customers');
    }


    public function consultas()
    {
        if(Session::exist()) {
            if(Session::get('role') == "secretaria") {
                $this->view->consultas = $this->model->getConsultas();
                $this->view->func = $this->model->getFuncionarios();
                $this->view->render($this, 'consultas');
            } else {
                Hook::Header('account/cpanel');
            }
        } else {
            Hook::Header('');

        }

    }

    public function customers() {
        if (Session::exist()) {
           if (Session::get('role') == 'secretaria') {
                $this->view->title = "Gestor da Secretaria";
                $this->view->user = $this->model->getAllPaciente();
                $this->view->render($this, 'customers-paciente');
            }
        }else {
            Hook::Header('');

        }

    }

    public function add() {

        if (Session::exist()) {
            if (Session::get('role') == 'secretaria') {
                $this->view->title = "Gestor da Secretaria | Add Paciente";
                $this->view->title = "Customers";
                $this->view->render($this, 'addPaciente');
            }
        }

    }

    /**
     * @param $data
     */
    public function edit($id, $username = null)
    {

        if (Session::exist()) {
            if (Session::get('role') == 'secretaria') {
                $this->view->title = "Gestor da Secretaria | ditar Paciente";
                $this->view->UserData = $this->model->getUser($id)[0];
                $this->view->users = $this->model->getusers($id);
                $this->view->render($this, 'editPaciente');
            }
        }
    }

    /**
     * @param $data
     */
    public function updates() {
        $pess = new Pessoa();
        $pess->setId($_POST['id']);
        if ($pess->getId()) {


            $pess->setFirstname($_POST['firstname']);
            $pess->setLastname(($_POST['lastname']));
            $pess->setUsername($_POST['username']);
            $pess->setEmail($_POST['email']);
            $pess->setTelephone($_POST["telephone"]);
            $pess->setCompany($_POST["company"]);
            $pess->setAddress1($_POST["address_1"]);
            $pess->setAddress2($_POST["address_2"]);
            $pess->setPostcode($_POST["postcode"]);
            $pess->setZoneId($_POST['zone_id']);
            $pess->setCity($_POST['city']);
            $pess->setCountryId($_POST['country_id']);
            $pess->setRole($_POST['role']);

            $data["firstname"] = $pess->getFirstname();
            $data["lastname"] = $pess->getLastname();
            $data['username'] = $pess->getUsername();
            $data['email'] = $pess->getEmail();
            $data["telephone"] = $pess->getTelephone();
            $data["company"] = $pess->getCompany();
            $data["address_1"] = $pess->getAddress1();
            $data["address_2"] = $pess->getAddress2();
            $data["postcode"] = $pess->getPostcode();
            $data['zone_id'] = $pess->getZoneId();
            $data['city'] = $pess->getCity();
            $data['country_id'] = $pess->getCountryId();
            $data['role'] = $pess->getRole();

            $this->model->updates($data, $pess->getId());
            Hook::Header('Dashboard/customers');
        } else {
            echo "NADA FOI ATUALIZADO";
        }
    }

    public function deletes($id) {
        if (Session::exist()) {
            if (Session::get('role') == 'secretaria') {
                $this->model->deletes($id);
                Hook::Header('secretaria/customers');
            } else {
                Hook::Header('user/signUp');
            }
        }
    }


}