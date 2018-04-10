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


use Ballybran\Core\{ Controller\AbstractController, REST\Encodes, REST\RestUtilities};
use Ballybran\Helpers\Security\{ Hash, vardump\Vardump};

/**
 * Class Index
 * @package Module\Clinic\Controllers
 */
class Api extends AbstractController {

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

        $data = RestUtilities::processRequest();
     
       switch ($data->getMethod()) :
           case 'get':
               $property = $this->model->getAllUser();
               $var = RestUtilities::sendResponse(203, Encodes::encodeJson($property), 'application/json');
               break;
           default:
               # code...
               break;
       endswitch;


    }
    public function rest(){

        $data = RestUtilities::processRequest();
     
       switch ($data->getMethod()) :
           case 'get':
               $property = $this->model->getAllUser();
               $var = RestUtilities::sendResponse(203, Encodes::encodeJson($property), 'application/json');
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
