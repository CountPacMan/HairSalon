<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Stylist.php";
  require_once __DIR__."/../src/Client.php";

  $app = new Silex\Application();

  $DB = new PDO('pgsql:host=localhost;dbname=hair_salon');

  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

  // get **********************************

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

  $app->get("/client/{id}/edit", function($id) use ($app) {
    $client = Client::find($id);
    return $app['twig']->render('client_edit.html.twig', array('client' => $client, 'stylist_id' => $_GET['stylist_id'], 'stylists' => Stylist::getAll()));
  });

  // post **********************************

  $app->post("/stylists", function() use ($app) {
    $stylist = new Stylist($_POST['name']);
    $stylist->save();
    return $app['twig']->render('index.html.twig', array('stylists' => Stylist::getAll()));
  });

  $app->post("/clients", function() use ($app) {
    $client = new Client($_POST['name'], $_POST['stylist_id']);
    $client->save();
    $stylist = Stylist::find($_POST['stylist_id']);
    $clients = $stylist->getAllClients();
    return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $clients));
  });

  // patch **********************************

  $app->patch("/stylists/{id}", function($id) use ($app) {
    $name = $_POST['name'];
    $stylist = Stylist::find($id);
    $stylist->update($name);
    $clients = $stylist->getAllClients();
    return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $clients));
  });

  $app->patch("/client/{id}", function($id) use ($app) {
    $name = $_POST['name'];
    $stylist = Stylist::find($_POST['stylist_id']);
    $client = Client::find($id);
    $client->updateName($name);
    $clients = $stylist->getAllClients();
    return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $clients));
  });

  $app->patch("/reassign/{id}", function($id) use ($app) {
    $client = Client::find($id);
    $client->updateStylist($_POST['stylist_id']);
    $stylist = Stylist::find($_POST['stylist_id']);
    $clients = $stylist->getAllClients();
    return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $clients));
  });

  // delete **********************************

  $app->delete("/stylists/{id}", function($id) use ($app) {
    $stylist = Stylist::find($id);
    $stylist->delete();
    return $app['twig']->render('index.html.twig', array('stylists' => Stylist::getAll()));
  });

  $app->delete("/client/{id}", function($id) use ($app) {
    $client = Client::find($id);
    $client->delete();
    $stylist = Stylist::find($_POST['stylist_id']);
    $clients = $stylist->getAllClients();
    return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $clients));
  });

  return $app;
?>
