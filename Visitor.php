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
 * Date: 14/01/17
 * Time: 19:37
 */

namespace Module\Clinica;


use Ballybran\Database\Drives\AbstractDatabasePDO;
use Ballybran\Database\RegistryDatabase;
use Ballybran\Helpers\vardump\Vardump;

 class Visitor
{
     private $stmt;

     /**
      * Visitor constructor.
      */
     function __construct()
    {

        $registry = RegistryDatabase::getInstance();
      $this->stmt =  $registry->get("PDO");


    }

     public function __init()
     {
         return $this->stmt->find('usuarios', 'sum(status)', 'status');

     }

     public function __deinit()
     {
         
     }
}