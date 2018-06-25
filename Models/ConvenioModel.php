<?php

/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 08/05/18
 * Time: 16:27
 */

namespace Module\Clinic\Models;

use Ballybran\Database\Drives\AbstractDatabaseInterface;

class ConvenioModel {

    /**
     * @var AbstractDatabaseInterface
     */
    private $database;

    public function __construct(AbstractDatabaseInterface $database) {

        $this->database = $database;
    }

    public function getAllConvenio() {
        return $this->database->selectManager("SELECT * FROM Convenio ");
    }

}
