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
use Ballybran\Core\REST\Client\ClientRest;
use Ballybran\Core\REST\RestUtilities;
use Ballybran\Helpers\Http\Hook;
use Ballybran\Helpers\Security\Hash;
use Ballybran\Helpers\Security\Session;

class Faq extends AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view->title = 'FAQ';
        $this->view->faque = $this->model->_getFaq();
        $this->view->render($this, 'index');
    }

    /**
     * @param $data
     */
    public function insertFaq() {

        if (!empty($_POST['quest']) && !empty($_POST['respo'])) {
            $data['quest'] = $_POST['quest'];
            $data['respo'] = $_POST['respo'];
            $this->model->insertFaq($data);
        }
        $this->view->title = "POSTAR FAQ";
        $this->view->render($this, 'insertFaq');
    }

    public function delete($id) {
        if (Session::exist() && Session::get('role') == 'owner' || Session::get('role') == 'admin') {

            $this->model->delete($id);
            Hook::Header('faq');
        }
    }

    public function rest() {

        $data = RestUtilities::processRequest();
        $property = $this->insertFaq();


        switch ($data->getMethod()) {
            case 'post':

                $var = RestUtilities::sendResponse(200, json_encode($property), 'application/json');

                break;

            default:
                # code...
                break;
        }
    }

    public function getRest() {

        $new = new ClientRest();
        echo $new->post('faq/insertFaq', ['quest' => 763, 'respo' => 10]);
    }

}
