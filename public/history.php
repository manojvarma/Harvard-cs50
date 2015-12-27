<?php
    // configuration
    require("../includes/config.php");  
    
    // query cash for template
    $cash =	query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);	
    
	// create new array to store all info for history table
    $table = query("SELECT * FROM history WHERE id = ?", $_SESSION["id"]);
    
    // render sell form
    render("userhistory.php", ["title" => "History", "cash" => $cash, "table" => $table]);
   
?>
