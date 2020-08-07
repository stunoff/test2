<?php

/**
 * @return PDO
 */
function db_connect() {
    return new PDO("mysql:host=localhost;dbname=database", 'root', '123123');
}

/**
 * Load user data from db by user ids
 *
 * @param string $user_ids. String must contains comma separated user ids
 *
 * @return array
 */
function load_users_data(string $user_ids) : array {
    $pdo = db_connect();
    $user_ids = explode(",", $user_ids);
    $user_ids = array_filter(
        $user_ids,
        function ($value) {
            return $value !== '';
        }
    );
    
    $inQuery = implode(',', array_fill(0, count($user_ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id IN ($inQuery)");
    
    foreach ($user_ids as $k => $id) {
        $stmt->bindValue(($k+1), $id);
    }
    
    $stmt->execute();
    
    $data = $stmt->fetchALL(PDO::FETCH_ASSOC);
    
    return $data;
}

$data = load_users_data($_GET['user_ids']);
foreach ($data as $user) {
    $id = (int) $user['id'];
    $name = htmlspecialchars($user['name']);
    if empty($id) continue;
    echo "<a href=\"/show_user.php?id={id}\">{name}</a><br>";
}