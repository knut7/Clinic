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
 * Date: 29/11/17
 * Time: 10:01
 */

namespace Module\Clinic\Controllers;


use Ballybran\Core\{
    Controller\AbstractController, REST\Encodes, REST\RestUtilities
};
use Ballybran\Helpers\{
    Copyright\Copyright, Http\Hook, Security\Session, Security\Validate, Utility\Hash
};
use Module\Clinic\{
    Controllers\CTrait\Updates, Lib\PrintListFuncionario, Lib\Prontuario, Lib\PrintListPacient, Lib\PrintListOfAllPacient, Lib\PrintListPacientPayment, Lib\PrintConsult
};
use Module\Entity\Pessoa;
use Module\Upload\ImageUpload;


class Account extends AbstractController
{
    use Updates;

    public $width = 2000;
    public $height = 2000;
    public $quality = 10;
    public $option = "perfil";
    private $form;

    /**
     * Account constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->form = new Validate();
        $this->form->setMethod('POST');


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
            $this->view->especialidades = $this->model->getEspecialidades();

            $this->view->user = $this->model->getUser(Session::get("ID"))[0];

            if ($this->model->getImageUser(Session::get("ID"))) {
                $this->view->UserPhoto = $this->model->getImageUser(Session::get("ID"))[0];
            }

            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $this->view->userAdmin = $this->model->getAllUserAdmin();
                $this->view->allPaciente = $this->model->getAllPaciente();
                $this->view->render($this, 'cpanel');
            }
            if (Session::get('role') == 'funcionario') {
                $this->view->paciente = $this->model->getPacientForConsulta(Session::get('ID'));

                $this->view->atendidos  = $this->model->getPacientForAtendimento(Session::get("ID"));
                $this->view->render($this, 'cpanelFuncionario');

            }
            if (Session::get('role') == 'laboratorio') {
                $this->view->render($this, 'cpanelLaboratorio');

            }
            if (Session::get('role') == 'paciente') {

                $this->view->paciente = $this->model->getPacienteById(Session::get('ID'));
                $this->view->convenio = $this->model->getConvenio();

//                $this->view->id = $this->model->getPacienteByPaciente(Session::get('ID'))[0];
                // $this->view->prontuario = $this->model->getProntuario(Session::get('ID'))[0];

                $this->view->render($this, 'cpanelPaciente');

            }
            if (Session::get('role') == 'secretaria') {

                $this->view->consultas = $this->model->getConsultas();
                $this->view->typeReceita = $this->model->getTypeReceita();
                $this->view->passienteSecretaria = $this->model->getAllPaciente();
                $this->view->moviento = $this->model->getContaMovimento();
                $this->view->formaPagamento = $this->model->getTypePagamento();
                $this->view->pacieteMedico = $this->model->getPacienteSemMedico();

                $this->view->render($this, 'cpanelSecretaria');

            }

        } else {

            Hook::Header('');
        }
    }

    public function printConsulta()
    {
        $pdf = new PrintConsult();
        $pdf->getList($this->model->getConsultas());
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
    }

    public function printProntuario($id)
    {

        $pdf = new  Prontuario();
        $pdf->getId($this->model->getAllInfoForPrint($id));
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->Output();
        $this->model->logPaciente( Session::get("U_NAME"), ":: Imprimiu um Prontuario.");

    }

    public function printListPaciente()
    {

        $pdf = new  PrintListPacient();
        $pdf->getList($this->model->getPacientForAtendimento(Session::get("ID")));
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
        $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lista de Pacientes consultados ");


    }

    public function printListPacienteForConsult()
    {

        $pdf = new  PrintListPacient();
        $pdf->getList($this->model->getPacientForConsulta(Session::get("ID")));
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
        $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lista de Pacientes sem Consulta Agendadas.");

    }

    public function printAllPacient()
    {
        $pdf = new  PrintListOfAllPacient();
        $pdf->getList($this->model->getPacienteSemMedico());
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
        $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lsta de Pacientes sem Consulta Agendadas.");


    }

    public function printAllPacientPayment()
    {
        $pdf = new  PrintListPacientPayment();
        $pdf->getList($this->model->getAllPaciente());
        $pdf->AliasNbPages();
        $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC" ));
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
        $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lsta de Pacientes com a Consulta paga. ");


    }

    public function printlistFuncionario()
    {
        if(Session::exist() ) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $pdf = new  PrintListFuncionario();
                $pdf->getList($this->model->getAllUserAdmin());
                $pdf->AliasNbPages();
                $pdf->myFooter(Copyright::copyright(2017, "KUNT CLINIC"));
                $pdf->AddPage();
                $pdf->body();
                $pdf->Output();

                $this->model->logAccess( Session::get("U_NAME"), ":: Imprimiu uma lsta de Funcionarios.");
            } else {
                Hook::Header("account/cpanel");
            }
        }else {
            Hook::Header("");
        }
    }

    public function getInfoPaciente($id) {
        return ($this->model->getAllInfoForPrint($id));
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
            $pess->setCountryId($_POST['country']);
            $pess->setDataNascimento($_POST['dataNascimento']);

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
            $data['dataNascimento'] = $pess->getDataNascimento();
            $data['country'] = $pess->getCountryId();

            $this->model->updates($data, $pess->getId());
            $this->model->logAccess( Session::get("U_NAME"), ":: Atualizou seus dados");

            Hook::Header('account/cpanel');
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
            Hook::Header('account/cpanel');
        }
        $this->view->render($this, 'profile');
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
            $this->model->logAccess( Session::get("U_NAME"), ":: Atualizou seus previlegios para " . $_POST['role']);
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
        $this->view->password = $this->model->getUser(Session::get("ID"))[0];
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
        if ($_POST['id'] && !empty($_POST['password']) && !empty($_POST['password2'])) {
            $new = Hash::Create(ALGO, $_POST['password2'], HASH_KEY);
            $old = $this->model->getUser(Session::get("ID"))[0]['password'];

            if ($new == $old) {
                $data['password'] = Hash::Create(ALGO, $_POST['password'], HASH_KEY);
                $this->model->logAccess( Session::get("U_NAME"), ":: Atualizou sua senha");
                return $this->model->managePassword($data, $_POST['id']);
            } else {
                echo "NADA FOI ATUALIZADO  porque a password antiga nao existe";
            }
        } else {
            Hook::Header('Account/cpanel');

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


            $this->form->post('hora')->val('maxlength', 58)
                ->post('diaSeman')->val('maxlength', 85)
                ->post('id')->val('maxlength', 85)
                ->submit();
            $data = $this->form->getPostData();
            $this->model->updateHorario($data, $_POST['id']);
        }
        Hook::Header('Account/horarios');

    }

    public function insertHorario()
    {
        $this->form->post('hora')->val('maxlength', 58)
            ->post('diaSeman')->val('maxlength', 85)
            ->post('usuarios_id')->val('maxlength', 85)
            ->submit();

        $data = $this->form->getPostData();
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

    public function inserConsulta()
    {

        if (Session::get('role') == 'paciente' || Session::get('role') == 'secretaria') {
            $this->form->post('info')->val('maxlength', 1223)
                ->post('ante')->val('maxlength', 1223)
                ->post('create_dt')->val('maxlength', 1223)
                ->post('Convenio_id')->val('maxlength', 1223)
                ->post('Especialidade_id')->val('maxlength', 1223)
            ->post('usuarios_id')->val('maxlength', 1223)->submit();

            $this->model->inserConsulta($this->form->getPostData());
            $this->model->logPaciente( Session::get("U_NAME"), ":: Fez uma Consulta");

            Hook::Header('account/cpanel');

        }
    }

    public function event()
    {
     echo json_encode($this->model->getPacientForConsulta(Session::get('ID')));
    }

    public function updatEvent()
    {
        if(! empty($_POST['id'])) {

            $data['start'] = $_POST['start'];
            $data['end'] = $_POST['end'];
            $this->model->updatEvent($data, $_POST['id']);
        }
        
    }
    public function updatEvent2()
    {
        if(! empty($_POST['id'])) {

            $data['title'] = $_POST['title'];
            $this->model->updatEvent2($data, $_POST['id']);
        }

    }

    public function deleteEvent()
    {
        if(! empty($_POST['id'])) {

            $this->model->deleteEvent($_POST['id']);
        }
    }




}
