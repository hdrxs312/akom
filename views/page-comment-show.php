<?php
$_module 		= 'comment';
$temp_id    = explode('/', $route)[2];
$related		= str_replace('comment-', '', explode('/', $route)[1]);
$id 				= explode('?', $temp_id)[0];
$_data 			= http($_module.'/detail/'.$related.'/'.$id . '?' . $params);
$_title 		= 'Komentar';

function _content($data, $_com, $_user) {
	$item = $data['comment'];
	$login = isset($_user['mb_id']) ? true : false;
	$module = $data['module'];
	$pfc 		= get_prefix($module, 'pfc');
?>
<!-- START FILE -->
	<div class="edge d-flex w-100 mb-3">
		<a href="<?php e(home().$module.'/'.$item[$pfc.$module.'_id']) ?>" class="btn btn-sm btn-light border-secondary level-up">
			<i class="fas fa-level-up-alt mr-1"></i> <?php parseup($data['parent_title']) ?>
		</a>
	</div>
			<div class="kard">
				<div class="body d-flex mb-3">
					<div class="mr-3 py-2">
						<img src="<?php e(home()) ?>assets/img/icon/instagram.svg" style="height:30px">
					</div>
					<div class="right w-100">
						<div class="kard-option">
							<button class="btn text-muted" onclick="" ><i class="fas fa-chevron-down"></i></button>
							<div class="dropdown-list" style="display:none">
								<span onclick="commentDelete(<?php e($item[$pfc.'id']) ?>)" class="dropdown-item">Delete</span>
							</div>
						</div>
						<div class="bg-light p-2">
							<a href="'<?php e(home().'member/'.$item['mb_username']) ?>" class="text-dark">
								<b><?php e($item['mb_username']) ?></b>
							</a>
							<br>
							<?php parsedown($item[$pfc.'content']) ?>
						</div>
						<span class="text-muted small btn btn-sm"><?php e( format_datetime($item[$pfc.'created_date'])) ?></span>
						<a class="btn btn-link btn-sm" href="<?php e(home().'comment-discuss/'.$item[$pfc.'id']) ?>"><?php e($item[$pfc.'count_reply']) ?> balasan</a>
					</div>
				</div>
			</div>
			<div class="kard">
			<?php
				if( count($data['replies']['list'] ) > 0) {
				foreach ($data['replies']['list'] as $row ) {
			?>
				<div class="body d-flex mb-3">
					<div class="mr-3 py-2">
						<img src="<?php e(home()) ?>assets/img/icon/instagram.svg" style="height:30px">
					</div>
					<div class="right w-100">
						<div class="kard-option">
							<button class="btn text-muted" onclick="" ><i class="fas fa-chevron-down"></i></button>
							<div class="dropdown-list" style="display:none">
								<span onclick="commentDelete(<?php e($row[$pfc.'id']) ?>)" class="dropdown-item">Delete</span>
							</div>
						</div>
						<div class="bg-light p-2">
							<a href="'<?php e(home().'member/'.$row['mb_username']) ?>" class="text-dark">
								<b><?php e($row['mb_username']) ?></b>
							</a>
							<br>
							<?php parsedown($row[$pfc.'content']) ?>
						</div>
						<span class="text-muted small btn btn-sm"><?php e( format_datetime($row[$pfc.'created_date'])) ?></span>
					</div>
				</div>
			<?php
				}
				echo '<div class="edge">'. pagination($data['replies']['total'], $data['replies']['limit'], $data['replies']['page'], '?page=%d') .'</div>';
				} else {
			?>
			<p class="text-muted text-center m-0">Tidak ada data</p>
			<?php } ?>
			</div>
			<div class="edge text-center">
				<button onclick="commentCreate()" class="btn btn-sm btn-dark">Tulis balasan</button>
			</div>
<!-- END FILE -->
	<div class="popup-backdrop" style="display:none"></div>
	<div class="popup" id="popup-form" style="display:none">

		<!-- Form Comment Create -->
		<form class="kard" id="comment-create" style="display:none">
			<h6>Buat komentar</h6>
			<label>Konten</label>
			<textarea class="form-control" name="<?php e($pfc.'content') ?>"></textarea>
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
<?php 
}
function _script($data) {
	$module = $data['module'];
	$pfx 		= get_prefix($module, 'pfx');
	$pfc 		= get_prefix($module, 'pfc');
	$list = base64_encode(json_encode($data['comment']));
	$list_comment = [];
	foreach ($data['replies']['list'] as $key => $value) {
		$list_comment[$value[$pfc.'id']] = $value;
	}
	$list_comment = base64_encode(json_encode($list_comment));
?>
	<script type="text/javascript">
		const master = JSON.parse(atob('<?php echo $list; ?>'));
		const master_comment = JSON.parse(atob('<?php echo $list_comment; ?>'));
		const pfx = '<?php e($pfx)?>';
		const pfc = '<?php e($pfc)?>';
		const module = '<?php e($module)?>';

		function commentCreate() {
			show_form('comment-create');
		}

		function commentDelete(id=0) {
			if(master_comment[id] != undefined ) {
				$('form#comment-delete').attr('data-id',id);
				show_form('comment-delete');
			}
		}

		$('form#comment-create').submit(function(e){
			e.preventDefault();
			let id = master[pfx+'id'];
			let url = '<?php e(api()) ?>comment/create/'+module;
			let data = $('form#comment-create').serialize();
			data = pfc+module+'_id='+master[pfc+module+'_id']+'&'+data;
			data = pfc+'parent_id='+master[pfc+'id']+'&'+data;
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

		$('.cancel-form').click(function(){
			hide_form();
		});

	</script>

<?php
}

require "layout.php";
?>