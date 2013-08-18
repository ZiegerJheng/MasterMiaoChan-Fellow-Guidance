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
			/*$(document).ready(function() {
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
			});*/
		</script>
	<div id="newshare" data-role="page" data-theme="a" data-add-back-btn="true">
		<div data-role="header" >
			<h1>預計入門修改</h1>
		</div>
		<div data-role="content">
			<h2>7/18 - 余仲凱(同學)/芷瑄</h2>
			
			<div data-role="collapsible-set" data-content-theme="a">
				<div data-role="collapsible" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" >
					<h2>入門日期修改</h2>
					<form id="form-edit-coming-date">
						<div data-role="fieldcontain">
							<label for="edit-coming-date">改期至:</label>
							<input type="date" name="edit-coming-date" id="edit-coming-date" value="" />
						</div>
						<button id="form-edit-coming-date-submitbutton" type="submit" >完成</button>
					</form>
				</div>
				
				<div data-role="collapsible" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" >
					<h2>取消入門</h2>
					<button id="cancel-coming">取消</button>
				</div>
			</div>
		</div>
	</div>
	</body>
</html>