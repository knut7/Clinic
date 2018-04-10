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
use Ballybran\Helpers\Security\Session;
use Ballybran\Helpers\Time\Timestamp;
use Ballybran\Helpers\Ucfirst;
use Ballybran\Helpers\Utility\Hash;
use Ballybran\Helpers\vardump\Vardump;
use Ballybran\Library\Email;
use Module\Clinic\Entity\Pessoa;
use Module\Lib\SendMail;
use PHPMailer\PHPMailer\PHPMailer;


class User extends AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function index(  )
    {
        Hook::Header('user/login');

    }
    public function Login() {
        if(!Session::exist()) {
            $this->view->title = 'Login';
            $this->view->render($this, 'index');
        }else {
            Hook::Header('account/cpanel');
        }

    }

    /**
     * @param $data
     */
    public function signUp() {

        $this->view->title = "Entrar";

        if ( !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['username']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['sexo']) ) {

            $obj = new Pessoa();
            $obj->setFirstname($_POST['firstname']);
            $obj->setLastname($_POST['lastname']);
            $obj->setEmail($_POST['email']);
            $obj->setPassword($_POST['password']);
            $obj->setUsername($_POST['username']);
            $obj->setSexo($_POST['sexo']);

            $data['username'] = $obj->getUsername();
            $data['password'] = $obj->getPassword();
            $data['lastname'] = $obj->getLastname();
            $data['firstname'] = $obj->getFirstname();
            $data['sexo'] = $obj->getSexo();
            $data['email'] = $obj->getEmail();
            $data['confirmCod'] = rand();
            $data['create_time'] = Timestamp::dataTime();
            $data['numero'] = Hash::simpleToken(4, '1234567890');


            $mail = new SendMail(new PHPMailer());
            $mail->setFrom("marciozebedeu@gmail.com");
            $mail->setFromName($_POST['firstname'] . "\t" . $_POST['lastname']);
            $mail->setMessage("Clique no link para confirmar teu cadastro : " ."\t". URL . "user/confirm/?email=" . $_POST['email'] . "&confirmCod=".$data['confirmCod']);
            $mail->setAssunto("Confirma seu cadastro");
            $mail->setTo($_POST['email']);  // email d visitante vindo do form
            $mail->setAddr($_POST['email']); // enviar para mim (secretaria)

            $mail->send();
            $mail->body();
            $this->model->signUp($data);

            if(!Session::exist()) {
                Hook::Header('user/signIn');
            }
            Hook::Header('account/cpanel');


        }
        $this->view->render($this, 'register');
    }

    public function secretariaCreateNewPaciente() {

        $this->view->title = "Entrar";

        if ( !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['username']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['sexo']) ) {

            $obj = new Pessoa();
            $obj->setFirstname($_POST['firstname']);
            $obj->setLastname($_POST['lastname']);
            $obj->setEmail($_POST['email']);
            $obj->setPassword($_POST['password']);
            $obj->setUsername($_POST['username']);
            $obj->setSexo($_POST['sexo']);

            $data['username'] = $obj->getUsername();
            $data['password'] = $obj->getPassword();
            $data['lastname'] = $obj->getLastname();
            $data['firstname'] = $obj->getFirstname();
            $data['sexo'] = $obj->getSexo();
            $data['email'] = $obj->getEmail();
            $data['confirmCod'] = rand();
            $data['create_time'] = Timestamp::dataTime();
            $data['numero'] = Hash::simpleToken(4, '1234567890');
            $data['confirmed'] = 1;




            if(!Session::exist()) {
                Hook::Header('user/signIn');
            }
            Hook::Header('account/cpanel');
            $this->model->signUp($data);



        }
        $this->view->render($this, 'register');
    }

    /**
     *
     */
    public function signIn()
    {
        $obj = new Pessoa();

        if (!empty($_POST["email"]) && !empty($_POST["password"])) {

            $obj->setUsername($_POST["email"]);


            if (!empty($this->model->signIn(array (':email' => $obj->getUsername())))) {

                $resposta = $this->model->signIn(array (':email' => $obj->getUsername()));;
                $resposta = $resposta[0];


                if (Hash::verify_password($_POST["password"], $resposta["password"]) && $resposta['confirmed'] == 1) {
                    $this->CreateSession($resposta["username"], $resposta["role"], $resposta["id"]);

                    $data['status'] = Session::exist();
                    $this->model->insertSession($data, Session::get('ID'));
                    $this->model->logAcess( Session::get("U_NAME"), " Entrou");
                    Hook::Header('Account/Cpanel');
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
    

    public function formReset()
    {
        $this->view->title = 'Esqueceu sua senha?';
        $this->view->render($this, 'password_reset');
    }

    public function resetPassword()
    {
        if(!empty($_POST['email'])) {

            if (!empty($this->model->selectData(array(':email' => $_POST['email'])))) {

                $resposta = $this->model->selectData(array(':email' => $_POST['email']))[0];

                $mail = new SendMail(new PHPMailer() );
                $mail->setFrom("marciozebedeu@gmail.com");
                $mail->setFromName($resposta['firstname'] . "\t" . $resposta['lastname']);
                $mail->setMessage("Clinic no link para atualizar a senha: " ."\t". URL . "user/newpassword/?id=" . $resposta['id'] . "&email=" . $resposta['email']);
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

    public function newPassword()
    {
        if(!empty($_GET['id']) && !empty($_GET['email'])) {

            $this->view->title = 'Recuperar sua senha?';
            $this->view->id = $_GET['id'];
            $this->view->email = $_GET['email'];
            $this->view->render($this, 'new_password');

        }

    }

    public function makePassword()
    {
        if(!empty($_POST['id']) && !empty($_POST['email']) && !empty($_POST['password'])) {

            $entity = new Pessoa();
            $entity->setPassword($_POST['password']);
            $data['password'] = $entity->getPassword();
            $this->model->updatePassword($data, $_POST['id']);
            $resposta = $this->model->selectData(array(':email'=> $_POST['email']))[0];
            $mail = new SendMail();
            $mail->setFrom("marciozebedeu@gmail.com");
            $mail->setFromName($resposta['firstname'] . "\t" . $resposta['lastname']);
            $mail->setMessage("Tua senha foi criada com sucesso para : " . $_POST['password'] );
            $mail->setAssunto("Atualização da Senha");
            $mail->setTo($resposta['email']);  // email d visitante vindo do form
            $mail->setAddr($resposta['email']); // enviar para mim (secretaria)

            $mail->send();
            $mail->body();
            Hook::Header('user');
        } else{
            Hook::Header('user');
        }
    }

    public function confirm()
    {
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
