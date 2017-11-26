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

namespace Module\Clinica\Controllers;

use Ballybran\Core\Controller\AbstractController;
use Ballybran\Core\REST\Encodes;
use Ballybran\Core\REST\RestUtilities;
use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\Security\Session;
use Ballybran\Helpers\Security\Validate;
use Ballybran\Helpers\Utility\Hash;
use Ballybran\Helpers\vardump\Vardump;
use Module\Entity\Pessoa;
use Module\Upload\ImageUpload;

class Account extends AbstractController
{

    public $width = 2000;
    public $height = 2000;
    public $quality = 10;
    public $option = "perfil";
    /**
     * Account constructor.
     */
    public function __construct()
    {

        parent::__construct();

    }

    /**
     *
     */
    public function index()
    {

        $this->view->title = "Lista de Usuarios";
        $this->view->users = $this->model->getAllUser();
        $this->view->render($this, 'index');
    }

    /**
     *
     */
    public function rest()
    {
        $data = RestUtilities::processRequest();

        switch ($data->getMethod()) {
            case 'get':
                $propriedade = $this->model->getAllUser();
                echo RestUtilities::sendResponse(200, Encodes::encodeJson($propriedade), 'application/json');
                break;
        }
    }

    /**
     *
     */
    public function getRest()
    {
//            $id = $_POST['username'];
//            $pass = $_POST["password"];

        $data = RestUtilities::processRequest();

        switch ($data->getMethod()) {
            case 'put':
                $propriedade = $this->model->getAllUser();
                echo RestUtilities::sendResponse(200, json_decode($propriedade), 'application/json');
                break;
        }

    }


    /**
     *
     */
    public function Cpanel()
    {
        if (Session::exist()) {
            $this->view->title = "Cpanel";

            $this->view->user = $this->model->getUser(Session::get("ID"))[0];
            if ($this->model->getImageUser(Session::get("ID"))) {
                $this->view->UserPhoto = $this->model->getImageUser(Session::get("ID"))[0];
            }

            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $this->view->render($this, 'cpanel');
            }
            if (Session::get('role') == 'funcionario') {
                $this->view->paciente = $this->model->getPacientForConsulta(Session::get('ID'));
                $this->view->render($this, 'cpanelFuncionario');

            }
            if (Session::get('role') == 'laboratorio') {
//                $this->view->paciente = $this->model->getPacientForConsulta(Session::get('ID'));
                $this->view->render($this, 'cpanelLaboratorio');

            }
            if (Session::get('role') == 'paciente') {


                    $this->view->paciente = $this->model->getPacienteById(Session::get('ID'));
                    $this->view->render($this, 'cpanelPaciente');


            }
            if (Session::get('role') == 'secretaria') {
                $this->view->render($this, 'cpanelSecretaria');

            }

        } else {

            Hook::Header('account/profile');
        }
    }

    /**
     *
     */
    public function imageUser()
    {
        if (Session::exist()) {

            if (!empty($this->model->getUser(Session::get("ID")))) {
                $this->view->UserData = $this->model->getUser(Session::get("ID"))[0];
            }
            $this->view->render($this, 'insertImageUser');
        } else {
            Hook::Header('user/login');
        }
    }

    /**
     *
     */
    public function insertImageUser()
    {

        $this->imagem->file('perfil');



        $image = new ImageUpload();
        $image->setName($this->imagem->name);
        $image->setType($this->imagem->type);
        $image->setPath($this->imagem->path);
        $image->setSize($this->imagem->size);
        $image->setId(Session::get('ID'));

        $data['type'] = $image->getType();
        $data['size'] = $image->getSize();
        $data['path'] = $image->getPath();
        $data['name'] = $image->getName();
        $data['usuarios_id'] = $image->getId();
        $this->model->insertImageUser($data);

        Hook::Header('account/cpanel');
    }

    /**
     *
     */
    public function edit()
    {
        if (Session::exist()) {
            $this->view->UserData = $this->model->getUser(Session::get("ID"))[0];
            if ($this->model->getImageUser(Session::get("ID"))) {
                $this->view->UserPhoto = $this->model->getImageUser(Session::get("ID"))[0];
            }
            $this->view->render($this, 'edit');
        } else {
            $this->view->render($this, 'index');
            header('location:' . URL);
        }
    }

    /**
     * @param $id
     */
    public function deletes($id)
    {
        if (Session::exist()) {

            $this->model->deletes($id);
            Session::Destroy();
            Hook::Header('user/signUp');
        } else {
            Hook::Header('user/signUp');

        }
    }

    /**
     *
     */
    public function updates()
    {
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

            $this->model->updates($data, $pess->getId());
            Hook::Header('account/edit');
        } else {
            echo "NADA FOI ATUALIZADO";
        }
    }

    /**
     *
     */
    public function deleteImage()
    {
        if (Session::exist()) {
            $file = $this->model->getImageUser(Session::get('ID'));
            if (is_array($file)) {
                foreach ($file as $key => $value) {

                    if (file_exists($value['path'])) {
                        unlink($value['path']);
                    }
                }
            }

            $this->model->deleteImagePerfil(Session::get('ID'));

            Hook::Header('account/cpanel');

        } else {
            Hook::Header('');
        }
    }

    /**
     * @param null $id
     */
    public function profile($id = null)
    {
        $this->view->title = "LISTA USERS";
        if ($id != null) {

            $this->view->UserPhoto = $this->model->getImageUser($id)[0];
            $this->view->resmural = $this->model->getResMural();
            $this->view->getMuralById = $this->model->getMuralById($id);
            $this->view->mural = $this->model->getMural($id);
            $this->view->users = $this->model->getUser($id);
        } else {
            Hook::Header('Account');
        }
        $this->view->render($this, 'profile');
    }

    /**
     *
     */
    public function insertMural()
    {

        $valid = new Validate();
        $valid->getMethod('POST');
        $valid->post('usuarios_id')->post('message');

        $this->model->insertMural($valid->getPostDate());

    }

    /**
     *
     */
    public function insertRespo()
    {

        $valid = new Validate();
        $valid->getMethod('POST');
        $valid->post('message')->post('mural_id');
        $this->model->insertRespo($valid->getPostDate());

    }

    /**
     * update the role of users
     *
     * @return data, id
     * @author
     * */
    public function manageUser()
    {
        if ($_POST['id']) {
            $data['role'] = $_POST['role'];
            return $this->model->manageUser($data, $_POST['id']);
        } else {
            echo "NADA FOI ATUALIZADO";
        }
    }

    /**
     * update the role of users
     *
     * @return data, id
     * @author
     * */
    public function password()
    {
        $this->view->UserData = $this->model->getUser(Session::get("ID"))[0];
        $this->view->render($this, 'password');
    }

    /**
     * update the role of users
     *
     * @return data, id
     * @author
     * */
    public function managePassword()
    {
        if ($_POST['id'] && !empty($_POST['password'])) {
            $data['password'] = Hash::Create(ALGO, $_POST['password'], HASH_KEY);
            return $this->model->managePassword($data, $_POST['id']);
        } else {
            echo "NADA FOI ATUALIZADO  porque a password antiga nao existe";
        }
    }

    /**
     *   Times and Date
     */
    public function horarios()
    {
        $this->view->title = "Horarios";
        if(Session::get('role') == 'funcionario') {
            $this->view->horario = $this->model->getHorarioByFunc(Session::get('ID'));
            $this->view->render($this, 'horarios');
        }

        if(Session::get('role') == 'secretaria') {
            $this->view->horario = $this->model->getHorarioByFunc(Session::get('ID'));
            $this->view->render($this, 'horariosSecretaria');
        }
    }

    public function updateHorario()
    {
        if (!empty($_POST['id'])) {

            $form = new Validate();
            $form->setMethod('POST');
            $form->post('hora')->val('maxlength', 58)
                ->post('diaSeman')->val('maxlength', 85)
                ->post('id')->val('maxlength', 85)
                ->submit();
            $data = $form->getPostData();
            $this->model->updateHorario($data, $_POST['id']);
        }
        Hook::Header('Account/horarios');

    }

    public function insertHorario()
    {
        $form = new Validate();
        $form->setMethod('POST');
        $form->post('hora')->val('maxlength', 58)
            ->post('diaSeman')->val('maxlength', 85)
            ->post('usuarios_id')->val('maxlength', 85)
            ->submit();

        $data = $form->getPostData();
        $this->model->insertHorario($data);
        Hook::Header('Account/horarios');

    }

    public function deteleHoraroById($id)
    {
        if (!empty($id)) {
            $this->model->deteleHoraroById($id);
        }
        Hook::Header('Account/horarios');

    }


//    public function updateAddHorasPaciente()
//    {
//        if (! empty($_POST['Funcionarios_id'])) {
//
//            $form = new Validate();
//            $form->setMethod('POST');
//            $form->post('horas')->val('maxlength', 58)
//                ->submit();
//            $data = $form->getPostData();
//            $this->model->updateAddHorasPaciente($data);
//        }
//        Hook::Header('Account/horarios');
//
//    }
}
