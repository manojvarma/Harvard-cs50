<?php

    // configuration
    require("../includes/config.php"); 

    $rows = query("SELECT * FROM pf WHERE id = ?", $_SESSION["id"]);
    
    $positions = [];
    $spent=0;
    foreach ($rows as $row)
    {   
    $stock = lookup($row["symbol"]);
    
    if ($stock !== false)
    {
        //$spent=$spent+number_format($stock["price"]*$row["shares"],2, '.', '');
        $positions[] = [
            "name" => $stock["name"],
            "price" => $stock["price"],
            "shares" => $row["shares"],
            "symbol" => $row["symbol"],
            "total"=> number_format($stock["price"]*$row["shares"],2, '.', '')
        ];
    }
    }

    $row = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
    $cash=$row[0]["cash"];
    //query("UPDATE users SET cash = cash - ? WHERE id = ?", $spent, $_SESSION["id"]);
    // render portfolio
    render("portfolio.php", ["positions" => $positions, "title" => "Portfolio","m"=>$cash]);
    //render("quote_form.php",["title" => "Quote"]);
    

?>

