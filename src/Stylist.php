<?php
  class Stylist {
    private $name;
    private $id;

    function __construct($name, $id = null)   {
      $this->name = $name;
      $this->id = $id;
    }

    // getters
    function getName()  {
      return $this->name;
    }

    function getId() {
      return $this->id;
    }

    // setters
    function setName($name)  {
      $this->name = (string) $name;
    }

    function setId($id) {
      $this->id = (int) $id;
    }


    // DB

    function save() {
      $statement = $GLOBALS['DB']->query("INSERT INTO stylists (name) VALUES ('{$this->getName()}') RETURNING id;");
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      $this->setId($result['id']);
    }

    function update($name) {
        $GLOBALS['DB']->exec("UPDATE stylists SET name = '{$name}' WHERE id = {$this->getId()}");
        $this->setName($name);
    }

    function delete() {
        // I'm assuming that the client is here for the stylist and absconded when the stylist left.
        $GLOBALS['DB']->exec("DELETE FROM clients WHERE stylist_id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM stylists WHERE id = {$this->getId()};");
    }

    function getAllClients() {
      $returned_clients = $GLOBALS['DB']->query("SELECT * FROM clients WHERE stylist_id = {$this->getId()} ORDER BY name;");
      $clients = array();
      foreach($returned_clients as $client) {
        $name = $client['name'];
        $id = $client['id'];
        $new_client = new Client($name, $this->getId(), $id);
        array_push($clients, $new_client);
      }
      return $clients;
    }

    // static functions

    static function getAll() {
      $returned_stylists = $GLOBALS['DB']->query("SELECT * FROM stylists ORDER BY name;");
      $stylists = array();
      foreach($returned_stylists as $stylist) {
        $name = $stylist['name'];
        $id = $stylist['id'];
        $new_stylist = new Stylist($name, $id);
        array_push($stylists, $new_stylist);
      }
      return $stylists;
    }

    static function deleteAll() {
      $GLOBALS['DB']->exec("DELETE FROM stylists *;");
    }

    static function find($id) {
      $found_stylist = null;
      $stylists = Stylist::getAll();
      foreach ($stylists as $stylist) {
        $stylist_id = $stylist->getId();
        if ($stylist_id == $id) {
          $found_stylist = $stylist;
        }
      }
      return $found_stylist;
    }
  }
?>
