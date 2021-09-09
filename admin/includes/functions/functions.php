<?php
/* 
**get All records of DB
*/
function getAllFrom($field,$table, $where = null, $whereValue = null,$and=null,$andv=null, $order = null, $ordering = null ){
    global $conn;

    $cond = $where != null? " WHERE " . $where : null;
    $sql = $whereValue != null? " = " . $whereValue : null;
    $andSql = $and != null? " AND " . $and . " = " . $andv : null;
    $oitem = $order != null? "ORDER BY " . $order : null;
    $statment = $conn->prepare("SELECT $field FROM $table $cond $sql $andSql $oitem $ordering");
	$statment->execute();
	$all = $statment->fetchAll();
    return $all;
}
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
        $link = 'Homepage';
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
function getLatest($select, $table, $order, $limit=5){
    global $conn;
    $statment = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$statment->execute();
	$rows = $statment->fetchAll();
    return $rows;
}
function getImage($id){
    global $conn;
    $statment = $conn->prepare("SELECT Image FROM users WHERE UserID=? LIMIT 1");
    $statment->execute([$id]);
    $rows = $statment->fetch();
    foreach ($rows as $row) {
        return $oldProfile = realpath(dirname(getcwd()))."\admin\uploads\profiles\\". $row;
    }
}

    function getImageItem($id){
    global $conn;
    $statment = $conn->prepare("SELECT Image FROM items WHERE Item_ID=? LIMIT 1");
    $statment->execute([$id]);
    $rows = $statment->fetch();
        foreach ($rows as $row) {
            return $oldProfile = realpath(dirname(getcwd()))."\uploads\images\\". $row;
        }
    }