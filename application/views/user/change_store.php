<?php echo form_open('user/ubah-toko', array('id' => 'FormUbahToko')); ?>
<div class='form-group'>
	<label>Nama Toko</label>
	<input type='text' name='nama_toko' class='form-control' autofocus="autofocus" value="<?php echo $toko->nama_toko;?>">
</div>
<div class='form-group'>
	<label>Kartu Ucapan</label>
	<textarea name='kartu_ucapan' class='form-control'><?php echo $toko->kartu_ucapan;?></textarea>
</div>

<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanUbahToko'>Simpan</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$('#SimpanUbahToko').click(function(){
		$.ajax({
			url: $('#FormUbahToko').attr('action'),
			type: "POST",
			cache: false,
			data: $('#FormUbahToko').serialize(),
			dataType:'json',
			success: function(json){
				if(json.status == 1){ 
					$('#FormUbahToko').each(function(){
						this.reset();
					});

					$('#ResponseInput').html(json.pesan);
					setTimeout(function(){ 
				   		$('#ResponseInput').html('');
				    }, 3000);
				}
				else {
					$('#ResponseInput').html(json.pesan);
				}
			}
		});
	});
});
</script>