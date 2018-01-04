<?php


namespace Module\Lib;

$register = \Ballybran\Database\RegistryDatabase::getInstance();
$stmt =  $register->get("PDO");


if(!empty($_POST["country_id"])) {
    $query = $stmt->selectManager("SELECT * FROM states WHERE countryID = '" . $_POST["country_id"] . "'");
    ?>
    <option value="">Select State</option>
    <?php
    foreach($query as $state) {

        var_dump($state);
        ?>
        <option value="<?php echo $state["id"]; ?>"><?php echo $state["name"]; ?></option>
        <?php
    }
}
?>