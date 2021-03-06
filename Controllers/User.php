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

use Ballybran\Core\Collections\Collection\IteratorCollection;
use Ballybran\Core\Controller\AbstractController;

use Ballybran\Helpers\ {
    Http\Hook, Log\Log, Security\Session, Security\Val, Security\Validate, Stdlib\HydratorConverter, Time\Timestamp, Utility\Hash
};

use Module\Clinic\Entity\Pessoa;
use Module\Lib\SendMail;
use PHPMailer\PHPMailer\PHPMailer;

class User extends AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        Hook::Header('user/login');
    }

    public function Login() {
        if (!Session::exist()) {
            $this->view->title = 'Login';
            $this->view->render($this, 'index');
        } else {
            Hook::Header('account/cpanel');
        }
    }

    public function complete() {
        $this->view->render($this, "complete");
    }

    /**
     * @param $data
     */
    public function signUp() {

        $this->view->title = "Entrar";

        if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['username']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['sexo'])) {


            $_POST['createTime'] = "";
            $_POST['confirmCod'] = "";
            $_POST['numero'] = 1;
            $val = new Validate(new Val());
            $val->setMethod('POST');
            $val->post('firstname')->text()->val('maxlength', 100)
                    ->post('lastname')->text()->val('maxlength', 100)
                    ->post('username')->text()->val('maxlength', 100)
                    ->post('email')->email()->val('maxlength', 100)
                    ->post('password')->text()->val('maxlength', 100)
                    ->post('sexo')->text()->val('maxlength', 100)
                    ->post('confirmCod')->text()->val('maxlength', 100)
                    ->post('createTime')->val('maxlength', 100)
                    ->post('numero')->numeric()->val('maxlength', 100)
                    ->post('telephone')->numeric()->val('maxlength', 100);

            $it = new IteratorCollection($val->getPostData());
            $it->set('password', Hash::hash_password($_POST['password']));
            $it->set('confirmCod', rand());
            $it->set('numero', Hash::simpleToken(4, "1234567890"));
            $it->set('createTime', \Ballybran\Helpers\Time\Timestamp::dataTime());


            $mail = new SendMail(new PHPMailer());
            $mail->setFrom("marciozebedeu@gmail.com");
            $mail->setFromName($_POST['firstname'] . "\t" . $_POST['lastname']);
            $mail->setMessage("Clique no link para confirmar teu cadastro : " . "\t" . URL . "user/confirm/?email=" . $_POST['email'] . "&confirmCod=" . $it->get('confirmCod'));
            $mail->setAssunto("Confirma seu cadastro");
            $mail->setTo($_POST['email']);  // email d visitante vindo do form
            $mail->setAddr($_POST['email']); // enviar para mim (secretaria)

            $mail->send();
            $mail->body();

            $this->model->signUp($it->toArray());

            if (!Session::exist()) {
                Hook::Header('user/complete');
            }
            Hook::Header('account/cpanel');
        }
        $this->view->render($this, 'register');
    }

    public function secretariaCreateNewFuncionario() {

        $this->view->title = "Entrar";

        if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['username']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['sexo'])) {


            $_POST['createTime'] = "";
            $_POST['confirmCod'] = "";
            $_POST['numero'] = 1;
            $val = new Validate(new Val());
            $val->setMethod('POST');
            $val->post('firstname')->text()->val('maxlength', 100)
                    ->post('lastname')->text()->val('maxlength', 100)
                    ->post('username')->text()->val('maxlength', 100)
                    ->post('email')->email()->val('maxlength', 100)
                    ->post('password')->text()->val('maxlength', 100)
                    ->post('sexo')->text()->val('maxlength', 100)
                    ->post('confirmCod')->text()->val('maxlength', 100)
                    ->post('createTime')->val('maxlength', 100)
                    ->post('numero')->numeric()->val('maxlength', 100)
                    ->post('telephone')->numeric()->val('maxlength', 100)
                    ->post('role')->text()->val('maxlength', 100);

            $it = new IteratorCollection($val->getPostData());
            $it->set('password', Hash::hash_password($_POST['password']));
            $it->set('confirmed', 1);
            $it->set('confirmCod', rand());
            $it->set('numero', Hash::simpleToken(4, "1234567890"));
            $it->set('createTime', \Ballybran\Helpers\Time\Timestamp::dataTime());



            $this->model->signUp($it->toArray());

            if (!Session::exist()) {
                Hook::Header('user/complete');
            }
            Hook::Header('account/cpanel');
        }
        $this->view->render($this, 'register');
    }

    public function secretariaCreateNewPaciente() {

        $this->view->title = "Entrar";

        if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['username']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['sexo'])) {



            $data['username'] = $_POST['username'];
            $data['password'] = $_POST['password'];
            $data['lastname'] = $_POST['lastname'];
            $data['firstname'] = $_POST['firstname'];
            $data['telephone'] = $_POST['telephone'];
            $data['sexo'] = $_POST['sexo'];
            $data['email'] = $_POST['email'];
            $data['confirmCod'] = rand();
            $data['createTime'] = Timestamp::dataTime();
            $data['numero'] = Hash::simpleToken(4, '1234567890');
            $data['confirmed'] = 1;

            $hydrator = HydratorConverter::toObject($data, new Pessoa());
            $toArray = HydratorConverter::toArray($hydrator);

            if (!Session::exist()) {
                Hook::Header('user/signIn');
            }

            $this->model->signUp($toArray);

            Hook::Header('account/cpanel');
        }
        $this->view->render($this, 'register');
    }

    /**
     *
     */
    public function signIn() {
        $obj = new Pessoa();

        if (!empty($_POST["email"]) && !empty($_POST["password"])) {

            $obj->setUsername($_POST["email"]);


            if (!empty($this->model->signIn(array(':email' => $obj->getUsername())))) {

                $resposta = $this->model->signIn(array(':email' => $obj->getUsername()));
                $resposta = $resposta[0];


                if (Hash::verify_password($_POST["password"], $resposta["password"]) && $resposta['confirmed'] == 1) {
                    $this->CreateSession($resposta["username"], $resposta["role"], $resposta["id"]);

                    $data['status'] = Session::exist();
                    $this->model->insertSession($data, Session::get('ID'));
                    $this->model->logAcess(Session::get("U_NAME"), " Entrou");
                    Hook::Header('account/cpanel');
                } else {
                    Hook::Header(' ');
                }
            } else {
                Hook::Header('user/signUp');
            }
        } else {
            Hook::Header('user');
        }
    }

    public function formReset() {
        $this->view->title = 'Esqueceu sua senha?';
        $this->view->render($this, 'password_reset');
    }

    public function resetPassword() {
        if (!empty($_POST['email'])) {

            if (!empty($this->model->selectData(array(':email' => $_POST['email'])))) {

                $resposta = $this->model->selectData(array(':email' => $_POST['email']))[0];

                $mail = new SendMail(new PHPMailer());
                $mail->setFrom("marciozebedeu@gmail.com");
                $mail->setFromName($resposta['firstname'] . "\t" . $resposta['lastname']);
                $mail->setMessage("Clinic no link para atualizar a senha: " . "\t" . URL . "user/newpassword/?id=" . $resposta['id'] . "&email=" . $resposta['email']);
                $mail->setAssunto("Recuperarção da Senha");
                $mail->setTo($resposta['email']);  // email d visitante vindo do form
                $mail->setAddr($resposta['email']); // enviar para mim (secretaria)

                $mail->send();
                $mail->body();
                Hook::header("account/cpanel");
            } else {
                Hook::Header('user');
            }
        }
    }

    public function newPassword() {
        if (!empty($_GET['id']) && !empty($_GET['email'])) {

            $this->view->title = 'Recuperar sua senha?';
            $this->view->id = $_GET['id'];
            $this->view->email = $_GET['email'];
            $this->view->render($this, 'new_password');
        }
    }

    public function makePassword() {
        if (!empty($_POST['id']) && !empty($_POST['email']) && !empty($_POST['password'])) {

            $entity = new Pessoa();
            $entity->setPassword($_POST['password']);
            $data['password'] = $entity->getPassword();
            $this->model->updatePassword($data, $_POST['id']);
            $resposta = $this->model->selectData(array(':email' => $_POST['email']))[0];
            $mail = new SendMail(new PHPMailer());
            $mail->setFrom("marciozebedeu@gmail.com");
            $mail->setFromName($resposta['firstname'] . "\t" . $resposta['lastname']);
            $mail->setMessage("Tua senha foi criada com sucesso para : " . $_POST['password']);
            $mail->setAssunto("Atualização da Senha");
            $mail->setTo($resposta['email']);  // email d visitante vindo do form
            $mail->setAddr($resposta['email']); // enviar para mim (secretaria)

            $mail->send();
            $mail->body();
            Hook::Header('user');
        } else {
            Hook::Header('user');
        }
    }

    public function confirm() {

        $this->view->title =  "E-mail Confirmado";

        if (!empty($_GET['confirmCod']) && !empty($_GET['email'])) {


            $data['confirmed'] = 1;
            $this->model->confirm($data, $_GET['confirmCod']);
            $this->view->render($this, 'confirm');
        } else {
            $this->view->render($this, 'not-confirm');
        }
    }

    /**
     *
     * @param type $username
     * @param type $id
     */
    public function CreateSession($username, $role, $id) {
        Session::set('U_NAME', $username);
        Session::set('role', $role);
        Session::set('ID', $id);
    }

    public function DestroySession() {
        $data['status'] = !Session::exist();
        $this->model->logAcess(Session::get("U_NAME"), " Saiu");
        $this->model->insertSession($data, Session::get('ID'));
        Session::Destroy();
        Hook::Header('');
    }

}
