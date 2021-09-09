<?php
/* 
**get All records of DB
*/
function getAllFrom($field, $table, $where = null, $whereValue = null, $and=null, $andv=null, $order = null, $ordering = null ){
    global $conn;

    $cond = $where != null? " WHERE " . $where : null;
    $sql = $whereValue !== null? " = " . $whereValue : null;
    $andSql = $and != null? " AND " . $and . " = " . $andv : null;
    $oitem = $order != null? "ORDER BY " . $order : null;
    $statment = $conn->prepare("SELECT $field FROM $table $cond $sql $andSql $oitem $ordering");
	$statment->execute();
	$all = $statment->fetchAll();
    return $all;
}

/* 
**get category records of DB
*/
function getCat(){
    global $conn;
    $statment = $conn->prepare("SELECT * FROM categories ORDER BY ID ASC");
	$statment->execute();
	$cats = $statment->fetchAll();
    return $cats;
}
/* 
**check If user isn't activated
*/
function checkUserStatus($user){
    global $conn;
    $stmt=$conn->prepare("SELECT Username FROM users WHERE Username=? AND RegStatus = 0");
        $stmt->execute(array($user));
        $status=$stmt->rowCount();
        return $status;
}
/* 
**get items records of DB
*/
function getItems($where,$value,$approve=null ){
    global $conn;
    $sql = $approve==null? 'AND Approve = 1' : "";
    $statment = $conn->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID ASC");
	$statment->execute(array($value));
	$cats = $statment->fetchAll();
    return $cats;
}

/* 
**to get title in page
*/
function getTitle()
{
	global $pageTitle;
	if (isset($pageTitle)) {
		echo $pageTitle;
	} else {
		echo "Default";
	}
}
/* 
**functio for page redirection v2.0
**we use http refferres to back to the previous page
*/
function redirectPage($theMsg, $url=null, $seconds = 3)
{
	if ($url === null) {
		$url = 'index.php';
		$link='Homepage';
	}else {
		if (isset($_SERVER['HTTP_REFERER'])&& !empty($_SERVER['HTTP_REFERER'])) {
			$url = $_SERVER['HTTP_REFERER'];
			$link='previous page';
		}else {
			$url = 'index.php';
			$link = 'Homepage';
		}
	
	}
	echo $theMsg;
	echo "<div class='alert alert-info'>you will be directed to $link afterb $seconds seconds</div>";
	header("refresh:$seconds;url=$url");
	exit();
}
/*
** checking if record exists in database
*/
function CheckDB($selectedData, $table, $value)
{
	global $conn;
	$statment = $conn->prepare("SELECT $selectedData FROM $table WHERE $selectedData=?");
	$statment->execute(array($value));
	$count = $statment->rowCount();
	return $count;
}
/* 
**function for count Items rows 
*/
function countItems($items,$table){
	global $conn;
	$statment = $conn->prepare("SELECT COUNT($items) FROM $table");
	$statment->execute();
	return $statment->fetchColumn();
}
/* 
**get latest number of records of DB
*/
function getLatest($select, $table, $order, $limit=5){
    global $conn;
    $statment = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$statment->execute();
	$rows = $statment->fetchAll();
    return $rows;
}

