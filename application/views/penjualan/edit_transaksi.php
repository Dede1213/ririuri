
<?php echo form_open('penjualan/act-edit-transaksi/'.$master->id_penjualan_m, array('id' => 'FormEditAdmin')); ?>
<div class='form-group'>
	<label>Biaya Admin</label>
	<input type="text" id="biaya_admin" name="biaya_admin" value="<?php echo $master->biaya_admin; ?>" class="form-control">
	<label>Income +</label>
	<input type="text" id="laba_tambahan" name="laba_tambahan" value="<?php echo $master->laba_tambahan; ?>" class="form-control">
</div>

<?php echo form_close(); ?>


<script>
	function TambahPelanggan()
{
	$.ajax({
		url: $('#FormEditAdmin').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormEditAdmin').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1)
			{ 
				$('#FormEditAdmin').each(function(){
					this.reset();
				});

					$('#ResponseInput').html('');

					$('.modal-dialog').removeClass('modal-lg');
					$('.modal-dialog').addClass('modal-sm');
					$('#ModalHeader').html('Berhasil');
					$('#ModalContent').html(json.pesan);
					$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Okay</button>");
					$('#ModalGue').modal('show');

					$('#my-grid').DataTable().ajax.reload( null, false );
			}
			else 
			{
				$('#ResponseInput').html(json.pesan);
			}
		}
	});
}

$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='Simpan'><i class='fa fa-save'></i> Simpan</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$('#Simpan').click(function(e){
		e.preventDefault();
		TambahPelanggan();
	});

	$('#Simpan').submit(function(e){
		e.preventDefault();
		TambahPelanggan();
	});
});
</script>