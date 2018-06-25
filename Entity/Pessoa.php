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
 * User: artphotografie
 * Date: 05/01/17
 * Time: 00:27
 */

namespace Module\Clinic\Entity;

use const ALGO;
use Ballybran\Helpers\Security\ValidateTypes;
use Ballybran\Helpers\Ucfirst;
use Ballybran\Helpers\Utility\Hash;
use const HASH_KEY;

class Pessoa {

    private $id;
    private $country;
    private $firstname;
    private $lastname;
    private $username;
    private $email;
    private $telephone;
    private $telephone2;
    private $company;
    private $address_1;
    private $address_2;
    private $postcode;
    private $zone;
    private $confirm;
    private $bairro;
    private $numero;
    private $confirmed;
    private $createTime;
    private $confirmCod;

    /**
     * @return mixed
     */
    public function getConfirmed() {
        return $this->confirmed;
    }

    /**
     * @param mixed $confirmed
     */
    public function setConfirmed($confirmed) {
        $this->confirmed = $confirmed;
    }

    /**
     * @return mixed
     */
    public function getCreateTime() {
        return $this->createTime;
    }

    /**
     * @param mixed $createTime
     */
    public function setCreateTime($createTime) {
        $this->createTime = $createTime;
    }

    /**
     * @return mixed
     */
    public function getConfirmCod() {
        return $this->confirmCod;
    }

    /**
     * @param mixed $confirmCod
     */
    public function setConfirmCod($confirmCod) {
        $this->confirmCod = $confirmCod;
    }

    /**
     * @return mixed
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero) {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getConfirm() {
        return $this->confirm;
    }

    /**
     * @param mixed $confirm
     */
    public function setConfirm($confirm) {
        $this->confirm = $confirm;
    }

    /**
     * @return mixed
     */
    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    /**
     * @param mixed $dataNascimento
     */
    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    private $city;
    private $password;
    private $role;
    private $status;
    private $sexo;
    private $dataNascimento;

    /**
     * @return mixed
     */
    public function getSexo() {
        return $this->sexo;
    }

    /**
     * @param mixed $sexo
     */
    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    /**
     * @return mixed
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param mixed $country_id
     */
    public function setCountry($country) {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname) {
        $this->firstname = ValidateTypes::getSQLValueString(Ucfirst::_ucfirst($firstname), 'text');
    }

    /**
     * @return mixed
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname) {
        $this->lastname = ValidateTypes::getSQLValueString(Ucfirst::_ucfirst($lastname), 'text');
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username) {
        $this->username = ValidateTypes::getSQLValueString($username, 'text');
        ;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = ValidateTypes::getSQLValueString($email, 'email');
        ;
    }

    /**
     * @return mixed
     */
    public function getTelephone() {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone) {
        $this->telephone = ValidateTypes::getSQLValueString($telephone, 'char');
        ;
    }

    /**
     * @return mixed
     */
    public function getTelephone2() {
        return $this->telephone2;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone2($telephone) {
        $this->telephone2 = ValidateTypes::getSQLValueString($telephone, 'char');
        ;
    }

    /**
     * @return mixed
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company) {
        $this->company = ValidateTypes::getSQLValueString($company, 'text');
    }

    /**
     * @return mixed
     */
    public function getAddress1() {
        return $this->address_1;
    }

    /**
     * @param mixed $address_1
     */
    public function setAddress1($address_1) {
        $this->address_1 = ValidateTypes::getSQLValueString($address_1, 'text');
    }

    /**
     * @return mixed
     */
    public function getAddress2() {
        return $this->address_2;
    }

    /**
     * @param mixed $address_2
     */
    public function setAddress2($address_2) {
        $this->address_2 = ValidateTypes::getSQLValueString($address_2, 'text');
    }

    /**
     * @return mixed
     */
    public function getPostcode() {
        return $this->postcode;
    }

    /**
     * @param mixed $postcode
     */
    public function setPostcode($postcode) {
        $this->postcode = ValidateTypes::getSQLValueString($postcode, 'char');
    }

    /**
     * @return mixed
     */
    public function getZone() {
        return $this->zone;
    }

    /**
     * @param mixed $zone
     */
    public function setZone($zone) {
        $this->zone = $zone;
    }

    /**
     * @return mixed
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = Hash::hash_password($password, PASSWORD_DEFAULT);
    }

    /**
     * @return mixed
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role) {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = ValidateTypes::getSQLValueString($id, 'int');
    }

    public function getBairro() {
        return $this->bairro;
    }

    /**
     * @param mixed $bairro
     */
    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function getEntity() {


        $this->getFirstname();
        $this->getLastname();
        $this->getUsername();
        $this->getEmail();
        $this->getTelephone();
        $this->getCompany();
        $this->getAddress1();
        $this->getAddress2();
        $this->getCity();
        $this->getCountry();
        $this->getZone();
        $this->getPostcode();
        $this->getBairro();

        return;
    }

}
