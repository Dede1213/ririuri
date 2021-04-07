<?php echo form_open('pengeluaran/tambah-pengeluaran', array('id' => 'FormTambahPengeluaran')); ?>
<div class='form-group'>
	<label>Keterangan</label>
	<input type='text' name='keterangan' class='form-control'>
	<label>Nominal</label>
	<input type='text' name='nominal' class='form-control'>
	<label>Tanggal</label>
	<input type='date' name='tgl_keluar' class='form-control'>
</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function TambahPengeluaran()
{
	$.ajax({
		url: $('#FormTambahPengeluaran').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormTambahPengeluaran').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1){ 
				$('#ResponseInput').html(json.pesan);
				setTimeout(function(){ 
			   		$('#ResponseInput').html('');
			    }, 3000);
				$('#my-grid').DataTable().ajax.reload( null, false );

				$('#FormTambahPengeluaran').each(function(){
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
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahPengeluaran'>Simpan Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormTambahPengeluaran").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanTambahPengeluaran').click(function(e){
		e.preventDefault();
		TambahPengeluaran();
	});

	$('#FormTambahPengeluaran').submit(function(e){
		e.preventDefault();
		TambahPengeluaran();
	});
});
</script>