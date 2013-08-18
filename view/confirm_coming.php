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
			<h1>回報入門情形</h1>
		</div>
		<div data-role="content">
			<h2>蘇姵珊(朋友)/庭慧</h2>
			
			<div data-role="collapsible-set" data-content-theme="a">
				<div data-role="collapsible" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" >
					<h2>確認入門</h2>
					<form id="form-confirm-coming">
						<div data-role="fieldcontain">
							<label for="coming-date">入門日期:</label>
							<input type="date" name="coming-date" id="coming-date" value="" />
						</div>
						<button id="form-confirm-coming-submitbutton" type="submit" >完成</button>
					</form>
				</div>
				
				<div data-role="collapsible" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" >
					<h2>入門延期</h2>
					<form id="form-postpone-coming-date">
						<div data-role="fieldcontain">
							<label for="postpone-coming-date">延期至:</label>
							<input type="date" name="postpone-coming-date" id="postpone-coming-date" value="" />
						</div>
						<button id="form-postpone-coming-date-submitbutton" type="submit" >完成</button>
					</form>
				</div>
				
				<div data-role="collapsible" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" >
					<h2>待確定或暫無入門意願</h2>
					<button id="coming-waiting-confirm">待確定</button>
					<button id="not-coming">暫無入門意願</button>
				</div>
			</div>
		</div>
	</div>
	</body>
</html>