<?php

  /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

  require_once "src/Client.php";
  require_once "src/Stylist.php";

  $DB = new PDO('pgsql:host=localhost;dbname=hair_salon_test');


  /*  Spec 1. User runs getName; app returns client name.
      Spec 2. User runs getId; app returns client id.
      Spec 3. Database saves entry.
      Spec 4. User requests a client by id. Returns Client object.
      Spec 5. User updates a client's name. Returns updated Client object.
      Spec 6. User deletes a client. Returns empty array of Client objects.
      Spec 7. User updates a client's stylist. Returns updated Client object.
  */

  class ClientTest extends PHPUnit_Framework_TestCase {

    protected function tearDown() {
      Client::deleteAll();
      Stylist::deleteAll();
    }

    // Spec 1
    function test_getName() {
      // Arrange
      $test_Stylist = new Stylist("testman");
      $test_Stylist->save();
      $stylist_id = $test_Stylist->getId();
      $name = "Biscuitdoughhands Man";
      $test_Client = new Client($name, $stylist_id);

      // Act
      $result = $test_Client->getName();

      // Assert
      $this->assertEquals($name, $result);
    }

    // Spec 2
    function test_getId() {
      // Arrange
      $test_Stylist = new Stylist("testman");
      $test_Stylist->save();
      $stylist_id = $test_Stylist->getId();
      $name = "Biscuitdoughhands Man";
      $test_Client = new Client($name, $stylist_id, 3);

      // Act
      $result = $test_Client->getId();

      // Assert
      $this->assertEquals(3, $result);
    }

    // Spec 3
    function test_save() {
      // Arrange
      $test_Stylist = new Stylist("testman");
      $test_Stylist->save();
      $stylist_id = $test_Stylist->getId();
      $name = "Biscuitdoughhands Man";
      $test_Client = new Client($name, $stylist_id);

      // Act
      $test_Stylist->save();
      $test_Client->save();
      $result = Client::getAll();

      // Assert
      $this->assertEquals($test_Client, $result[0]);
    }

    // Spec 4
    function test_find() {
      // Arrange
      $test_Stylist = new Stylist("testman");
      $test_Stylist2 = new Stylist("testman2");
      $test_Stylist->save();
      $stylist_id = $test_Stylist->getId();
      $test_Stylist2->save();
      $stylist_id2 = $test_Stylist2->getId();
      $name = "Biscuitdoughhands Man";
      $test_Client = new Client($name, $stylist_id, 1);
      $name2 = "Mr Spakuru";
      $test_Client2 = new Client($name2, $stylist_id2, 2);

      // Act
      $test_Client->save();
      $test_Client2->save();
      $result = Client::find($test_Client->getId());

      // Assert
      $this->assertEquals($test_Client, $result);
    }

    // Spec 5
    function test_update() {
      // Arrange
      $test_Stylist = new Stylist("testman");
      $test_Stylist->save();
      $stylist_id = $test_Stylist->getId();
      $name = "Biscuitdoughhands Man";
      $test_Client = new Client($name, $stylist_id);

      // Act
      $test_Client->save();
      $test_Client->updateName("Mr Spakuru");
      $result = Client::getAll();

      // Assert
      $this->assertEquals("Mr Spakuru", $result[0]->getName());
    }

    // Spec 6
    function test_delete() {
      // Arrange
      $test_Stylist = new Stylist("testman");
      $test_Stylist->save();
      $stylist_id = $test_Stylist->getId();
      $name = "Biscuitdoughhands Man";
      $test_Client = new Client($name, $stylist_id);

      // Act
      $test_Client->save();
      $test_Client->delete();
      $result = Client::getAll();

      // Assert
      $this->assertEquals(0, count($result));
    }

    // Spec 7
    function test_updateStylist() {
      // Arrange
      $test_Stylist = new Stylist("testman");
      $test_Stylist->save();
      $test_Stylist2 = new Stylist("testman2");
      $test_Stylist2->save();
      $stylist_id = $test_Stylist->getId();
      $stylist_id2 = $test_Stylist->getId();
      $name = "Biscuitdoughhands Man";
      $test_Client = new Client($name, $stylist_id);

      // Act
      $test_Client->save();
      $test_Client->updateStylist($stylist_id2);
      $result = Client::getAll();

      // Assert
      $this->assertEquals($stylist_id2, $result[0]->getStylistId());
    }
  }
?>
