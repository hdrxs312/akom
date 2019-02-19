<?php

$_module 		= 'article';
$_data 			= http($_module . '?' . $params);
$_title 		= get_menu($_module, 'label');

function _content($data, $_com = [], $_user = []) {
	
?>

<!-- START FILE -->
	<div class="edge d-flex w-100 mb-3">
		<button class="btn btn-sm btn-primary" onclick="createData()"><i class="fas fa-plus"></i> Buat artikel baru</button>
		<form action="<?php e(home()) ?>article" class="ml-auto" style="width:150px"><input type="text" name="search" class="form-control my-0" value="<?php e(@$data['search']) ?>" placeholder="cari..."></form>
	</div>

	<?php foreach($data['list'] as $row) { ?>

		<div class="kard">

			<div class="d-flex">
				<div class="thumbnail">
					<img src="<?php files($row['co_id'], 'ar_'.$row['ar_id'].'.jpg' ) ?>">
				</div>
				<div class="snippet w-100">
					<div class="kard-option">
						<button class="btn text-muted"><i class="fas fa-chevron-down"></i></button>
						<div class="dropdown-list" style="display:none">
							<span onclick="updateData(<?php e($row['ar_id']) ?>)" class="dropdown-item">Edit</span>
							<span onclick="deleteData(<?php e($row['ar_id']) ?>)" class="dropdown-item">Delete</span>
						</div>
					</div>
					<h3>
						<a href="<?php e(home().'article/'.$row['ar_id']) ?>" class="text-dark">
							<?php e($row['ar_title']) ?>
						</a>
					</h3>
					<p class="my-3">
						<span class="text-muted small mb-3"><?php e(format_date($row['ar_created_date'])) ?></span>
						&mdash; <?php parseup(substr($row['ar_content'], 0, 120)) ?>
					</p>
					<a class="btn btn-sm btn-light" href="<?php e(home().'member/'.$row['mb_username']) ?>">
						<i class="fas fa-user mr-2"></i>
						<?php e($row['mb_username']) ?></a>
					<button class="btn btn-sm btn-light"><?php e($row['ar_count_comment']) ?> komentar</button>
				</div>
			</div>
		</div>

	<?php } ?>

	<div class="popup-backdrop" style="display:none"></div>
	<div class="popup" id="popup-form" style="display:none">

		<!-- Form Create -->
		<form class="kard" id="create" style="display:none">
			<h6>Artikel baru</h6>
			<label>Judul</label>
			<input type="text" class="form-control" name="ar_title">
			<label>Konten</label>
			<textarea class="form-control" name="ar_content"></textarea>
			<button class="btn btn-primary btn-sm">Submit</button>
			<button type="button" class="btn btn-outline-secondary btn-sm ml-2 cancel-form">Batal</button>
		</form>

		<!-- Form Update -->
		<form class="kard" id="update" data-id="" style="display:none">
			<h6>Edit artikel</h6>
			<label>Judul</label>
			<input type="text" class="form-control" name="ar_title">
			<label>Konten</label>
			<textarea class="form-control" name="ar_content"></textarea>
			<button class="btn btn-primary btn-sm">Submit</button>
			<button type="button" class="btn btn-outline-secondary btn-sm ml-2 cancel-form">Batal</button>
		</form>

		<!-- Form Delete -->
		<form class="kard text-center" id="delete" data-id="" style="display:none">
			<h6>Hapus artikel</h6>
			<label>Data yang terhapus tidak dapat di kembalikan. Lanjutkan?</label><br>
			<button class="btn btn-primary btn-sm">Ya</button>
			<button type="button" class="btn btn-outline-secondary btn-sm ml-2 cancel-form">Tidak</button>
		</form>

	</div>
<!-- END FILE -->
<?php 
}
function _script($data) {
	foreach ($data['list'] as $key => $value) {
		$list[$value['ar_id']] = $value;
	}
	$list = base64_encode(json_encode($list));
?>
	<script type="text/javascript">
		const master = JSON.parse(atob('<?php echo $list; ?>'));
		const pfx = 'ar_';
		const module = 'article';

		function createData() {
			show_form('create');
		}

		function updateData(id=0) {
			if( master[id] != undefined ) {
				$('form#update').attr('data-id', id);
				$('form#update .form-control').val('');
				$('form#update .form-control').each(function() {
					let n = $(this).attr('name');
					if( master[id][n] != undefined) {
						$(this).val(master[id][n]);
					}
				})
				show_form('update');
			}
		}

		function deleteData(id=0) {
			if( master[id] != undefined ) {
				$('form#delete').attr('data-id', id);
				show_form('delete');
			}
		}

		$('form#create').submit(function(e){
			e.preventDefault();
			let url = '<?php e(api()) ?>'+module+'/create';
			let data = $('form#create').serialize();
			ajaxSend(url, data, function(res){
				if(res.status == 1) {
					location.href = '<?php e(home()) ?>'+module;
				}
				if(res.status == 0) {
					alert(res.msg);
				}
			});
		});

		$('form#update').submit(function(e){
			e.preventDefault();
			let id 		= $('form#update').attr('data-id');
			if( master[id] == undefined ) {
				alert('Something wrong!');
				return false;
			}
			let url 	= '<?php e(api()) ?>'+module+'/update/'+id;
			let data 	= $('form#update').serialize();

			ajaxSend(url, data, function(res) {
				if(res.status == 1) {
					location.reload();
				}
				if(res.status == 0) {
					alert(res.msg);
				}
			});
		});

		$('form#delete').submit(function(e){
			e.preventDefault();
			let id 		= $('form#delete').attr('data-id');
			if( master[id] == undefined ) {
				alert('Something wrong!');
				return false;
			}
			let url = '<?php e(api()) ?>'+module+'/delete/'+id;
			let data = $('form#delete').serialize();

			ajaxSend(url, data, function(res){
				if(res.status == 1) {
					location.href = '<?php e(home()) ?>'+module;
				}
				if(res.status == 0) {
					alert(res.msg);
				}
			});
		});

	</script>

<?php
}

require "layout.php";
?>