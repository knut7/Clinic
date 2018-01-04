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
/**
 *
 * KNUT7 K7F (http://framework.artphoweb.com/)
 * KNUT7 K7F(tm) : Rapid Development Framework (http://framework.artphoweb.com/)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link      http://github.com/zebedeu/artphoweb for the canonical source repository
 * @copyright (c) 2016.  APWEB  Software Technologies AO Inc. (http://www.artphoweb.com)
 * @license   http://framework.artphoweb.com/license/new-bsd New BSD License
 * @author    Marcio Zebedeu - artphoweb@artphoweb.com
 * @version   1.0.0
 */


use Ballybran\Core\Controller\AbstractController;
use Ballybran\Core\REST\Encodes;
use Ballybran\Core\REST\RestUtilities;
use Ballybran\Helpers\Security\Hash;
use Ballybran\Helpers\vardump\Vardump;

/**
 * Class Index
 * @package Module\Clinic\Controllers
 */
class Index extends AbstractController {

    /**
     * Index constructor.
     */
    public function __construct() {

        parent::__construct();

    }


    /**
     *
     */
    public function index() {

        $this->view->title = "home";

        $this->view->public = $this->model->exibirAricle();
        $this->view->users = $this->model->getAllUser();
        $this->view->Allcat = $this->model->_allCategorias();
        $this->view->GetTitile = $this->model->exibiAllTitle();

        $this->view->render($this, 'index');

    }
    public function rest(){

        $data = RestUtilities::processRequest();
     
       switch ($data->getMethod()) :
           case 'get':
               $property = $this->model->getAllUser();
               $var = RestUtilities::sendResponse(200, Encodes::encodeJson($property), 'application/json');
               break;
           default:
               # code...
               break;
       endswitch;

    }

    public function crearTable(){
       $d = $this->model->crearTable();

       var_dump($d);
    }


}
