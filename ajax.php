<?php  
require_once "db.php";
if (isset($_POST['essayId'])) {
	$essayId = (int)$_POST['essayId'];
	$sel = $con->query("SELECT * FROM essays WHERE Id='$essayId'");
	$sel = $sel->fetch();
	$Title = trim($sel["Essay_title"]);
	$Text = trim($sel["Essay_text"]);
	$response = [
		"Title" => $Title,
		"Text"  => $Text
	];
	echo json_encode($response,true);	
}
if (isset($_POST['subEssayId'])) {
	$essayId = (int)$_POST['subEssayId'];
	$sel = $con->query("SELECT * FROM subessays WHERE Id='$essayId'");
	$sel = $sel->fetch();
	$Title = trim($sel["Title"]);
	$Text = trim($sel["Essay_Text"]);
	$response = [
		"Title" => $Title,
		"Text"  => $Text
	];
	echo json_encode($response,true);
}
if (isset($_POST['writeEssay'])) {
	$Array      = json_decode($_POST['writeEssay'],true);
	$Subject    = trim($Array["subject"]);
	$Title      = trim($Array["title"]);
	$Text       = trim($Array["text"]);
	$SubEssayOf = trim($Array["subEssayOf"]);

	if (strlen($Subject)==0 || strlen($Title)==0 || strlen($Text)==0) {
		echo "Please fill out Subject,Title and Text Fields fields";
		exit();
	}
	//check if an article with same title exists
	//if subessay is empty then add it to Essays database,otherwise to subessays
	if(strlen($SubEssayOf)==0){
		$sel = $con->query("SELECT COUNT(*) AS num FROM essays WHERE Essay_title='$Title'");
		$sel = $sel->fetch();
		$num = $sel["num"];
		if ($num == 1) {
			echo "Essay with same title already exists";
			exit();
		}
		$ins = $con->query("INSERT INTO essays (Subject,Essay_title,Essay_Text) VALUES('$Subject','$Title','$Text')");
	}else{
		$sel = $con->query("SELECT COUNT(*) AS num FROM subessays WHERE title='$Title'");
		$sel = $sel->fetch();
		$num = $sel["num"];
		if ($num == 1) {
			echo "Essay with same title already exists";
			exit();
		}		
		//select Id and Includes_subcategories of the essay where title is equal to SubEssayOf
		$select = $con->query("SELECT Id,Includes_subcategories FROM essays WHERE Essay_title='$SubEssayOf'");
		$select = $select->fetch();
		$Id = $select["Id"];
		if(!$Id){
			echo "Essay with that title doesn't exist";
			exit();
		}
		$ins = $con->query("INSERT INTO subessays (SubCategOf,Title,Essay_Text) VALUES('$Id','$Title','$Text')");
		// if Includes_subcategories is 0 then make it 1 because now it includes subessays
		if($select['Includes_subcategories']==0){
			$upd = $con->query("UPDATE essays SET Includes_subcategories='1' WHERE Id='$Id'");
		}
	}
}
?>