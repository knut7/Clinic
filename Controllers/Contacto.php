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
 * User: artphotografie
 * Date: 12/01/17
 * Time: 18:27
 */

namespace Module\Clinic\Controllers;


use Ballybran\Core\Controller\AbstractController;
use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\{
    Security\Session, Security\Validate, Security\Val
};
use Module\Lib\SendMail;
use PHPMailer\PHPMailer\PHPMailer;

class Contacto extends AbstractController
{
    private $form;

    public function __construct()
    {
        parent::__construct();
        $this->form = new Validate( new Val );
        $this->form->setMethod("POST");
    }

    public function index()
    {

        $this->view->title = "Contacto";
        $this->view->contacto = $this->model->getContacto();
        $this->view->render($this, 'index');
    }

    public function sobre()
    {
        $this->view->title = "Contacto";
        $this->view->render($this, 'sobre');
    }

    public function contactoInfo()
    {
        if (Session::exist()) {
            if (Session::get("role") == "owner" || Session::get("role") == "admin") {


                $this->view->title = "Insert";
                $this->view->mailConfig =  $this->model->getMailSetting()[0];
                $this->view->contacto = $this->model->getContacto()[0];
                $this->view->social = $this->model->getSocial()[0];
                $this->view->render($this, 'insert-contacto');

            } else {
                Hook::Header("account/cpanel");
            }
        } else {
            Hook::Header("");
        }

    }

    public function insertContacto()
    {
        if ($_POST['id']) {
            $this->form->post("endereco")->val("maxlength", 122)
                ->post("telefone")->val("maxlength", 122)
                ->post("bairro")->val("maxlength", 122)
                ->post("cidade")->val("maxlength", 122)
                ->post("email")->val("maxlength", 122)
                ->post("fax")->val("maxlength", 122)
                ->post("site")->val("maxlength", 122)->submit();

            $this->model->updateContacto($this->form->getPostData(), $_POST['id']);
            Hook::Header("contacto/contactoInfo");
        }
    }


    public function insertSocial()
    {
        if ($_POST['id']) {

            $this->form->post("instagram")->val("maxlength", 122)
                ->post("facebook")->val("maxlength", 122)
                ->post("dribbble")->val("maxlength", 122)
                ->post("twitter")->val("maxlength", 122)->submit();
            $this->model->updateSocial($this->form->getPostData(), $_POST['id']);
            Hook::Header("contacto/contactoInfo");

        }
    }

    public function sendMail()
    {
        if (!empty($_POST['assunto']) && !empty($_POST['email']) && !empty($_POST['message']) && !empty($_POST['nome'])) {


            $mail = new SendMail(new PHPMailer());
            $mail->setFrom("marciozebedeu@gmail.com");
            $mail->setFromName($_POST['nome']);
            $mail->setMessage($_POST['message']);
            $mail->setAssunto($_POST['assunto']);
            $mail->setTo($_POST['email']);  // email d visitante vindo do form
            $mail->setAddr("marciozebedeu@gmail.com"); // enviar para mim

            $mail->send();
            $mail->body();
            Hook::header();




        }
    }

    public function updatEmailConfig()
    {
        if (!empty($_POST['id'])
            && !empty($_POST['Lang']) && !empty($_POST['Auth']) && !empty($_POST['Charset'])
            && !empty($_POST['Html']) && !empty($_POST['Port']) && !empty($_POST['Host'])
            && !empty($_POST['Username']) && !empty($_POST['Password']) && !empty($_POST['Secure'])
            && !empty($_POST['From']) && !empty($_POST['FromName'])
            && !empty($_POST['Message1'])  && !empty($_POST['Message2']) && !empty($_POST['Message3'])) {

            $data['Lang'] = $_POST['Lang'];
            $data['Auth'] = $_POST['Auth'];
            $data['Charset'] = $_POST['Charset'];
            $data['Html'] = $_POST['Html'];
            $data['Port'] = $_POST['Port'];
            $data['Host'] = $_POST['Host'];
            $data['Username'] = $_POST['Username'];
            $data['Password'] = $_POST['Password'];
            $data['Secure'] = $_POST['Secure'];
            $data['From'] = $_POST['From'];
            $data['FromName'] = $_POST['FromName'];
            $data['Message1'] = $_POST['Message1'];
            $data['Message2'] = $_POST['Message2'];
            $data['Message3'] = $_POST['Message3'];

            $this->model->updateMailConfig($data, $_POST['id']);
            Hook::Header("contacto/contactoInfo");

        }

    }

    public function createBackup()
    {
        if(! empty($_GET['backup'])) {
        $back = new \Ballybran\Database\MySQLDump();
        $back->save(\Ballybran\Helpers\Utility\Hash::token().'-'.time().'txt');
        Hook::Header("contacto/contactoInfo");

        }else{
            echo "nooo";
        }
    
    }


}