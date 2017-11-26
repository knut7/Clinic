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
use Module\Entity\Pessoa;

/**
 * KNUT7 K7F (http://framework.artphoweb.com/)
 * KNUT7 K7F(tm) : Rapid Development Framework (http://framework.artphoweb.com/)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link      http://github.com/zebedeu/artphoweb for the canonical source repository
 * Copyright (c) 2017.  APWEB  Software Technologies AO Inc. (http://www.artphoweb.com)
 * @license   http://framework.artphoweb.com/license/new-bsd New BSD License
 * @author    Marcio Zebedeu - artphoweb@artphoweb.com
 * @version   1.0.0
 */


class Dashboard extends AbstractController {

    public function __construct() {
        parent::__construct();

        $this->view->Js = array('Dashboard/Js/default.js');
    }

    public function index() {
        if (Session::exist()) {
            if (Session::get('role') == 'owner') {
                $this->view->title = "Dashboard";
                $this->view->render($this, 'index');
            }
        } else {
            Hook::Header('');
        }
    }

    /**
     *
     */
    public function customers() {
        if (Session::exist()) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $this->view->title = "Customers";
                $this->view->user = $this->model->getAllUser();
                $this->view->render($this, 'customers');
            } else {
                Hook::Header('');
            }
        }

    }

    public function add() {

        if (Session::exist()) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {
                $this->view->title = "Customers";
                $this->view->render($this, 'add');
            }
        }

    }

    /**
     * @param $data
     */
    public function edit($id, $username = null)
    {

        if (Session::exist()) {
            if (Session::get('role') == 'owner' || Session::get('role') == 'admin') {

                $this->view->title = "Customers";
                $this->view->UserData = $this->model->getUser($id)[0];
                $this->view->users = $this->model->getusers($id);
                $this->view->render($this, 'edit');
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
            if (Session::get('role') == 'owner') {
                $this->model->deletes($id);
                Hook::Header('Dashboard/customers');
            } else {
                Hook::Header('user/signUp');
            }
        }
    }

}
