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
use Ballybran\Helpers\Security\Session;
use Ballybran\Helpers\Security\Validate;

class Contacto extends AbstractController
{
    private $form;

    public function __construct()
    {
        parent::__construct();
        $this->form = new Validate();
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
        if($_POST['id']) {

            $this->form->post("instagram")->val("maxlength", 122)
                ->post("facebook")->val("maxlength", 122)
                ->post("dribbble")->val("maxlength", 122)
                ->post("twitter")->val("maxlength", 122)->submit();
            $this->model->updateSocial($this->form->getPostData(), $_POST['id']);
            Hook::Header("contacto/contactoInfo");

        }
    }


}