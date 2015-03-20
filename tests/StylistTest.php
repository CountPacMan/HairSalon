<?php

  /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

  require_once "src/Stylist.php";

  $DB = new PDO('pgsql:host=localhost;dbname=hair_salon_test');


  /*  Spec 1. User runs getName; app returns stylist name.
      Spec 2. User runs getId; app returns stylist id.
      Spec 3. Database saves entry.
      Spec 4. User requests a stylist by id. Returns Stylist object.
  */

  class StylistTest extends PHPUnit_Framework_TestCase {

    protected function tearDown() {
      Stylist::deleteAll();
    }

    // Spec 1
    function test_getName() {
      // Arrange
      $name = "Biscuitdoughhands Man";
      $test_Stylist = new Stylist($name);

      // Act
      $result = $test_Stylist->getName();

      // Assert
      $this->assertEquals($name, $result);
    }

    // Spec 2
    function test_getId() {
      // Arrange
      $name = "Biscuitdoughhands Man";
      $test_Stylist = new Stylist($name, 1);

      // Act
      $result = $test_Stylist->getId();

      // Assert
      $this->assertEquals(1, $result);
    }

    // Spec 3
    function test_save() {
      // Arrange
      $name = "Biscuitdoughhands Man";
      $test_Stylist = new Stylist($name);

      // Act
      $test_Stylist->save();
      $result = Stylist::getAll();

      // Assert
      $this->assertEquals($test_Stylist, $result[0]);
    }
  }
?>
