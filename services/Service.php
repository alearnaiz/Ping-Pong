<?php
/**
 * Created by PhpStorm.
 * User: ale
 * Date: 4/10/15
 * Time: 16:14
 */

class Service
{

    public static function getUserByName($name)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM user WHERE name=?");
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createUser($name)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('INSERT INTO user (name) VALUES (?)');
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getTournaments()
    {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM tournament");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getActiveTournaments()
    {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM tournament WHERE date > NOW()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createTournament($name, $date)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('INSERT INTO tournament (name, date) VALUES (?, ?)');
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->bindParam(2, $date, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getUsersOfTournament($tournament_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('SELECT * FROM user INNER JOIN tournament_user ON user.id = tournament_user.user_id WHERE tournament_user.tournament_id = ?');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function registerForTournament($tournament_id, $user_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('INSERT INTO tournament_user (tournament_id, user_id) VALUES (?, ?)');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function deregisterFromTournament($tournament_id, $user_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('DELETE FROM tournament_user WHERE tournament_id = ? AND user_id = ?');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function isRegisteredInTournament($tournament_id, $user_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM tournament_user WHERE tournament_id = ? AND user_id = ?');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() ? true : false;
    }
}