<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 30/01/18
 * Time: 06:29
 */

namespace Module\Clinic\Models;


use Ballybran\Database\Drives\AbstractDatabasePDO;

class EventsModel {


    /**
     * @var AbstractDatabasePDO
     */
    private $entity;

    public function __construct(AbstractDatabasePDO $entity)
    {

        $this->entity = $entity;
    }



    public function updatEvent($data, $id)
    {
        $this->entity->update("Func_has_Paci", $data, "id=$id");
    }

    public function updatEvent2($data, $id)
    {
        $this->entity->update("Func_has_Paci", $data, "id=$id");
    }

    public function deleteEvent($Paciente_id)
    {
        $this->entity->delete("Func_has_Paci", "Paciente_id=$Paciente_id", 1);
        $this->entity->delete("Credito", "Paciente_id=$Paciente_id", 1);

    }
    public function updateSituacao($data, $Paciente_id) {

        $this->entity->update("Paciente", $data, "id=$Paciente_id");

    }


}