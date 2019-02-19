<?php

$_module 		= 'file';
$_data 			= http($_module . '?' . $params);
$_title 		= get_menu($_module, 'label');

function _content($data, $_com = [], $_user = []) {
?>

<!-- START FILE -->
	<div class="edge d-flex w-100 mb-3">
		<button class="btn btn-sm btn-primary" onclick="uploadFile()"><i class="fas fa-plus"></i> Upload file</button>
	</div>


	<?php foreach($data['list'] as $row) {	?>

		<div class="twothree">
			<div class="kard text-center">
				<div class="photo">
					<img src="<?php files($row['co_id'], $row['fl_name']) ?>" style="height:120px">
				</div>
				<div class="body">
					<a href="<?php e(home().'file/'.$row['fl_id']) ?>" class="text-dark"><?php e($row['fl_name']) ?></a>
				</div>
			</div>
		</div>
		
	<?php } ?>

	<div class="popup-backdrop" style="display:none"></div>
	<div class="popup" id="popup-form" style="display:none">

		<!-- Form Create -->
		<form class="kard" id="upload" style="display:none" method="post" enctype="multipart/form-data">
			<h6>Upload</h6>
			<label>Files</label>
			<input type="file" class="form-control" name="upload">
			<button class="btn btn-primary btn-sm">Submit</button>
			<button type="button" class="btn btn-outline-secondary btn-sm ml-2 cancel-form">Batal</button>
		</form>

		<!-- Form Update -->
		<form class="kard" id="update" data-id="" style="display:none">
			<h6>Edit diskusi</h6>
			<label>Konten</label>
			<textarea class="form-control" name="upload"></textarea>
			<button class="btn btn-primary btn-sm">Submit</button>
			<button type="button" class="btn btn-outline-secondary btn-sm ml-2 cancel-form">Batal</button>
		</form>

		<!-- Form Delete -->
		<form class="kard text-center" id="delete" data-id="" style="display:none">
			<h6>Hapus diskusi</h6>
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
		$list[$value['fl_id']] = $value;
	}
	$list = base64_encode(json_encode($list));
?>
	<script type="text/javascript">
		const master = JSON.parse(atob('<?php echo $list; ?>'));
		const pfx = 'fl_';
		const module = 'files';

		function uploadFile() {
			show_form('upload');
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

		$('form#upload').submit(function(e){
			e.preventDefault();
			let url = '<?php e(api()) ?>file/upload';
			let form = $('form#upload').get(0);
			let form2 = $('form#upload');
			console.log(1,form);
			console.log(2,form2);
			let data = new FormData(form);
			ajaxSendUpload(url, data, function(res){
				if(res.status == 1) {
					// location.href = '<?php e(home()) ?>file';
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

		$('.cancel-form').click(function(){
			hide_form();
		});

	</script>

<?php
}
require "layout.php";
?>