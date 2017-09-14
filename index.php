<?php  
require_once "db.php";
// first select subjects for divs at the bottom
$sel = $con->query("SELECT Subject FROM essays GROUP BY Subject");
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Knowledge Base</title>
		<link rel="stylesheet" href="main.css">
		<script src="script.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<h1 class="science_base">Knowledge Base</h1>
			<input type="button" class="write_an_essay" onclick="openDiv('write_an_essay_div')" value="Write an essay">
			<?php
				while ($row = $sel->fetch()) {
					$Subject = $row["Subject"];
					?>
					<div class="subject_d">
						<div class="subject_name">
							<span><?php  echo $Subject;?></span>
						</div>
						<div class="essays">
							<ul>
							<?php  
								//select all essays from this Subject
								$essays = $con->query("SELECT Id,Essay_title,Includes_subcategories FROM essays WHERE Subject='$Subject'");
								while ($essay = $essays->fetch()) {
									$EssayId = $essay["Id"];
									?>
									<li onclick="openEssay('essay_wrapper',<?php echo $EssayId ?>,false)"><?php echo $essay["Essay_title"]; ?></li><br />
										<?php  
											if ($essay['Includes_subcategories']==1) {
												?>
												<ul>
													<?php  
														$selectSubcateg = $con->query("SELECT Id,Title FROM subessays WHERE SubCategOf='$EssayId'");
														while($subc = $selectSubcateg->fetch()){
															$subId = $subc["Id"];
															?>
															<li onclick="openEssay('essay_wrapper',<?php echo $subId; ?>,true)"><?php echo $subc["Title"]; ?></li><br />
															<?
														}
													?>
												</ul>
												<?
											}
										?>
									</li>
									<?
								}
							?>
							</ul>
						</div>
					</div>
					<?php
				}
			?>			
		</div>
		<div id="essay_wrapper">
			<span class="back" onclick="closeParent('essay_wrapper')">Back</span>
			<span class="essay_title" id="essay_title"></span><br>
			<span class="essay" id="essay"></span>
		</div>
		<div id="write_an_essay_div">
			<span class="back essayWrite" onclick="closeParent('write_an_essay_div')">Back</span><br>
			<input type="text" placeholder="Subject" id="subjectName">
			<input type="text" placeholder="Title" id="titleEssay">
			<input type="text" placeholder="Subessay of" id="subEssayOf">
			<textarea id="essayText" placeholder="Text"></textarea>
			<input type="button" class="insert_essay" onclick="insertEssay()" value="Insert">
		</div>	
	</body>
</html>	
