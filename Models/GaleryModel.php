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

namespace Module\Clinic\Models;

use Ballybran\Database\Drives\AbstractDatabaseInterface;

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

class GaleryModel {
    /**
     * @var interfaceModel instanciando o BD
     */
//    private $m;

    /**
     * @var iDatabase instanciando o BD
     */
    private $entity;

    public function __construct( AbstractDatabaseInterface $entity) {

        $this->entity = $entity;
    }

    /**
     * @param $id id for user selected by session
     * @return array
     */
    public function getUser($id) {
//        $this->db->
        return $this->entity->selectManager('SELECT * FROM usuarios WHERE id =:id', ["id" => $id]);
    }

//    public function getUser() {
//        return $this->db->select('SELECT * FROM usuarios WHERE id =:id', array("id" => $id));
//    }


    public function insertImage($data) {
        return $this->entity->insert('photographer', $data);
    }

    public function getImage() {

        return $this->entity->selectManager('SELECT * FROM  photographer WHERE id ORDER BY id desc ');
    }

    public function unlinkgetImage($id) {

        return $this->entity->selectManager("SELECT * FROM  photographer WHERE id=:id", ["id" => $id]);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     * */
    public function delete($id) {
        $this->entity->delete('photographer', "id=$id", 1);
    }

}
