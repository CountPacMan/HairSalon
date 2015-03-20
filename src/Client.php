<?php
  class Client {
    private $name;
    private $id;
    private $stylist_id;

    function __construct($name, $stylist_id, $id = null)   {
      $this->name = $name;
      $this->stylist_id = $stylist_id;
      $this->id = $id;
    }

    // getters
    function getName()  {
      return $this->name;
    }

    function getId() {
      return $this->id;
    }

    function getStylistId() {
      return $this->stylist_id;
    }

    // setters
    function setName($name)  {
      $this->name = (string) $name;
    }

    function setId($id) {
      $this->id = (int) $id;
    }

    function setStylistId($stylist_id) {
      $this->stylist_id = $stylist_id;
    }


    // DB

    function save() {
      $statement = $GLOBALS['DB']->query("INSERT INTO clients (name, stylist_id) VALUES ('{$this->getName()}', {$this->getStylistId()}) RETURNING id;");
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      $this->setId($result['id']);
    }

    function updateName($name) {
        $GLOBALS['DB']->exec("UPDATE clients SET name = '{$name}' WHERE id = {$this->getId()}");
        $this->setName($name);
    }

    function updateStylist($stylist_id) {
        $GLOBALS['DB']->exec("UPDATE clients SET stylist_id = {$stylist_id} WHERE id = {$this->getId()}");
        $this->setStylistId($stylist_id);
    }

    function delete() {
        $GLOBALS['DB']->exec("DELETE FROM clients WHERE id = {$this->getId()};");
    }

    // static functions

    static function getAll() {
      $returned_clients = $GLOBALS['DB']->query("SELECT * FROM clients ORDER BY name;");
      $clients = array();
      foreach($returned_clients as $client) {
        $name = $client['name'];
        $stylist_id = $client['stylist_id'];
        $id = $client['id'];
        $new_client = new Client($name, $stylist_id, $id);
        array_push($clients, $new_client);
      }
      return $clients;
    }

    static function deleteAll() {
      $GLOBALS['DB']->exec("DELETE FROM clients *;");
    }

    static function find($id) {
      $found_clients = null;
      $clients = Client::getAll();
      foreach ($clients as $client) {
        $client_id = $client->getId();
        if ($client_id == $id) {
          $found_clients = $client;
        }
      }
      return $found_clients;
    }
  }
?>
