<?php if($penjualan->num_rows() > 0) { ?>
	<table class='table table-bordered table-striped table-responsive table-sm' style="font-size:12px;">
		<thead>
			<tr>
				<th>#</th>
				<th>Tanggal</th>
				<th>No Pesanan</th>
				<th>Nama Barang</th>
				<th>Harga /pc</th>
				<th>Modal</th>
				<th>Qty</th>
				<th>Subtotal</th>
				<th>Laba</th>
				<th>G.Total</th>
				<th>Admin</th>
				<th>Income+</th>
				<th>Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			$total_penjualan = 0;
			$total_laba = 0;
			$total_Admin = 0;
			$total_laba_tambahan = 0;
			$last_nota = '';
			foreach($penjualan->result() as $p)
			{

				

				if($last_nota == $p->nomor_nota){
					$nota = '';
					$tanggal = '';
					$grand = '';
					$keterengan = '';
					$no_old = $no-1;
					$no = '';
					$biaya_admin = '';
					$laba_tambahan = '';

				}else{
					$nota = $p->nomor_nota;
					$tanggal = date("d/m/Y", strtotime($p->tanggal));
					$grand = number_format($p->grand_total);
					$keterengan = $p->keterangan_lain;
					$total_penjualan = $total_penjualan + $p->grand_total;
					$biaya_admin = number_format($p->biaya_admin);
					$total_Admin = $total_Admin + $p->biaya_admin;
					$laba_tambahan = number_format($p->laba_tambahan);
					$total_laba_tambahan = $total_laba_tambahan + $p->laba_tambahan;
				}

				
				

				echo "
					<tr>
						<td>".$no."</td>
						<td>".$tanggal
						."</td>
						<td>".$p->nomor_nota."</td>
						<td>".$p->nama_barang."</td>
						<td>".number_format($p->harga_satuan)."</td>
						<td>".number_format($p->modal)."</td>
						<td>".$p->jumlah_beli."</td>
						<td>".number_format($p->total)."</td>
						<td>".number_format($p->laba)."</td>
						<td>".$grand."</td>
						<td>".$biaya_admin."</td>
						<td>".$laba_tambahan."</td>
						<td>".$keterengan."</td>
					</tr>
				";

				
				$total_laba = $total_laba + $p->laba;
				

				if($last_nota == $p->nomor_nota){
					$no = $no_old;
				}

				$last_nota = $p->nomor_nota;
			
				$no++;
				

				
				
				
			}

			echo "
				<tr>
					<td colspan='8'><b>TOTAL</b></td>
					<td><b>".number_format($total_laba)."</b></td>
					<td><b>".number_format($total_penjualan)."</b></td>
					<td><b>".number_format($total_Admin)."</b></td>
					<td><b>".number_format($total_laba_tambahan)."</b></td>
				</tr>
			";
			?>
		</tbody>
	</table>
		

	<p>
		<?php
		$from 	= date('Y-m-d', strtotime($from));
		$to		= date('Y-m-d', strtotime($to));
		?>
		<a href="<?php echo site_url('laporan/pdf/'.$from.'/'.$to.'/'.$id_barang); ?>" target='blank' class='btn btn-default'><img src="<?php echo config_item('img'); ?>pdf.png"> Export ke PDF</a>
		<a href="<?php echo site_url('laporan/excel/'.$from.'/'.$to.'/'.$id_barang); ?>" target='blank' class='btn btn-default'><img src="<?php echo config_item('img'); ?>xls.png"> Export ke Excel</a>
	</p>
	<br />
<?php } ?>

<?php if($penjualan->num_rows() == 0) { ?>
<div class='alert alert-info'>
Data dari tanggal <b><?php echo $from; ?></b> sampai tanggal <b><?php echo $to; ?></b> tidak ditemukan
</div>
<br />
<?php } ?>