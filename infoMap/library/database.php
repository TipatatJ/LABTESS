<?php
error_reporting(E_ERROR & ~E_NOTICE);
require_once 'config.php';

//mysqli_report(MYSQLI_REPORT_ALL);
//$dbConn = mysql_connect ($dbHost, $dbUser, $dbPass) or die ('MySQL connect failed. ' . mysql_error());


class DBi {
    public static $conn;
}
@DBi::$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
//จริงq ใช่แค่ DBi::$conn ก็พอ แต่ต้องมี @DBi เพราะไม่ให้ Server Show Warning
//$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
DBi::$conn->set_charset('utf8');

if (DBi::$conn->connect_errno) {
    // The connection failed. What do you want to do? 
    // You could contact yourself (email?), log the error, show a nice page, etc.
    // You do not want to reveal sensitive information

    // Let's try this:
    echo "Sorry, this website is experiencing problems.";

    // Something you should not do on a public site, but this example will show you
    // anyways, is print out MySQL error related information -- you might log this
    echo "Error: Failed to make a MySQL connection, here is why: \n";
    echo "Errno: " . DBi::$conn->connect_errno . "\n";
    echo "Error: " . DBi::$conn->connect_error . "\n";
    
    // You might want to show them something nice, but we will simply exit
    exit;
}

//mysql_select_db($dbName) or die('Cannot select database. ' . mysql_error());
//mysql_query("SET NAMES UTF8");

if (!DBi::$conn->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", DBi::$conn->error);
    exit();
} else {
    //printf("Current character set: %s\n", DBi::$conn->character_set_name());
}


function dbQuery($sql, $errDesc = '')
{
	//$result = mysql_query($sql) or die(mysql_error());
	try {
        if (!$result = DBi::$conn->query($sql)) {
            
            if(!$errDesc){
                // Oh no! The query failed. 
                echo "Sorry, the website is experiencing problems.";

                // Again, do not do this on a public site, but we'll show you how
                // to get the error information
                echo "Error: Our query failed to execute and here is why: \n";
                echo "Query: " . $sql . "\n";
                echo "Errno: " . DBi::$conn->errno . "\n";
                echo "Error: " . DBi::$conn->error . "\n";
            }
            else
            {
                echo $errDesc ."\n";
            }
            
            
            exit;
        }
        else
        {
            //echo "Connection succeeded.\n";
        }
    } catch (Exception $e){
        echo $e->getMessage();
    }
    
	return $result;
}

function dbAffectedRows()
{
	global $dbConn;
	
    return DBi::$conn->affected_rows;
	//return mysqli_affected_rows($dbConn);
}

function dbFetchArray($result, $resultType = MYSQL_NUM) {
    return $result->fetch_array($resultType);
	//return mysql_fetch_array($result, $resultType);
}

function dbFetchAssoc($result)
{
    return $result->fetch_assoc();
	//return mysql_fetch_assoc($result);
}

function dbFetchRow($result) 
{
    return $result->fetch_row();
	//return mysql_fetch_row($result);
}

function dbFreeResult($result)
{
	return mysql_free_result($result);
}

function dbNumRows($result)
{
    return $result->num_rows;
	//return mysql_num_rows($result);
}

function dbSelect($dbName)
{
	return mysql_select_db($dbName);
}

function dbInsertId()
{
    return DBi::$conn->insert_id;
	//return mysql_insert_id();
}
?>