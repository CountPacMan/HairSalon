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
      $found_stylists = null;
      $stylists = Stylist::getAll();
      foreach ($stylists as $stylist) {
        $stylist_id = $stylist->getId();
        if ($stylist_id == $id) {
          $found_stylists = $stylist;
        }
      }
      return $found_stylists;
    }
  }
?>
