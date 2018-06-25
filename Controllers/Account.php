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

use Ballybran\Core\ {
    Controller\AbstractController
};

use Ballybran\Helpers\Http\FileSystem;

use Ballybran\Helpers\ {
    Http\Hook, Http\HZip, Images\Resize, Security\Session, Security\Validate, Security\Val, StdLib\HydratorConverter, Utility\Hash
};

use Module\Clinic\Entity\Pessoa;
use Module\Lib\SendMail;
use Module\Upload\ImageUpload;
use PHPMailer\PHPMailer\PHPMailer;
use Module\Clinic\Controllers\CTrait\Prints;

class Account extends AbstractController {

    use Prints;

    private $form;
    private $imagem;

    /**
     * Account constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->form = new Validate(new Val);
        $this->form->setMethod('POST');
    }

    /**
     *
     */
    public function index() {
        $this->view->title = "Lista de Usuarios";
        $this->view->users = $this->model->getAllUser();
        $this->view->render($this, 'index');
    }

    public function cpanel() {
        if (Session::exist()) {

            $this->view->title = "Cpanel";
            $this->view->especialidades = $this->model->getEspecialidades();
            $this->view->user = $this->model->getUser(Session::get("ID"))[0];

            if ($this->model->getImageUser(Session::get("ID"))) {
                $this->view->UserPhoto = $this->model->getImageUser(Session::get("ID"))[0];
            }

            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $this->view->userAdmin = $this->model->getAllMedicos();
                $this->view->allPaciente = $this->model->getAllPaciente();
                $this->view->listaDeFuncionarios = $this->model->getAllFuncionarios();
                $this->view->render($this, 'cpanel');
            }
            if (Session::get('role') == 'medico') {

                $this->view->paciente = $this->model->getPacientForConsulta(Session::get('ID'));
                $this->view->atendidos = $this->model->getPacientForAtendimento(Session::get("ID"));
                $this->view->unidadeInternacao = $this->model->getUnidadeInternacao();
                $this->view->tipoAdmissao = $this->model->getTipoAdmissao();
                $this->view->internados = $this->model->getPacientInternados(Session::get("ID"));
                $this->view->render($this, 'cpanelMedico');
            }
            if (Session::get('role') == 'laboratorio') {
                $this->view->render($this, 'cpanelLaboratorio');
            }
            if (Session::get('role') == 'markting') {
                $this->view->render($this, 'cpanelMarkting');
            }
            if (Session::get('role') == 'paciente') {

                $this->view->newConsulta = $this->model->getConsulta(Session::get('ID'));
                $this->view->paciente = $this->model->getPacienteById(Session::get('ID'));
                $this->view->convenio = $this->model->getConvenio();

//                $this->view->id = $this->model->getPacienteByPaciente(Session::get('ID'))[0];
                // $this->view->prontuario = $this->model->getProntuario(Session::get('ID'))[0];

                $this->view->render($this, 'cpanelPaciente');
            }
            if (Session::get('role') == 'secretaria') {
                $this->view->paciente = $this->model->getPacientForConsulta(Session::get('ID'));
                $this->view->consultas = $this->model->getConsultas();
                $this->view->typeReceita = $this->model->getTypeReceita();
                $this->view->passienteSecretaria = $this->model->getAllPaciente();
                $this->view->moviento = $this->model->getContaMovimento();
                $this->view->formaPagamento = $this->model->getTypePagamento();
                $this->view->pacieteMedico = $this->model->getPacienteSemMedico();
                $this->view->convenio = $this->model->getAllConvenio();
                $this->view->retornoDoPaciente = $this->model->getRetornoDoPaciente();


                $this->view->render($this, 'cpanelSecretaria');
            }

            if (Session::get('role') == 'enfermeiro') {
                $this->view->pacienteMedico = $this->model->getPacienteParaTriagem();

                $this->view->render($this, 'cpanelEnfermagem');
            }
        } else {

            Hook::Header('');
        }
    }

    public function getInfoPaciente($id) {
        return ($this->model->getAllInfoForPrint($id));
    }

    /**
     *
     */
    public function imageUser() {
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
    public function insertImageUser() {

        if (!empty($_POST['quality']) && !empty($_POST['color']) && !empty($_POST['degree'])) {

            $color = substr($_POST['color'], 1);


            $this->imagem = new FileSystem(new Resize());


            $this->imagem->setWidth(2000);
            $this->imagem->setHeight(2000);
            $this->imagem->setOption("perfil");
            $this->imagem->setQuality($_POST['quality']);
            $this->imagem->setColor($color);
            $this->imagem->setDegree($_POST['degree']);


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
    }

    /**
     *
     */
    public function edit() {
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
    public function deletes($id) {
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
    public function updates() {
        if ($_POST['id']) {


            $data['id'] = $_POST['id'];
            $data["firstname"] = $_POST['firstname'];
            $data["lastname"] = $_POST['lastname'];
            $data['username'] = $_POST['username'];
            $data['email'] = $_POST['email'];
            $data["telephone"] = $_POST["telephone"];
            $data["company"] = $_POST["company"];
            $data["address_1"] = $_POST["address_1"];
            $data["address_2"] = $_POST["address_2"];
            $data["postcode"] = $_POST["postcode"];
            $data['zone'] = $_POST['zone'];
            $data['city'] = $_POST['city'];
            $data['bairro'] = $_POST['bairro'];
            $data['dataNascimento'] = $_POST['dataNascimento'];
            $data['country'] = $_POST['country'];

            $hydrat = \Ballybran\Helpers\Stdlib\HydratorConverter::toObject($data, new Pessoa());
            $array = \Ballybran\Helpers\Stdlib\HydratorConverter::toArray($hydrat);

            $this->model->updates($array, $_POST['id']);
            $this->model->logAccess(Session::get("U_NAME"), ":: Atualizou seus dados");

            Hook::Header('account/cpanel');
        } else {
            echo "NADA FOI ATUALIZADO";
        }
    }

    /**
     *
     */
    public function deleteImage() {
        if (Session::exist()) {
            $file = $this->model->getImageUser(Session::get('ID'));

//            var_dump($file);  die;
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
    public function profile($id = null) {
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
    public function manageUser() {
        if ($_POST['id']) {
            $data['role'] = $_POST['role'];
            $this->model->logAccess(Session::get("U_NAME"), ":: Atualizou seus previlegios para " . $_POST['role']);
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
    public function password() {
        $this->view->password = $this->model->getUser(Session::get("ID"))[0];
        $this->view->render($this, 'password');
    }

    /**
     * update the role of users
     *
     * @author
     * */
    public function managePassword() {
        $entity = new Pessoa();


        if ($_POST['id'] && !empty($_POST['password']) && !empty($_POST['oldPassword'])) {
            $youPassword = $_POST['oldPassword'];
            $oldPassword = $this->model->getUser(Session::get("ID"))[0]['password'];


            if (Hash::verify_password($youPassword, $oldPassword)) {
                $entity->setPassword($_POST['password']);
                $data['password'] = $entity->getPassword();
                $this->model->logAccess(Session::get("U_NAME"), ":: Atualizou sua senha");
                $this->model->managePassword($data, $_POST['id']);

                $paciente = $this->model->getUser(Session::get("ID"))[0];


                $mail = new SendMail(new PHPMailer());
                $mail->setFrom("marciozebedeu@gmail.com");
                $mail->setFromName($paciente['firstname'] . "\t" . $paciente['lastname']);
                $mail->setMessage("Tua senha foi aualizada com sucesso para : " . $_POST['password']);
                $mail->setAssunto("Atualização da Senha");
                $mail->setTo($paciente['email']);  // email d visitante vindo do form
                $mail->setAddr($paciente['email']); // enviar para mim (secretaria)

                $mail->send();
                $mail->body();
                Hook::Header('Account/cpanel');
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
    public function horarios() {
        $this->view->title = "Horarios";
        if (Session::get('role') == 'medico') {
            $this->view->horario = $this->model->getHorarioByFunc(Session::get('ID'));
            $this->view->render($this, 'horarios');
        }

        if (Session::get('role') == 'secretaria') {
            $this->view->horario = $this->model->getHorarioByFunc(Session::get('ID'));
            $this->view->render($this, 'horariosSecretaria');
        }
    }

    public function deteleHoraroById($id) {
        if (!empty($id)) {
            $this->model->deteleHoraroById($id);
        }
        Hook::Header('Account/horarios');
    }

    public function insertconsulta() {

        if (Session::get('role') == 'paciente' || Session::get('role') == 'secretaria') {

            $this->form->post('info')->val('maxlength', 1223)
                    ->post('ante')->val('maxlength', 1223)
                    ->post('create_dt')->val('maxlength', 1223)
                    ->post('Convenio_id')->val('maxlength', 1223)
                    ->post('Especialidade_id')->val('maxlength', 1223)
                    ->post('usuarios_id')->val('maxlength', 1223)->submit();

            $paciente = $this->model->getUser(Session::get("ID"))[0];

            // $mail = new SendMail(new PHPMailer());
            // $mail->setFrom("marciozebedeu@gmail.com");
            // $mail->setFromName($paciente['firstname']. "\t". $paciente['lastname']);
            // $mail->setMessage($_POST['ante'] . "<br>" . $_POST['info']);
            // $mail->setAssunto("Nova Consulta");
            // $mail->setTo($paciente['email']);  // email d visitante vindo do form
            // $mail->setAddr("marciozebedeu@gmail.com"); // enviar para mim (secretaria)
            // $mail->send();
            // $mail->body();

            $this->model->inserConsulta($this->form->getPostData());
            $this->model->logPaciente(Session::get("U_NAME"), ":: Fez uma Consulta");

            Hook::Header('account/cpanel');
        }
    }

    public function event() {
        echo json_encode($this->model->getEvent(Session::get('ID')));
    }

    public function getEvent2() {
        echo json_encode($this->model->getEvent2(Session::get('ID')));
    }

    public function events() {
        echo json_encode($this->model->getEvent2());
    }

}
