<?php

require_once 'conn.php';

// Get form data
$parentId = $_POST['parent_id'];
$name = $_POST['name'];

if($name!='')
{
	if($parentId==0)
	{	
		// Insert into database for case if root members
		$stmt = $pdo->prepare("SET FOREIGN_KEY_CHECKS = 0;
							   INSERT INTO members (name) VALUES (:name);
							   SET FOREIGN_KEY_CHECKS = 1");
		$stmt->execute(array(':name'=>$name));

		$arr['response'] = '1';
		$arr['msg'] = "Member '".$name."' added successfully!";
	}
	else
	{
		// Insert into database for case if non root members
		$stmt = $pdo->prepare("INSERT INTO members (parent_id, name) VALUES (:parent_id, :name)");
		$stmt->execute(array(':parent_id'=>$parentId, ':name'=>$name));

		$arr['response'] = '1';
		$arr['msg'] = "Member '".$name."' added successfully!";
	}
}
else{
	$arr['response'] = '0';
	$arr['msg'] = "Please add proper name value.";
}	

echo json_encode($arr);