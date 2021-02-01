<?php echo form_open('secure/act_tambah', array('id' => 'FormTambahUser')); ?>
<div class='form-group'>
	<label>Username</label>
	<input type='text' name='username' class='form-control'>
</div>
<div class='form-group'>
	<label>Password</label>
	<input type='password' name='password' id="password" class='form-control'>
</div>

<div class='form-group'>
	<label>Ulangi Password</label>
	<input type='password' name='repassword' id="repassword" class='form-control'>
</div>

<hr />

<div class='form-group'>
	<label>Nama</label>
	<input type='text' name='nama' class='form-control'>
</div>

<div class='form-group'>
<label>Isi Captcha *Perhatikan baik baik angka nya ya.</label> <br>
<?php echo $cap_img;?> <br> <br>
<input type='text' name='kode_captcha' class='form-control'> 
</div>

<?php echo form_close(); ?>

<div id='ResponseInputModal'></div>

<script>
function TambahUser()
{
	var a = $('#password').val();
	var b = $('#repassword').val();

	if(a == b){

		$.ajax({
			url: $('#FormTambahUser').attr('action'),
			type: "POST",
			cache: false,
			data: $('#FormTambahUser').serialize(),
			dataType:'json',
			success: function(json){
				if(json.status == 1){ 
					$('.modal-dialog').removeClass('modal-lg');
					$('.modal-dialog').addClass('modal-sm');
					$('#ModalHeader').html('Sukses !');
					$('#ModalContent').html(json.pesan);
					$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' id='oke'>Ok</button>");
					$('#ModalGue').modal('show');
					// $('#my-grid').DataTable().ajax.reload( null, false );
					$('#oke').click(function(){
						location.reload(); 
					});
					
				}
				else {
					$('#ResponseInputModal').html(json.pesan);
				}
			}
		});
	}else{
		alert('password tidak sama!');
	}
}

$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahUser'>Simpan</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormTambahUser").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanTambahUser').click(function(e){
		e.preventDefault();
		TambahUser();
	});

	$('#FormTambahUser').submit(function(e){
		e.preventDefault();
		TambahUser();
	});
});
</script>