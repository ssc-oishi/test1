<?php
	$p = 75;
?>

<!--サイドメニュー-->
<div>
	<ul>
		<li class="side_memu_title">
			<div>メニュー１</div>
		</li>
		<li class="side_memu_contents <?php if(PHPName == "project_info.php"){echo 'active_memu';} ?>">
			<a href="project_info.php">プロジェクト情報</a>
		</li>
		<li class="side_memu_contents <?php if(PHPName == "project_regist.php"){echo 'active_memu';} ?>">
			<a href="project_regist.php">新規計画</a>
		</li>
		<li class="side_memu_contents <?php if(PHPName == "matter_construction_search.php"){echo 'active_memu';} ?>">
			<a href="matter_construction_search.php">案件・工事検索</a>
		</li>
	</ul>
	<div id="memu_button">
		<button id="btn_logout" class="btn_common_style" rel="Logout.php">ログアウト</button>
	</div>
</div>