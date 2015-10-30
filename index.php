<?php
/**
 * Created by PhpStorm.
 * User: ale
 * Date: 29/09/15
 * Time: 20:05
 */
session_start();

require 'vendor/autoload.php';
require 'services/Service.php';
require 'utils/Database.php';
require 'utils/Helper.php';
require 'utils/Filter.php';
require 'utils/TournamentStatus.php';


$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig()
));

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/cache'
);

$app->get('/', function() use($app) {
    $app->render('index.html.twig');
})
->name('login');

$app->post('/login', function() use($app) {
    $name = $app->request->post('name');
    if ($name) {
        // Check if the username isn't already register
        $user = Service::getUserByName($name);
        if (!$user) {
            $user_id = Service::createUser($name);
            $_SESSION['id'] = $user_id;
        } else {
            $_SESSION['id'] = $user[0]['id'];
        }
        $app->redirectTo('home');
    } else {
        $app->redirectTo('login');
    }
});

$app->get('/home', Filter::isRegisteredUser($app), function() use($app) {
    $user_id = $_SESSION['id'];
    $tournaments = Service::getActiveTournaments();
    foreach ($tournaments as $index => $tournament) {
        $tournaments[$index]['registered'] = Service::isUserRegisteredInTournament($tournament['id'], $user_id);
        $tournaments[$index]['num_people'] = Service::countUsersByTournamentId($tournament['id']);
    }
    $app->render('home.html.twig', ['tournaments' => $tournaments, 'user_id' => $user_id]);
})
->name('home');

$app->get('/create-tournament', Filter::isRegisteredUser($app), function() use($app) {
    $app->render('create_tournament.html.twig');
});

$app->get('/logout', function() use($app) {
    unset($_SESSION['id']);
    $app->redirectTo('login');
});

$app->post('/tournaments', Filter::isRegisteredUser($app), function() use($app) {
    $name = $app->request->post('name');
    $time = $app->request->post('time');
    $date = $app->request->post('date');
    $status = TournamentStatus::OPEN_STATUS;
    $promoter_id = $_SESSION['id'];
    $tournament_id = Service::createTournament($name, $promoter_id, Helper::getDateTime($time, $date), $status);
    Service::registerUserInTournament($tournament_id, $promoter_id);
    $app->redirectTo('home');
});

$app->post('/tournaments/:id', Filter::isRegisteredUser($app), function($tournament_id) use($app) {
    $user_id = $_SESSION['id'];
    Service::registerUserInTournament($tournament_id, $user_id);
    $app->redirectTo('participants', ['id' => $tournament_id]);
});

$app->post('/tournaments/:id/delete', Filter::isRegisteredUser($app), function($tournament_id) use($app) {
    $user_id = $_SESSION['id'];
    Service::deregisterUserFromTournament($tournament_id, $user_id);
    $app->redirectTo('home');
});

$app->get('/participants/:id', Filter::isRegisteredUser($app), function($tournament_id) use($app) {
    $users = Service::getUsersByTournamentId($tournament_id);
    $num_people = Service::countUsersByTournamentId($tournament_id);
    $promoter = Service::getPromoterByTournamentId($tournament_id);
    $app->render('see_users_tournament.html.twig', ['users' => $users, 'num_people' => $num_people, 'promoter' => $promoter]);
})
->name('participants');

$app->run();