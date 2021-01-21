<?php $this->load->view('include/header'); ?>

<div class="container-fluid">
	<div class="login-panel">
		<center>
			<img src="<?php echo config_item('img'); ?>logo.png" />
		</center>
		<div class='panel panel-default'>
			<div class='panel-body'>
				<?php echo form_open('secure', array('id' => 'FormLogin')); ?>
					<div class="form-group">
						<label>Username</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class='glyphicon glyphicon-user'></span>
							</div>
							<?php 
							echo form_input(array(
								'name' => 'username', 
								'class' => 'form-control', 
								'autocomplete' => 'off', 
								'autofocus' => 'autofocus'
							)); 
							?>
						</div>
					</div>
					<div class="form-group">
						<label>Password</label>
						<div class="input-group">
							<div class="input-group-addon">
								<span class='glyphicon glyphicon-lock'></span>
							</div>
							<?php 
							echo form_password(array(
								'name' => 'password', 
								'class' => 'form-control', 
								'id' => 'InputPassword'
							)); 
							?>
						</div>
					</div>

					<button type="submit" class="btn btn-primary">
						<span class='glyphicon glyphicon-log-in' aria-hidden="true"></span> Sign In
					</button>
					<!-- <button type="button" class="btn btn-default" id='TambahUser'>
					
					<span class='glyphicon glyphicon-edit' aria-hidden="true"></span> DAFTAR
					</button> -->

					<a href="<?php echo site_url('secure/tambah');?>" class='btn btn-default' id='TambahUser'><i class='fa fa-user fa-fw'></i> Daftar</a>
				<?php echo form_close(); ?>

				<div id='ResponseInput'></div>
			</div>
		</div>
		<p class='footer'><?php echo config_item('web_footer'); ?></p>
	</div>
</div>



<script>

$(document).on('click', '#TambahUser, #EditUser', function(e){
		e.preventDefault();
		
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').removeClass('modal-sm');
		$('#ModalHeader').html('Registrasi Toko Baru');

		$('#ModalContent').load($(this).attr('href'));
		$('#ModalGue').modal('show');
	});

$(function(){
	//------------------------Proses Login Ajax-------------------------//
	$('#FormLogin').submit(function(e){
		e.preventDefault();
		$.ajax({
			url: $(this).attr('action'),
			type: "POST",
			cache: false,
			data: $(this).serialize(),
			dataType:'json',
			success: function(json){
				//response dari json_encode di controller

				if(json.status == 1){ window.location.href = json.url_home; }
				if(json.status == 0){ $('#ResponseInput').html(json.pesan); }
				if(json.status == 2){
					$('#ResponseInput').html(json.pesan);
					$('#InputPassword').val('');
				}
			}
		});
	});

	//-----------------------Ketika Tombol Reset Diklik-----------------//
	$('#ResetData').click(function(){
		$('#ResponseInput').html('');
	});
});
</script>

<?php $this->load->view('include/footer'); ?>


