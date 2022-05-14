<!--
    Name:           Milankumar Vinubhai Prajapati, 000813839
    Date created:   December 2, 2020
    Description:    This is a php file which will show the result
                    which will display a message with image depending
                    on user won or lost.
-->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/wumpus.css">
    <title>Result</title>
</head>
<body>
    <div id="container">
        <?php
            try {
                $dbh = new PDO("mysql:host=localhost;dbname=000813839", "000813839", "19971219");
                } catch (Exception $e) {
                die("ERROR: Couldn't connect. {$e->getMessage()}");
            }

            $outcome = "lost";
            $row = filter_input(INPUT_GET, "row", FILTER_VALIDATE_INT);
            $col = filter_input(INPUT_GET, "col", FILTER_VALIDATE_INT);

            if($row !== null && $col !== null && $row !== false && $col !== false){

                $params = [$row, $col];

                $command = "SELECT my_row, my_col FROM wumpuses WHERE my_row = ? AND my_col = ?";
                $stmt = $dbh->prepare($command);
                $success = $stmt->execute($params);

                if($received = $stmt->fetch()){
                    if($received["my_row"] == $row && $received["my_col"] == $col){
                        echo '<p>Wumpus found</p><br><img src="img/win.png" alt="Image of Wumpus">';
                        $outcome = "won";
                    }
                } else {
                    echo '<p>Wumpus not found</p><br><img src="img/lost.png" alt="Image of Wumpus">';
                }

            } else {
                echo "<p>Error in input</p>";
            }
        ?>

        <form method="POST" action="save.php">
            
            <br><br>
            <input type="hidden" name="outcome" value="<?=$outcome?>">
            
            <label for="emailaddress">Email Address:</label>
            <br>
            <input type="email" name="email" id="emailaddress" placeholder="name@example.com" maxlength="40">
            <br><br>
            <input type="submit" value="Submit">

        </form>
        
    </div>
</body>
</html>