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
 * Date: 02/01/18
 * Time: 13:03
 */

namespace Module\Clinic\Controllers;


use Ballybran\Core\{
    Controller\AbstractController,
    REST\Encodes, REST\RestUtilities,  REST\Client\ClientRest
};


class TestAjax extends AbstractController {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->view->title = " TEXT DE SERACH";

        $this->view->select = $this->model->select( 'events', 'teste', 100, 'NOT NULL');
        $this->view->render($this, "index" );
    }

    public function insert()
    {

        if(! empty($_GET['id'])) {
            $data['start'] = $_GET['start'];
            $data['end'] = $_GET['end'];
            $d = $this->model->insert($data, $_GET['id']);
//            var_dump($data);
        }

    }


    public function getRest() {
if(!empty($_POST['id'])) {

         $rest = new ClientRest();

         $for = $rest->get("http://localhost:8888/CLINIC/testajax/search/32");

         var_dump($for); die;

         foreach ($for as $key => $value) {
             echo $value->password;
         }

     }else {
        echo "string";
     }
    }

    public function search($id)
    {
                // $this->view->render($this, "serach" );

       $datas =  $this->model->getUser($id);

       $RestUtilities = RestUtilities::processRequest();

        $c = $RestUtilities->getMethod();


switch ($c) {
    case 'get':

        return RestUtilities::sendResponse(404, Encodes::encodeJson($datas), "application/Json");
 
        break;
    
    default:

        break;
}


    }
}