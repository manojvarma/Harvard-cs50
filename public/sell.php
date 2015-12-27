<?php
    
    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $rows =	query("SELECT * FROM pf WHERE id = ?", $_SESSION["id"]);	
        // create new array to store stock symbols
        $stocks = [];
        // for each of user's stocks
        foreach ($rows as $row)	
        {   
            // save stock symbol
            $stock = $row["symbol"];
            
            // add stock symbol to the new array
            $stocks[] = $stock;       
        }    
        // render sell form
        render("sellstock1.php", ["title" => "Sell Form", "stocks" => $stocks]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
      
      //transaction type
      $transaction = 'SELL';
      //no of shares
       $ns = query("SELECT * FROM pf WHERE id = ? AND symbol = ?", $_SESSION["id"],$_POST["symbol"]);
      //cost of single share
       $cc = lookup($_POST["symbol"]);
       
       $shares=$ns[0]["shares"];
     //total cost of selling share
       $tc=number_format(($ns[0]["shares"])*($cc["price"]),2, '.', '');
      // to acheive atomicity
      //render("dump.php",["variable"=>"$tc"]);
      query("DELETE FROM pf WHERE id = ? AND symbol = ?" ,$_SESSION["id"],$_POST["symbol"]);
  

       //$row = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
       //$cash=$row[0]["cash"];
       //render("dump.php",["variable"=>"$cash"]);
      //query("")
      query("UPDATE users SET cash = cash + ? WHERE id = ?",$tc,$_SESSION["id"]) ;
      
      query("INSERT INTO history (id, transaction, symbol, shares, price) VALUES (?, ?, ?, ?, ?)", $_SESSION["id"], $transaction, $_POST["symbol"], $shares, $cc["price"]);
        
      redirect("index.php");
          
    }
    
?>

