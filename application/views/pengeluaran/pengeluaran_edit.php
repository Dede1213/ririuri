<?php echo form_open('pengeluaran/edit-pengeluaran/'.$pengeluaran->id, array('id' => 'FormEditPengeluaran')); ?>
<div class='form-group'>
	<label>Keterangan</label>
	<input type='text' name='keterangan' class='form-control' value="<?php echo $pengeluaran->keterangan;?>">
	<label>Nominal</label>
	<input type='text' name='nominal' class='form-control' value="<?php echo $pengeluaran->nominal;?>">
	<label>Tanggal</label>
	<input type='date' name='tgl_keluar' class='form-control' value="<?php echo $pengeluaran->tgl_keluar;?>">
	
</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function EditPengeluaran()
{
	$.ajax({
		url: $('#FormEditPengeluaran').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormEditPengeluaran').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1){ 
				$('#ResponseInput').html(json.pesan);
				setTimeout(function(){ 
			   		$('#ResponseInput').html('');
			    }, 3000);
				$('#my-grid').DataTable().ajax.reload( null, false );
			}
			else {
				$('#ResponseInput').html(json.pesan);
			}
		}
	});
}

$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanEditPengeluaran'>Update Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormEditPengeluaran").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanEditPengeluaran').click(function(e){
		e.preventDefault();
		EditPengeluaran();
	});

	$('#FormEditPengeluaran').submit(function(e){
		e.preventDefault();
		EditPengeluaran	});
});
</script>