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
	<div id="home" data-role="page" data-theme="a">
		<div data-role="header" >
			<h1>存菁組每日分享接引回報統整平台</h1>
		</div>
		<div data-role="content">
			<h2>7/10每日分享統整</h2>
			<ul data-role="listview" data-inset="true" data-divider-theme="a" >
				<li data-role="list-divider">每日分享: 2位</li>
				<li>
					<h3>承妘</h3>
					<p>詹佳菁(同事)，測試(同學)</p>
				</li>
				<li>
					<h3>芷瑄</h3>
					<p>蔡宗良(lab朋友)</p>
				</li>
				<li data-role="list-divider">最新入門法會回報</li>
				<li>
					<h3>07/13 蔡宗良(lab朋友)/芷瑄</h3>
					<p>台北精舍</p>
					<p>我是備註我是備註我是備註</p>
				</li>
				<li>
					<h3>07/13 詹佳菁(同事)/承妘</h3>
					<p>台北精舍</p>
				</li>
			</ul>
			<hr />
			
			<h2>今日分享會與入門法會</h2>
			<ul data-role="listview" data-inset="true" data-divider-theme="a" >
				<li data-role="list-divider">今日參加分享會: 2位</li>
				<li><a href="confirm_share_event.php">
					<h3>劉碧禎(前同事)/麗英</h3>
					<p>台北精舍</p>
				</a></li>
				<li><a href="confirm_share_event.php">
					<h3>陳曉露(陸委會同事)/嘉萍</h3>
					<p>台北精舍</p>
				</a></li>
				<li data-role="list-divider">今日入門法會: 2位</li>
				<li><a href="confirm_coming.php">
					<h3>蘇姵珊(朋友)/庭慧</h3>
					<p>台北精舍</p>
					<p>我是備註我是備註我是備註</p>
				</a></li>
				<li><a href="confirm_coming.php">
					<h3>蕭伊廷(朋友)/庭慧</h3>
					<p>台北精舍</p>
				</a></li>
			</ul>
			<hr />
			
			<h2>預計參加分享會: 2位</h2>
			<ul data-role="listview" data-inset="true" data-divider-theme="a" >
				<li data-role="list-divider">6/18(二) <span class="ui-li-count">1</span></li>
				<li><a href="edit_share_event.php">
					<h3>劉碧禎(前同事)/麗英</h3>
					<p>台北精舍</p>
				</a></li>
				<li data-role="list-divider">6/22(六) <span class="ui-li-count">1</span></li>
				<li><a href="edit_share_event.php">
					<h3>陳曉露(陸委會同事)/嘉萍</h3>
					<p>台北精舍</p>
				</a></li>
			</ul>
			<hr />
			
			<h2>預計入門法會: 3位</h2>
			<ul data-role="listview" data-inset="true" data-divider-theme="a" >
				<li data-role="list-divider">6/25(二) <span class="ui-li-count">3</span></li>
				<li><a href="edit_coming.php">
					<h3>何雨潔/玟慈</h3>
					<p>台北精舍</p>
				</a></li>
				<li><a href="edit_coming.php">
					<h3>余仲凱/芷瑄</h3>
					<p>台北精舍</p>
				</a></li>
				<li><a href="edit_coming.php">
					<h3>鄭秀英/美蕙</h3>
					<p>台北精舍</p>
				</a></li>
			</ul>
			<hr />
			
			<h2>輔導組統整</h2>
			<div data-role="collapsible-set" >
				<div data-role="collapsible" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" >
					<h3>All輔導組</h3>
					<ul data-role="listview" data-inset="true" >
						<li>本週</li>
						<li>上週</li>
						<li>本月</li>
						<li>上月</li>
					</ul>
				</div>
				<div data-role="collapsible" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d" >
					<h3>單一同修</h3>
					<ul data-role="listview" data-inset="true" >
						<li>黃玟慈</li>
						<li>鄭世宏</li>
						<li>俞力平</li>
						<li>吳美琪</li>
					</ul>
				</div>
			</div>
			<hr />
			
			<h2>新增每日分享</h2>
			<a href="newshare.php" data-role="button" data-icon="arrow-r" data-iconpos="right" data-transition="fade" data-rel="dialog" >新增每日分享</a>
		</div>
		
	</div>
</html>