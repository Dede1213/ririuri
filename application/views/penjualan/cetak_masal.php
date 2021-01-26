
<?php echo form_open('penjualan/cetak-masal/'.$master->id_penjualan_m, array('id' => 'FormEditAdmin')); ?>
<div class='form-group'>
	<label>Jumlah Transaksi Terakhir</label>
	<select id="jumlah_cetak" class='form-control'>
		<option>2</option>
		<option>3</option>
		<option>4</option>
		<option>5</option>
		<option>6</option>
		<option>7</option>
		<option>8</option>
		<option>9</option>
		<option>10</option>
	</select>
</div>

<?php echo form_close(); ?>


<script>
	function CetakMasal()
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
	var Tombol = "<button type='button' class='btn btn-primary' id='Print'><i class='fa fa-print'></i> Cetak</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$('#Print').click(function(e){
		e.preventDefault();
		CetakMasal();
	});

});
</script>