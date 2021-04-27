<?php echo form_open('barang/tambah-bundling', array('id' => 'FormTambahBandling')); ?>
<div class='form-group'>
	<lable>Nama Barang Utama</lable>
	<select type='text' name='id_barang' class='form-control'>
	<?php
	foreach($barang as $row){
		echo "<option value=".$row['id_barang'].">".$row['nama_barang']."</option>";
	}
	?>
	</select>
<br>
	<lable>Nama Barang Bandling</lable>
	<select type='text' name='id_barang_bundling' class='form-control'>
	<?php
	foreach($barang as $row){
		echo "<option value=".$row['id_barang'].">".$row['nama_barang']."</option>";
	}
	?>
	</select>

</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function TambahBandling()
{
	$.ajax({
		url: $('#FormTambahBandling').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormTambahBandling').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1){ 
				$('#ResponseInput').html(json.pesan);
				setTimeout(function(){ 
			   		$('#ResponseInput').html('');
			    }, 3000);
				$('#my-grid').DataTable().ajax.reload( null, false );

				$('#FormTambahBandling').each(function(){
					this.reset();
				});
			}
			else {
				$('#ResponseInput').html(json.pesan);
			}
		}
	});
}

$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahBandling'>Simpan Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormTambahBandling").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanTambahBandling').click(function(e){
		e.preventDefault();
		TambahBandling();
	});

	$('#FormTambahBandling').submit(function(e){
		e.preventDefault();
		TambahBandling();
	});
});
</script>