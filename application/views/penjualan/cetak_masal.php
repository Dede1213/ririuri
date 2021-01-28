
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
							<div class="form-group">
									<label>Opsi Cetak</label>
									<select id="opsi_masal" class='form-control'>
										<option>Semua</option>
										<option>Resi</option>
										<option>Ucapan</option>
									</select>
							</div>

							<div class="form-group">
									<label>Opsi Kertas</label>
									<select id="kertas_masal" class='form-control'>
										<option>57mm</option>
										<option>80mm</option>
										<option>100mm</option>
										<option>A4</option>
									</select>
							</div>


<?php echo form_close(); ?>


<script>
	function CetakMasal()
{
			var FormData = "jumlah_cetak="+encodeURI($('#jumlah_cetak').val());
			FormData += "&opsi_masal="+encodeURI($('#opsi_masal').val());
			FormData += "&kertas_masal="+encodeURI($('#kertas_masal').val());
			

			window.open("<?php echo site_url('penjualan/transaksi-cetak-resi-masal/?'); ?>" + FormData,'_blank');
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