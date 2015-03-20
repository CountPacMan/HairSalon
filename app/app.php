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
    return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist));
  });

  $app->get("/stylists/{id}/edit", function($id) use ($app) {
     $stylist = Stylist::find($id);
     return $app['twig']->render('stylist_edit.html.twig', array('stylist' => $stylist));
  });

  $app->patch("/stylists/{id}", function($id) use ($app) {
    $name = $_POST['name'];
    $stylist = Stylist::find($id);
    $stylist->update($name);
    return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist));
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
  });

  return $app;
?>
