

			$(document).ready(function(){
	
			//hide reactions by default
			$(".all-reaction").hide();
		
			
			$(document).mouseup(function(e){
			if($(e.target).closest(".all-reaction").length ===0){
				$(".all-reaction").hide();
			}
			});
		
			//show reactions when like btn is hovered
			$(".react-con").click(function(){
			var id = this.id;
			$("#react_"+id).show("slow");
			
			
			//when a react icon is clicked 
			$(".reaction").off().click(function(){
		
			var reactId = this.id;		//get the react id
			var splitId = reactId.split("_");	//split the value
			var reactType = splitId[0];				//get the first array element
			var postId = splitId[1];				//get the first array element
			
			//send react values to like.php
			$.ajax({
				type: "POST",
				url: "like.php",
				data: {reactType:reactType, postId:postId},
				success: function(data){
				$("#counter_"+postId).html(data);
				
				//location to react image icon
				var reactImg  = "<center><img src='images/"+reactType+".png' style='width:40px; height:40px;'></center>"
					
					//replace the glyphicon thumb icon with the user reacted icon
					$("#"+postId).html(reactImg);
					
				//switch icon background based on clicked reaction
				if(reactType =="thumb"){
				$("#"+postId).css("background","#e8e8ff");
				}else if(reactType =="love"){
				$("#"+postId).css("background","#ffdddd");
				}else if(reactType =="haha"){
				$("#"+postId).css("background","#fff7d8");
				}else if(reactType =="wow"){
				$("#"+postId).css("background","#fff7d8");
				}else if(reactType =="sad"){
				$("#"+postId).css("background","#fff7d8","padding","2px");
				}else if(reactType =="angry"){
				$("#"+postId).css("background","#ffdddd");
				}
					
				}
			});
		
				//hide react container
				$(".all-reaction").hide();	
			});
			});
		
			});
			
