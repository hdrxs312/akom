<?php
$_module 		= 'member';
$temp_id    = explode('/', $route)[2];
$id 				= explode('?', $temp_id)[0];
$_data 			= http($_module.'/detail/'.$id . '?' . $params);
$_title 		= get_menu($_module, 'label');

function _content($data) {
	$item = $data['detail'];
?>
<!-- START FILE -->
	<div class="kard row ml-0">
		<div class="col-6 text-center">
			<div class="photo">
				<img src="<?php avatar($item['co_id'], $item['mb_id']) ?>" style="height:120px">
			</div>
		</div>
		<div class="col-6 text-center">
			<div class="body mt-3">
				<h3><?php e($item['mb_username']) ?></h3>
				<span class="text-muted">Jabatan: </span> <?php e($item['mb_role']) ?></span><br>
				<span class="text-muted">Status: </span> <?php e($item['mb_status']) ?></span><br>
				<span class="text-muted">Sejak: </span> <?php e(format_date($item['mb_join_date'])) ?></span><br>
			</div>
		</div>
	</div>
	<hr>
	<div class="kard row ml-0">
		<div class="col-6">
			<h3 class="text-muted mt-3">Tentang</h3>
			<span class="text-muted">Propinsi: </span> <?php e($item['mb_address_state']) ?></span><br>
			<span class="text-muted">Kota: </span> <?php e($item['mb_address_city']) ?></span><br>
			<span class="text-muted">Lahir: </span> <?php e($item['mb_birthday']) ?></span><br>
			<span class="text-muted">Gender: </span> <?php e($item['mb_gender']) ?></span><br>
			<span class="text-muted">Hobi: </span> <?php e($item['mb_hobby']) ?></span><br>
		</div>
		<div class="col-6">
			<h3 class="text-muted mt-3">Catatan</h3>
			<div><?php parsedown($item['mb_notes']) ?></div>
		</div>
	</div>
			<?php
				if( count($item['comments']) > 0) {
				foreach ($item['comments'] as $row ) {
			?>
			<div class="kard">
				<div class="body d-flex mb-3">
					<div class="mr-3 py-2">
						<img src="<?php e(home()) ?>assets/img/icon/instagram.svg" style="height:30px">
					</div>
					<div class="right w-100">
						<div class="bg-light p-2">
							<a href="'<?php e(home().'member/'.$row['mb_username']) ?>" class="text-dark">
								<b><?php e(comment['mb_username']) ?></b>
							</router-link>
							<br>
							<span><?php e( comment['cm_content']) ?></span>
						</div>
						<span class="text-muted small btn btn-sm"><?php e( comment['created_date']) ?></span>
						<span class="btn btn-link btn-sm"><?php e( comment['count_reply']) ?> balasan</span>
					</div>
				</div>
			</div>
			<?php
				}
				}
			?>
<!-- END FILE -->
<?php 
}
require "layout.php";
?>