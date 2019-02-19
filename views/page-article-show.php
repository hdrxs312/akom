<?php
$_module 		= 'article';
$temp_id    = explode('/', $route)[2];
$id 				= explode('?', $temp_id)[0];
$_data 			= http($_module.'/detail/'.$id . '?' . $params);
$_title 		= get_menu($_module, 'label');

function _content($data) {
	$item = $data['detail'];
	$module = $data['module'];
?>
<!-- START FILE -->
	<div class="edge d-flex w-100 mb-3">
		<a href="<?php e(home().$module) ?>" class="btn btn-sm btn-light border-secondary">
			<i class="fas fa-level-up-alt mr-2"></i> Artikel
		</a>
	</div>
	<h1 class="edge"><?php e($item['ar_title']) ?></h1>
	<div class="kard">

		<div class="kard-option">
			<button class="btn text-muted" onclick="" ><i class="fas fa-chevron-down"></i></button>
			<div class="dropdown-list" style="display:none">
				<span onclick="updateData()" class="dropdown-item">Edit</span>
				<span onclick="deleteData()" class="dropdown-item">Delete</span>
			</div>
		</div>

		<div class="body">
			<?php parsedown($item['ar_content'], true); ?>
		</div>
		<div class="footer border-top text-muted small pt-3">
			<span class="btn btn-light btn-sm"><?php e($item['ar_count_comment']) ?> Komentar</span>
		</div>
	</div>
	<div class="kard">
	<?php
		if( count($data['comments']['list']) > 0) {
		foreach ($data['comments']['list'] as $row ) {
	?>
		<div class="body d-flex mb-3">
			<div class="mr-3 py-2">
				<img src="<?php e(home()) ?>assets/img/icon/instagram.svg" style="height:30px">
			</div>
			<div class="right w-100">
				<div class="kard-option">
					<button class="btn text-muted" onclick="" ><i class="fas fa-chevron-down"></i></button>
					<div class="dropdown-list" style="display:none">
						<span onclick="commentDelete(<?php e($row['arc_id']) ?>)" class="dropdown-item">Delete</span>
					</div>
				</div>
				<div class="bg-light p-2">
					<a href="'<?php e(home().'member/'.$row['mb_username']) ?>" class="text-dark">
						<b><?php e($row['mb_username']) ?></b>
					</a>
					<br>
					<?php parsedown($row['arc_content']) ?>
				</div>
				<span class="text-muted small btn btn-sm"><?php e( format_datetime($row['arc_created_date'])) ?></span>
				<a class="btn btn-link btn-sm" href="<?php e(home().'comment-article/'.$row['arc_id']) ?>"><?php e($row['arc_count_reply']) ?> balasan</a>
			</div>
		</div>
		<?php
			}
			}
			else
			{
		?>
		<span class="text-muted">Tidak ada komentar</span>
		<?php
			}
		?>
	</div>
	<div class="edge mb-3">
		<button onclick="commentCreate()" class="btn btn-sm btn-dark">Tulis komentar</button>
	</div>
	<div class="popup-backdrop" style="display:none"></div>
	<div class="popup" id="popup-form" style="display:none">

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

		<!-- Form Comment Create -->
		<form class="kard" id="comment-create" style="display:none">
			<h6>Buat komentar</h6>
			<label>Konten</label>
			<textarea class="form-control" name="arc_content"></textarea>
			<button class="btn btn-primary btn-sm">Submit</button>
			<button type="button" class="btn btn-outline-secondary btn-sm ml-2 cancel-form">Batal</button>
		</form>

		<!-- Form Comment Delete -->
		<form class="kard text-center" id="comment-delete" data-id="" style="display:none">
			<h6>Hapus komentar</h6>
			<label>Data yang terhapus tidak dapat di kembalikan. Lanjutkan?</label><br>
			<button class="btn btn-primary btn-sm">Ya</button>
			<button type="button" class="btn btn-outline-secondary btn-sm ml-2 cancel-form">Tidak</button>
		</form>

	</div>
<!-- END FILE -->
<?php 
}
function _script($data) {
	$list = base64_encode(json_encode($data['detail']));
	$list_comment = [];
	foreach ($data['comments']['list'] as $key => $value) {
		$list_comment[$value['arc_id']] = $value;
	}
	$list_comment = base64_encode(json_encode($list_comment));
?>
	<script type="text/javascript">
		const master = JSON.parse(atob('<?php echo $list; ?>'));
		const master_comment = JSON.parse(atob('<?php echo $list_comment; ?>'));
		const pfx = 'ar_';
		const pfc = 'arc_';
		const module = 'article';

		function commentCreate() {
			show_form('comment-create');
		}

		function commentDelete(id=0) {
			if(master_comment[id] != undefined ) {
				$('form#comment-delete').attr('data-id',id);
				show_form('comment-delete');
			}
		}

		function updateData() {
			$('form#update .form-control').val('');
			$('form#update .form-control').each(function() {
				let n = $(this).attr('name');
				if( master[n] != undefined) {
					$(this).val(master[n]);
				}
			})
			show_form('update');
		}

		function deleteData() {
			show_form('delete');
		}

		$('form#update').submit(function(e){
			e.preventDefault();
			let id = master[pfx+'id'];
			let url = '<?php e(api()) ?>'+module+'/update/'+id;
			let data = $('form#update').serialize();

			ajaxSend(url, data, function(res){
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
			let id = master[pfx+'id'];
			let url = '<?php e(api()) ?>'+module+'/delete/'+id;
			let data = '';

			ajaxSend(url, data, function(res){
				if(res.status == 1) {
					location.href = '<?php e(home()) ?>'+module;
				}
				if(res.status == 0) {
					alert(res.msg);
				}
			});
		});

		$('form#comment-create').submit(function(e){
			e.preventDefault();
			let id = master[pfx+'id'];
			let url = '<?php e(api()) ?>comment/create/'+module;
			let data = $('form#comment-create').serialize();
			data = pfc+module+'_id='+id+'&'+data;
			ajaxSend(url, data, function(res){
				if(res.status == 1) {
					location.reload();
				}
				if(res.status == 0) {
					alert(res.msg);
				}
			});
		});

		$('form#comment-delete').submit(function(e) {
			e.preventDefault();
			let id = $('form#comment-delete').attr('data-id');
			if( master_comment[id] == undefined) {
				alert('Something wrong!');
				return false;
			}
			let url = '<?php e(api()) ?>comment/delete/'+module+'/'+id;
			let data = '';

			ajaxSend(url, data, function(res){
				if(res.status == 1) {
					location.reload();
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