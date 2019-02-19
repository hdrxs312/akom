<?php
$_module 		= 'member';
$_data 			= http($_module . '?' . $params);
$_title 		= get_menu($_module, 'label');

function _content($data, $_com = [], $_user = []) {
?>
<!-- START FILE -->
	<?php foreach($data['list'] as $row) { ?>

		<div class="twothree">
			<div class="kard">
				<div class="body d-flex">
					<img src="<?php avatar($row['co_id'], $row['mb_id']) ?>" style="height:40px">
					<span class="ml-3">
						<a href="<?php e(home().'member/'.$row['mb_username']) ?>" class="text-dark">
							<b><?php e($row['mb_username']) ?></b>
						</a>
						<br>
						<span class="text-muted small"><?php e($row['mb_role']) ?></span>
					</span>
				</div>
			</div>
		</div>
	<?php } ?>
<!-- END FILE -->
<?php 
}
require "layout.php";
?>