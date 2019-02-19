<?php
$_module 		= 'home';
$_title 		= 'Beranda';
$_data 			= http($_module);

function _content($data) {
?>
<!-- START FILE -->
	<?php foreach($data['data'] as $row) { ?>

			<div class="kard">
				<div class="body">
					<p class="d-flex" style="align-items:center">
						<img src="<?php e(home()) ?>assets/img/icon/instagram.svg" style="height:40px">
						<span class="ml-3">
							<a href="'<?php e(home().'member/'.$row['mb_username']) ?>" class="text-dark">
								<b><?php e($row['mb_username']) ?></b>
							</a>
							<br>
							<span class="text-muted small"><?php e($row['created_date']) ?></span>
						</span>
						<button class="btn ml-auto bg-white" onclick="showOption(<?php e($row['dc_id'])?>)">
							<i class="fas fa-ellipsis-h"></i>
						</button>
						<div v-show="show_option[i]" class="dropdown" style="display:none">
							<div class="dropdown-list">
								<a @click="editData(i);showOption(i)" class="dropdown-item">Edit</a>
								<a @click="editData(i);showOption(i)" class="dropdown-item">Another action</a>
								<a @click="editData(i);showOption(i)" class="dropdown-item">Something else here</a>
							</div>
						</div>
					</p>
					<a href="<?php e(home().'discuss/'.$row['dc_id']) ?>" class="text-dark">
						<p>
							<?php echo nl2br($row['dc_content']); ?>
						</p>
					</a>
				</div>
				<div class="footer border-top text-muted small pt-3">
					<a href="<?php e(home().'discuss/'.$row['dc_id']) ?>" class="btn btn-light btn-sm">
						<?php e($row['count_comment']) ?> Komentar
					</a>
				</div>
			</div>

	<?php } ?>
<!-- END FILE -->
<?php 
}
require "layout.php";
?>