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


namespace Module\Clinic\Controllers;


use Ballybran\Core\Controller\AbstractController;
use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\{
    Security\Session, Security\Validate, Security\Val
};
use Module\Entity\Pessoa;

class Secretaria extends AbstractController
{
    private $form;

    public function __construct()
    {
        parent::__construct();
        $this->form = new Validate( new Val );
        $this->form->setMethod('POST');
    }

    public function index()
    {

    }
    public function joinPacienteAndFunc()
    {
        $this->form->post('Especialidade_id')->val('maxlength', 1223)
            ->post('Paciente_id')->val('maxlength', 12)
            ->post('horas')->val('maxlength', 123)->submit();

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

    public function inserPasciente(){

        if(!empty($_POST['Especialidade_id']) && !empty($_POST['usuarios_id']) && !empty($_POST['create_dt']) && !empty($_POST['info'])) {

            $data['info'] = $_POST['info'];
            $data['create_dt'] = $_POST['create_dt'];
            $data['usuarios_id'] = $_POST['usuarios_id'];
            $data['Especialidade_id'] = $_POST['Especialidade_id'];
            $this->model->inserPasciente($data);
            Hook::Header("account/cpanel");
        } else {
            Hook::Header("account/cpanel");
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
            $pess->setZone($_POST['zone']);
            $pess->setCity($_POST['city']);
            $pess->setCountry($_POST['country']);
            $pess->setRole($_POST['role']);
            $pess->setDataNascimento($_POST['dataNascimento']);
            $pess->setSexo($_POST['sexo']);


            $data["firstname"] = $pess->getFirstname();
            $data["lastname"] = $pess->getLastname();
            $data['username'] = $pess->getUsername();
            $data['email'] = $pess->getEmail();
            $data["telephone"] = $pess->getTelephone();
            $data["company"] = $pess->getCompany();
            $data["address_1"] = $pess->getAddress1();
            $data["address_2"] = $pess->getAddress2();
            $data["postcode"] = $pess->getPostcode();
            $data['zone'] = $pess->getZone();
            $data['city'] = $pess->getCity();
            $data['country'] = $pess->getCountry();
            $data['role'] = $pess->getRole();
            $data['dataNascimento'] = $pess->getDataNascimento();
            $data['sexo'] = $pess->getSexo();


            $this->model->updates($data, $pess->getId());
            Hook::Header('secretaria/customers');
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

    /**
     * @throws \Ballybran\Exception\Exception
     */
    public function insertExameFisico() {

        $this->form->post('altura')->val('maxlength', 1223)
            ->post('Funcionarios_id')->val('maxlength', 2222)
            ->post('Paciente_id')->val('maxlength', 2222)
            ->post('peso')->val('maxlength', 123)
            ->post('freq_cardiaca')->val('maxlength', 123)
            ->post('press_arte_sistolica')->val('maxlength', 123)
            ->post('press_arte_diastolica')->val('maxlength', 123)
            ->post('obs_gerais')->val('maxlength', 123)->submit();


        $this->model->insertExameFisico($this->form->getPostData() );
    
        Hook::Header("account/cpanel");
    }

    /**
     * @throws \Ballybran\Exception\Exception
     */
    public function insertEspecialidade()
    {
        $this->form->post('espValor')->val("maxlength", 100)
            ->post('espNome')->val("maxlength", 100)
            ->post('TipoReceita_id')->val("maxlength", 100)->submit();

        $this->model->insertEspecialidade($this->form->getPostData());
        Hook::header("account/cpanel");

    }

    public function deleteEspecialidade($id)
    {
            $this->model->deleteEspecialidade($id);
            Hook::Header("account/cpanel");
    }

    public function deleteConvenio($id)
    {
            $this->model->deleteConvenio($id);
            Hook::Header("account/cpanel");
    }

    /**
     * @throws \Ballybran\Exception\Exception
     */
    public function createConvenio()
    {
        $this->form->post('convNome')->val('maxlength', 300)
            ->post('responsavel')->val('maxlength', 100)
            ->post('email')->val('maxlength', 100)
            ->post('telephone')->val('digit', 17)->submit();


        $this->model->createConvenio($this->form->getPostData());

        Hook::header("account/cpanel");

    }


}