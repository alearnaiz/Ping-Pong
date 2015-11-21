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

    public static function getMyTournaments($user_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT tournament.* FROM tournament_user INNER JOIN tournament ON tournament.id = tournament_user.tournament_id WHERE tournament_user.user_id = ? ORDER BY date_time ASC");
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUpcomingTournaments()
    {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM tournament WHERE date_time > NOW() ORDER BY date_time ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTournamentsByStatus($status)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM tournament WHERE status = ? AND date_time > NOW() ORDER BY date_time ASC");
        $stmt->bindParam(1, $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTournaments()
    {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM tournament ORDER BY date_time ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createTournament($name, $promoter_id, $date_time, $status)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('INSERT INTO tournament (name, promoter_id, date_time, status) VALUES (?, ?, ?, ?)');
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->bindParam(2, $promoter_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $date_time, PDO::PARAM_STR);
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
        $stmt = $conn->prepare('UPDATE tournament SET status = ? WHERE id = ?');
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

    public static function getTournamentByTournamentId($tournament_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('SELECT * FROM tournament WHERE tournament.id = ?');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updatePosition($position, $tournament_id, $user_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('UPDATE tournament_user SET position = ? WHERE tournament_id = ? AND user_id = ?');
        $stmt->bindParam(1, $position, PDO::PARAM_INT);
        $stmt->bindParam(2, $tournament_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getUsersByTournamentIdOrderByRandom($tournament_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('SELECT * FROM user INNER JOIN tournament_user ON user.id = tournament_user.user_id WHERE tournament_user.tournament_id = ? ORDER BY RAND()');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUsersByTournamentIdOrderByPosition($tournament_id)
    {
        $conn = Database::connect();
        $stmt = $conn->prepare('SELECT * FROM user INNER JOIN tournament_user ON user.id = tournament_user.user_id WHERE tournament_user.tournament_id = ? ORDER BY position ASC');
        $stmt->bindParam(1, $tournament_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}