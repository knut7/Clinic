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
 * Time: 13:14
 */

namespace Module\Clinic\Models;


use Ballybran\Database\Drives\AbstractDatabaseInterface;

class TestAjaxModel {

    /**
     * @var AbstractDatabaseInterface
     */
    private $database;

    public function __construct(AbstractDatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function getUser()
    {
        return $this->database->find("usuarios", "*");
    }

    public function Backup()
    {
        return $this->database->Backup("test.sql");
    }

    public function select()
    {
        return $this->database->selectManager("SELECT * FROM usuarios");

    }

    public function insert($data, $id)
    {
         $this->database->update('events', $data, "id=$id");

    }
}