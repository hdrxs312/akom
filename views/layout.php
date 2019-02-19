<?php
	$_login = isset($_user['mb_id']) ? true : false;
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title><?php e($_title) ?></title>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> 
	<link rel="stylesheet" href="<?php e(home()) ?>assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php e(home()) ?>assets/fa/css/fontawesome.min.css">
	<link rel="stylesheet" href="<?php e(home()) ?>assets/fa/css/solid.min.css">
	<link rel="stylesheet" href="<?php e(home()) ?>style.css" />
</head>
<body class="bg-light">
	<nav class="navbar navbar-expand-lg navbar-dark bg-secondary fixed-top" >
		<div class="container">
		  <a class="navbar-brand mr-auto ml-3" href="#" onclick="$('aside').toggleClass('collapsed')">
		  	</i> <?php e($_community['co_name']) ?>
	  	</a>
		  	<button class="btn btn-secondary btn-sm" id="btn-user">
	  		<?php if($_login) { ?>
		  		<i class="fas fa-user mr-2"></i> <span class="d-none d-sm-inline"><?php e($_user['mb_username']) ?></span>
	  		<?php } else { ?>
		  		<i class="fas fa-sign-in-alt mr-2"></i> <span>Login</span>
	  		<?php } ?>
		  	</button>
		 </div>
	</nav>
	<div class="container d-flex" style="margin-top:70px">
		<aside class="collapsed" onblur="$('aside').toggleClass('collapsed')">
			<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

				<a href="<?php e(home()) ?>" class="nav-link <?php e($_module=='home'?'active':'') ?>" >
					<span><i class="fas mr-3 fa-home"></i></span>Beranda
				</a>
				
				<?php 
				$menus = isset($_COOKIE['menu']) ? json_decode($_COOKIE['menu'],true) : $_menu;
				foreach( $menus as $menu) { ?>
				<a href="<?php e(home().$menu['mn_name']) ?>" class="nav-link <?php e($_module==$menu['mn_name']?'active':'') ?>" >
					<span><i class="fas mr-3 fa-<?php e($menu['mn_icon']) ?>"></i></span><?php e($menu['mn_label']) ?>
				</a>
				<?php } ?>

			</div>

			<p class="small text-muted my-3 text-center">Thanks to: <a href="#" class="text-primary">dome.in</a></p>
		</aside>
		<div onclick="$('aside').toggleClass('collapsed')" class="aside-backdrop">.</div>
		<section class="<?php e($_module) ?>">

				<?php _content($_data, $_community, $_user); ?>

			<div class="popup-backdrop" style="display: none" onclick="hide_login()"></div>
			<div class="popup popup-sm" id="popup-login" style="display: none">
				<form class="kard" action="login">
					<div class="close-popup" onclick="hide_login()">
						<button type="button" class="btn bg-white text-muted"><i class="fas fa-times"></i></button>
					</div>
					<h6>Login</h6>
					<div class="alert alert-danger alert-sm alert-login" style="display: none">username / password salah</div>

					<label>Username</label>
					<input class="form-control" name="username">

					<label>Password (<span class="btn btn-link btn-sm">Lupa?</span>) </label>
					<input class="form-control" name="password">

					<div class="reg-form" style="display:none">
						<label>Email</label>
						<input class="form-control" name="email">
					</div>

					<button class="btn btn-primary btn-sm">
						<span class="reg-form">Login</span>
						<span class="reg-form" style="display:none">Daftar</span>
					</button> 
					<button type="button" class="btn btn-link btn-sm" onclick="registerToggle()">
						<span class="reg-form">Buat akun baru?</span>
						<span class="reg-form" style="display:none">Login saja</span>
					</button>
				</form>
			</div>

		</section>
	</div>
	<script src="<?php e(home()) ?>assets/js/jquery.min.js"></script>
	<script src="<?php e(home()) ?>assets/js/jquery.cookie.js"></script>
	<script src="<?php e(home()) ?>assets/js/popper.min.js"></script>
	<script src="<?php e(home()) ?>assets/js/bootstrap.bundle.js"></script>
	<script type="text/javascript">
	function ajaxInit()
	{
		$.ajaxSetup({
	    headers: { 
	    	'x-data': $.cookie('x-data'),
	    	'Referer': location.href,
	    },
	    error : function(jqXHR, textStatus, errorThrown) {
	      if (jqXHR.status == 404) {
	        alert("Element not found.");
	      }
	      if (jqXHR.status == 401) {
	        show_login();
	      }
	       else {
	          alert("Error: " + textStatus + ": " + errorThrown);
	      }
	    }
		});
	}
	function ajaxSend(url, data, handle) {
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			success: function(res) {
				handle(res);
			}
		});
	}
	function ajaxSendUpload(url, data, handle) {
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			contentType: false,
			processData: false,
			success: function(res) {
				handle(res);
			}
		});
	}
	ajaxInit();
	</script>
	<script type="text/javascript">

		function show_form(f) {
			$('form#'+f).show();
			$('#popup-form').show();
			$('#popup-form').prev('.popup-backdrop').show();
		}
		
		function hide_form(f=null) {
			if(f!=null) {
				$('form#'+f).hide();
			}
			else {
				$('#popup-form form').hide();
			}
			$('#popup-form').hide();
			$('#popup-form').prev('.popup-backdrop').hide();
		}

		function show_login() {
			$('#popup-login').show();
			$('#popup-login').prev('.popup-backdrop').show();
		}

		function hide_login() {
			$('#popup-login').hide();
			$('#popup-login').prev('.popup-backdrop').hide();
		}

		function quick_set_user(user) {
			$('#btn-user').attr('onclick', 'show_profile(user.mb_username)');
			$('#btn-user span').text(user.mb_username);
			$('#btn-user i').removeClass('fa-sign-in-alt');
			$('#btn-user i').addClass('fa-user');
		}

		$('#btn-user').click( function(e){
			let u = $('#btn-user span').text().toLowerCase();
			if( u != 'login') {
				location.href = '<?php e(home())?>member/'+u;
			}
			else {
				show_login();
			}
		});

		function registerToggle() {
			$('.reg-form').toggle();
			let action = $('#popup-login form').attr('action');
			if(action=='login') {
				$('#popup-login form').attr('action','register');
			}
			else {
				$('#popup-login form').attr('action','login');
			}
		}
		$('#popup-login form').submit(function(e){
			e.preventDefault();
			let action = $('#popup-login form').attr('action');
			let url = '<?php e(api()) ?>auth/'+action;
			let data = $('#popup-login form').serialize();
			$.ajax({
				url: url,
				type: 'post',
				data: data,
				success: function(res){
					if(action == 'login')
					{
						$.cookie('x-data', res['x-data'], {expires: 30, path: '/'} );
						ajaxInit();
						quick_set_user(res['data']);
						hide_login();
					}
					if( action == 'register')
					{
						
					}
				},
				error: function(err) {
					$('.alert-login').show();
				}
			});
		});

		$('.kard-option > button').click(function(){
			$(this).next('.dropdown-list').show();
		});

		$('.kard-option > button').blur(function(){
			$(this).next('.dropdown-list').fadeOut(500);
		});

		$('.hide-option').click(function(e){
			$(this).parent('.dropdown').hide();
		});
		$('.cancel-form').click(function(e){
			hide_form();
		});
	</script>
	<?php
	if( function_exists('_script')) _script($_data);
	?>