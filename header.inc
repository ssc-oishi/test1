<header class="user_header">
	<p class="header_title">案件計画</p>
	<div>
		<a href="logout.php" class="cs-btn-logout">ログアウト</a>
		<?php
			if(isset($_SESSION["kintai_admin_uNo"])){
				echo '<a href="time_schedule_admin_approval.php" class="cs-btn-logout" style="background: #b76e00">上長勤務管理へ</a>';
			}
		?>
	</div>
</header>