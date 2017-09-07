//first send ajax to get all the essays

function closeParent(parentId){
	let essay_wrapper = document.getElementById(parentId),
	    wrapper = document.getElementById("wrapper");
	hideWithAnimation(essay_wrapper);
	showWithAnimation(wrapper);
}

function openEssay(openId,EssayId,subEssay){
	openDiv(openId);
	//subessays are in different database thus we send different ajax request
	var request = (subEssay) ? "subEssayId="+EssayId : "essayId="+EssayId;
console.log(request);
	sendAjax(request,function(http){
		var response      = http.responseText,
			responseArray = JSON.parse(response);
			title = responseArray["Title"],
			Text = responseArray["Text"];
		document.getElementById("essay_title").innerHTML = title;
		document.getElementById("essay").innerHTML       = Text;
	})
}

function openDiv(openId){
	let wrapper = document.getElementById("wrapper"),
	    open = document.getElementById(openId);
	hideWithAnimation(wrapper);
	showWithAnimation(open);
}

function hideWithAnimation(element){
	let style   = window.getComputedStyle(element),
	    opacity = 1,
	    seconds = setInterval(frame,30);
	function frame(){
		if(opacity <= 0){
			clearInterval(seconds);
		}else{
			opacity-=0.1;
			element.style.opacity = opacity;
		}
	}	
	element.style.display = "none";
}

function showWithAnimation(element){
	let style   = window.getComputedStyle(element),
	    opacity = 0,
	    seconds = setInterval(frame,30);
	element.style.display = "block";
	function frame(){
		if(opacity >= 1){
			clearInterval(seconds);
		}else{
			opacity+=0.1;
			element.style.opacity = opacity;
		}
	}	
}

function insertEssay(){
	let subject    = document.getElementById("subjectName").value,
		title      = document.getElementById("titleEssay").value,
		text       = document.getElementById("essayText").value,
		subEssayOf = document.getElementById("subEssayOf").value,
		params     = {
			//key: value
			subject: subject, 
			title: title,
			text: text,
			subEssayOf: subEssayOf
		};
	sendAjax("writeEssay="+JSON.stringify(params),function(http){
		if (http.responseText.length>0) {
			alert(http.responseText);
		}
	})
}

//ajax requests
function sendAjax(params,onSuccessFunction){
	var http = new XMLHttpRequest();
	var url = "ajax.php";
	http.open("POST", url, true);

	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.onreadystatechange = function() {//Call a function when the state changes.
	    if(http.readyState == 4 && http.status == 200) {
	        onSuccessFunction(http)
	    }
	}
	http.send(params);	
}