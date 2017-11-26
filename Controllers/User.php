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
use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\Security\Session;
use Ballybran\Helpers\Time\Timestamp;
use Ballybran\Helpers\Ucfirst;
use Ballybran\Helpers\Utility\Hash;
use Ballybran\Helpers\vardump\Vardump;
use Module\Entity\Pessoa;


class User extends AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function index(  )
    {
        Hook::Header('user/login');

    }
    public function Login() {
        $this->view->title = 'Login';
        $this->view->render($this, 'index');
    }

    /**
     * @param $data
     */
    public function signUp() {

        $this->view->title = "Entrar";

        if ( !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['username']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {

            $obj = new Pessoa();
            $obj->setFirstname($_POST['firstname']);
            $obj->setLastname($_POST['lastname']);
            $obj->setEmail($_POST['email']);
            $obj->setPassword($_POST['password']);
            $obj->setUsername($_POST['username']);

            $data['username'] = $obj->getUsername();
            $data['password'] = $obj->getPassword();
            $data['lastname'] = $obj->getLastname();
            $data['firstname'] = $obj->getFirstname();
            $data['email'] = $obj->getEmail();
            $data['create_time'] = Timestamp::dataTime();


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

        if (!empty($_POST["username"]) && !empty($_POST["password"])) {

            $obj->setUsername($_POST["username"]);
            $obj->setPassword($_POST["password"]);


            if (!empty($this->model->signIn(array (':username' => $obj->getUsername())))) {

                $resposta = $this->model->signIn(array (':username' => $obj->getUsername()));;
                $resposta = $resposta[0];


                if ($resposta["password"] == $obj->getPassword()) {
                    $this->CreateSession($resposta["username"], $resposta["role"], $resposta["id"]);

                    $data['status'] = Session::exist();
                    $this->model->insertSession($data, Session::get('ID'));
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
    
    /**
     *
     */
    public function selectData() {
        $resposta = $this->model->selectData(array(':username', $_POST['username']));
        $resposta = $resposta[0];
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
        $this->model->insertSession($data, Session::get('ID'));
        Session::Destroy();
        Hook::Header('');
    }

}
