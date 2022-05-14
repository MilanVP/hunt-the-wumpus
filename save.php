<!--
    Name:           Milankumar Vinubhai Prajapati, 000813839
    Date created:   December 2, 2020
    Description:    This php file will save the user email,
                    date played and outcome of game to leaderboard
                    table.
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leaderboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/wumpus.css">
    <style>
        table, tr, td, th {
            pointer-events: none;
            border: solid 2px black;
            border-collapse: collapse;
        }
        #play_again {
            display: block;
            margin: auto;
            width: 200px;
            height: 30px;
            background: #4E9CAF;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            line-height: 25px;
        }
    </style>
    <title>Save</title>
</head>
<body>
    <div id="container">
    <?php
        date_default_timezone_set('America/Toronto');

        try {
            $dbh = new PDO("mysql:host=localhost;dbname=000813839", "000813839", "19971219");
            } catch (Exception $e) {
            die("ERROR: Couldn't connect. {$e->getMessage()}");
        }

        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $outcome = filter_input(INPUT_POST, "outcome", FILTER_SANITIZE_STRING);
        $date = date('Y-m-d');
        if($email !== null && $outcome !== null && $outcome !== false){
            
            $command = "SELECT email, wins, losses FROM players WHERE email = ?";
            $stmt = $dbh->prepare($command);
            $params = [$email];
            $success = $stmt->execute($params);

            if($received = $stmt->fetch()) {
                if($outcome == "won"){
                    $command = "UPDATE players SET wins = wins + 1 WHERE email = ?";
                    $stmt = $dbh->prepare($command);
                    $success = $stmt->execute($params);
                } else if($outcome = "lost"){
                    $command = "UPDATE players SET losses = losses + 1 WHERE email = ?";
                    $stmt = $dbh->prepare($command);
                    $success = $stmt->execute($params);
                }
            } else if($outcome == "won"){
                $command = "INSERT INTO players VALUES (?, ?, ?, ?)";
                $stmt = $dbh->prepare($command);
                $params = [$email, 1, 0, $date];
                $success = $stmt->execute($params);
            } else if($outcome = "lost"){
                $command = "INSERT INTO players VALUES (?, ?, ?, ?)";
                $stmt = $dbh->prepare($command);
                $params = [$email, 0, 1, $date];
                $success = $stmt->execute($params);
            }

            $command = "SELECT * FROM players ORDER BY wins DESC, losses ASC";
            $stmt = $dbh->prepare($command);
            $success = $stmt->execute();


            echo "<h1>Leaderboard</h1>";
            echo "<table>";
            echo "<tr>";
            echo "<th>Email</th>";
            echo "<th>Wins</th>";
            echo "<th>Losses</th>";
            echo "<th>Date Last Played</th>";
            echo "</tr>";

            $i = 0;
            while(($received = $stmt->fetch()) && $i < 10){
                echo "<tr>";
                echo "<td>$received[email]</td>";
                echo "<td>$received[wins]</td>";
                echo "<td>$received[losses]</td>";
                echo "<td>$received[date_last_played]</td>";
                echo "</tr>";
                $i++;
            }
            echo "</table><br>";
            echo '<a href="index.php" id="play_again">Play Again</a>';

        } else {
            echo "<p>Error in input</p>";
        }
    ?>
    </div>
</body>
</html>