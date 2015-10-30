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
        $stmt->execute();
        return $conn->lastInsertId();
    }

    public static function getActiveTournaments()
    {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM tournament WHERE date > NOW() ORDER BY date ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createTournament($name, $promoter_id, $date, $status)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('INSERT INTO tournament (name, promoter_id, date, status) VALUES (?, ?, ?, ?)');
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->bindParam(2, $promoter_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $date, PDO::PARAM_STR);
        $stmt->bindParam(4, $status, PDO::PARAM_STR);
        $stmt->execute();
        return $conn->lastInsertId();
    }

    public static function getUsersByTournamentId($tournament_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('SELECT * FROM user INNER JOIN tournament_user ON user.id = tournament_user.user_id WHERE tournament_user.tournament_id = ?');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function registerUserInTournament($tournament_id, $user_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('INSERT INTO tournament_user (tournament_id, user_id) VALUES (?, ?)');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function deregisterUserFromTournament($tournament_id, $user_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('DELETE FROM tournament_user WHERE tournament_id = ? AND user_id = ?');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function isUserRegisteredInTournament($tournament_id, $user_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM tournament_user WHERE tournament_id = ? AND user_id = ?');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() ? true : false;
    }

    public static function countUsersByTournamentId($tournament_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM tournament_user WHERE tournament_id = ?');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function updateTournamentStatus($tournament_id, $status)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('UPDATE FROM tournament SET status = ? WHERE tournament_id = ?');
        $stmt->bindParam(1, $status, PDO::PARAM_STR);
        $stmt->bindParam(2, $tournament_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getPromoterByTournamentId($tournament_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('SELECT user.* FROM user INNER JOIN tournament ON user.id = tournament.promoter_id
          WHERE tournament.id = ?');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}