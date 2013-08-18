<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>存菁組每日分享接引回報統整平台</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
		<link rel="stylesheet" href="themes/MasterMiaoChan-Fellow-Guidance.min.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
	</head>
	<body>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#newshare-form-submitbutton").click(function(){
					var formData = $("#newshare-form-add").serialize();
 
					$.ajax({
						type: "POST",
						url: "http://localhost:8080/MasterMiaoChan-Fellow-Guidance/model/newposttest.php",
						cache: false,
						data: formData,
						success: function(data, status) {
							data = $.trim(data);
							$("#notification").text(data);
							
							//$("#notification").hide().fadeIn();
							//$("#notification").delay(1000).fadeOut();
							
							$("#notification").popup("open");
							setTimeout(function(){
								$("#notification").popup("close");
							}, 1000);
							
							$("#newshare-add-result-list").append("<li>SS</li>");
						},
						complete: function() {
							$("#newshare-add-result-list").listview('refresh');
						}
					});
	 
					return false;
				});
			});
		</script>
	<div id="newshare" data-role="page" data-theme="a" data-add-back-btn="true">
		<div data-role="header" >
			<h1>新增每日分享</h1>
		</div>
		<div data-role="content">
			<div data-role="popup" id="notification" class="ui-content"></div>
			<form id="newshare-form-add">
				<div data-role="fieldcontain">
					<label for="report-event-date">回報日期:</label>
					<input type="date" name="report-event-date" id="report-event-date" value="<?php echo date('Y-m-d'); ?>" />
					
					<label for="name">新朋友:</label>
					<input type="text" name="name" id="name" value="" />
				
					<label for="relationship">關係:</label>
					<input type="text" name="relationship" id="relationship" value="" />
				
					<label for="sharer">分享人:</label>
					<input type="text" name="sharer" id="sharer" value="" />
				
					<label for="share-event-date">預計參加分享會:</label>
					<input type="date" name="share-event-date" id="share-event-date" value="" />
				
					<label for="coming-date">預計入門法會:</label>
					<input type="date" name="coming-date" id="coming-date" value="" />
				
					<label for="note">備註:</label>
					<textarea name="textarea" name="note" id="note"></textarea>
				</div>
				<button id="newshare-form-submitbutton" type="submit" >完成</button>
			</form>
			
			<hr />
			<h2>已新增列表</h2>
			<ul id="newshare-add-result-list" data-role="listview" data-inset="true" >
			</ul>
		</div>
	</div>
	</body>
</html>