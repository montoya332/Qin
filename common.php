<?php
require_once("settings.php");

 session_start();

// PHP_DOC
// INPUT: $s as string
// OUTPUT: $s as string
// DESCRIPTION:  Prevents SQL injection attacks
function SQLE($s)
{
	return mysql_real_escape_string($s);

}

// PHP_DOC
// INPUT: $s as string
// OUTPUT: $s as string
// DESCRIPTION:  Prevents SQL injection attacks
function escapeString($s)
{
	return str_replace("'", "\'", $s);
}

// PHP_DOC
// INPUT: $s as string
// OUTPUT: $s as string
// DESCRIPTION: Formats string for viewing
function unescapeString($s)
{
	return str_replace("\'", "'", $s);
}

// PHP_DOC
// INPUT: $s as string
// OUTPUT: $s as string
// DESCRIPTION: **DEPRICATED**
function stripSlashesString($s){
	//$slashes = array ("\", "/");

	//return str_replace($slashes, "", $s);

}



// PHP_DOC
// INPUT: $s as string
// OUTPUT: $s as string
// DESCRIPTION: Filters non-hex characters from string, prevents injection attacks
function filterHexString($s){
	$pattern = "~[^a-fA-F0-9]~";
	$replacement = "";

	return preg_replace($pattern, $replacement, $s);

}

// PHP_DOC
// INPUT: $s as string
// OUTPUT: $s as string
// DESCRIPTION: Filters non-alphanumeric characters from string, prevents injection attacks
function filterAlphaNumericString($s){
        $pattern = "~[^a-zA-Z0-9.]~";
        $replacement = "";

        return preg_replace($pattern, $replacement, $s);  
}

// PHP_DOC
// INPUT: $s as string
// OUTPUT: $s as string
// DESCRIPTION: Filters non-numerical characters, prevents injection attacks
function filterNumberString($s){
        $pattern = "~[^0-9]~";
        $replacement = "";

        return preg_replace($pattern, $replacement, $s);   

}

// PHP_DOC
// INPUT: $s as string
// OUTPUT: $s as string
// DESCRIPTION: Filters all characters except numbers and commas, prevents injection attacks
function filterNumberCSV($s) {
        $pattern = "~[^0-9,]~";
        $replacement = "";

        return preg_replace($pattern, $replacement, $s);  
}


// PHP_DOC
// INPUT: null
// OUTPUT: True on valid session, false otherwise
// DESCRIPTION:
function isUserLoggedIn()
{
  return (isset($_SESSION) && isset($_SESSION['isValid']) && $_SESSION['isValid']);
}



// PHP_DOC
// INPUT: null
// OUTPUT: sess_username on user logged in, "@invalid_u5er!" on failure
// DESCRIPTION:
function getSessionUsername()
{
	if(isUserLoggedIn()) {
		return $_SESSION['sess_username'];
	}
	else {
		return "@invalid_u5er!";
	}
}



// PHP_DOC
// INPUT: null
// OUTPUT: $link as MySQL database link
// DESCRIPTION: Opens the Database Table using the SQL Database Credentials
function openDatabase()
{
	//$db_user = "";
	//$password = "";
	$database = DB_SCHEMA;
	$link = mysql_connect("127.0.0.1:3306", DB_USER, DB_PASSWORD);
	if(!$link)
		die("Unable to connect to database");
	@mysql_select_db($database) or die( "Unable to select database");
	return $link;
}

// PHP_DOC
// INPUT: null
// OUTPUT: null
// DESCRIPTION:  Closes the Database
function closeDatabase()
{
		mysql_close();
}


// PHP_DOC
// INPUT:
// OUTPUT:
// DESCRIPTION:  TODO - ANALYIZE
function performSingleDbQueryGetRow($query)
{
	return mysql_fetch_assoc(mysql_query($query)); // bug (?) wrong syntax
}


// PHP_DOC
// INPUT:   $query as DB query
// OUTPUT:  $count as integer
// DESCRIPTION: Performs Query and returns the #rows returned from the Query
function getQueryCount($query)
{
	$results = mysql_query($query);
	$count   = null==$results ? 0 : mysql_num_rows($results);
	return $count;
}


// PHP_DOC
// INPUT:   $s as string
// OUTPUT:  $s as string
// DESCRIPTION: Get Single Quoted & Escaped SQL string, prevents injection
function sqlQStr($s)
{
	return "'" . SQLE($s) . "'";
}


// PHP_DOC
// INPUT: 
// OUTPUT: 
// DESCRIPTION: Damn single quoted strings for SQL gets your eyes tired!
function sqlArgs1($A1)
{
	return "('" . SQLE($A1) . "')";
}


// PHP_DOC
// INPUT: 
// OUTPUT: 
// DESCRIPTION:
function sqlArgs2($A1, $A2)
{
	return "('" . SQLE(A1) . "', '" . SQLE($A2) . "')";
}


// PHP_DOC
// INPUT: 
// OUTPUT: 
// DESCRIPTION:
function sqlArgs3($A1, $A2, $A3)
{
	return "('" . SQLE($A1) . "', '" . SQLE($A2) . "', '" . SQLE($A3) . "')";
}

?>
