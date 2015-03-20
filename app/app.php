<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Stylist.php";

  $app = new Silex\Application();

  $DB = new PDO('pgsql:host=localhost;dbname=hair_salon');

  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

  $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig', array('stylists' => Stylist::getAll()));
  });

  $app->get("/stylists/{id}", function($id) use ($app) {
    $stylist = Stylist::find($id);
    $clients = $stylist->getAllClients();
    return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $clients));
  });

  $app->get("/stylists/{id}/edit", function($id) use ($app) {
     $stylist = Stylist::find($id);
     return $app['twig']->render('stylist_edit.html.twig', array('stylist' => $stylist));
  });

  $app->patch("/stylists/{id}", function($id) use ($app) {
    $name = $_POST['name'];
    $stylist = Stylist::find($id);
    $stylist->update($name);
    $clients = $stylist->getAllClients();
    return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $clients));
  });

  $app->post("/stylists", function() use ($app) {
    $stylist = new Stylist($_POST['name']);
    $stylist->save();
    return $app['twig']->render('index.html.twig', array('stylists' => Stylist::getAll()));
  });

  $app->post("/deleteStylists", function() use ($app) {
    Stylist::deleteAll();
    return $app['twig']->render('index.html.twig');
  });

  $app->delete("/stylists/{id}", function($id) use ($app) {
    $stylist = Stylist::find($id);
    $stylist->delete();
    return $app['twig']->render('index.html.twig', array('stylists' => Stylist::getAll()));

    $app->post("/clients", function() use ($app) {
      $client = new Client($_POST['name'], $_POST['stylist_id']);
      $client->save();
      $stylist = Stylist::find($_POST['stylist_id']);
      $clients = $stylist->getAllClients();
      return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $clients));
    });
  });

  return $app;
?>
