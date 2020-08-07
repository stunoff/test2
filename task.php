<?php

function load_users_data($user_ids)
{
    $user_ids = explode(',', $user_ids);
    $db  = mysqli_connect("192.168.56.101", "root", "E398x54a", "zandstra");
    
    foreach ($user_ids as $user_id) {
        $sql = mysqli_query($db, "SELECT * FROM users WHERE id = $user_id");
        # sql injection -> 1 union select * from (select user()) as a join (select '<script type="text/javascript">console.log(\'hello world\');</script>') as b join (select null) as c join (select 1) as d
        # которая даст нам stored xss
        
        while ($obj = $sql->fetch_object()) {
            $data[$user_id] = $obj->name;
        }
    }
    
    mysqli_close($db);

    return $data;
}

$data = load_users_data($_GET['user_ids']);

foreach ($data as $user_id => $name) {
    echo "<a href=\"/show_user.php?id=$user_id\">$name</a>"; #и тут xss с помощью sql injection
}