<?php
    
    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("buystock1.php");
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {


         //cheching weather submitted no of shares is valid
         if(preg_match("/^\d+$/", $_POST["shares"]))
         {
          //no of shares
       $ns = $_POST["shares"];
         }
         else
         {
         apologize("Enter valid shares number");
         }
         
       $transaction = 'BUY';       
       //share which user wants to buy 
       $sb=strtoupper($_POST["symbol"]);
      //cost of single share
       $cc = lookup($sb);
     //total cost of buying share
       $tc=number_format(($ns)*($cc["price"]),2, '.', '');
      // to acheive atomicity
      //render("dump.php",["variable"=>"$tc"]);
      //$row = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
      // query to check how much cash user has
        $cash =	query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
       if($tc<=$cash) 
       {
       query("INSERT INTO pf (id, symbol, shares) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE shares = shares + VALUES(shares)",$_SESSION["id"],$sb,$_POST["shares"]);
        
        query("UPDATE users SET cash = cash - ? WHERE id = ?", $tc, $_SESSION["id"]);
       
       // update history
       query("INSERT INTO history (id, transaction, symbol, shares, price) VALUES (?, ?, ?, ?, ?)", $_SESSION["id"], $transaction, $sb, $ns, $cc["price"]);
      
            redirect("index.php");
      
       }
       
       else
        {
        apologize("you does not have enough amount");
        }     
        //redirect("index.php");
          
    }
    
?>
