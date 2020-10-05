<?php if($penjualan->num_rows() > 0) { ?>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>#</th>
				<th>No. Nota</th>
				<th>Tanggal</th>
				<th>Nama Barang</th>
				<th>Harga Satuan</th>
				<th>Modal</th>
				<th>Qty</th>
				<th>Subtotal</th>
				<th>Laba</th>
				<th>Grand Total</th>
				<th>Admin</th>
				<th>Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			$total_penjualan = 0;
			$total_laba = 0;
			$total_Admin = 0;
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
				}else{
					$nota = $p->nomor_nota;
					$tanggal = date("d/m/Y", strtotime($p->tanggal));
					$grand = number_format($p->grand_total);
					$keterengan = $p->keterangan_lain;
					$total_penjualan = $total_penjualan + $p->grand_total;
				}

				$mod = $no%2;
				if ($mod == 0){
					$color ='#f2f2f2';
				}else{
					$color ='';
				}

				echo "
					<tr bgcolor='".$color."'>
						<td>".$no."</td>
						<td>".$nota."</td>
						<td>".$tanggal
						."</td>
						<td>".$p->nama_barang."</td>
						<td>".number_format($p->harga_satuan)."</td>
						<td>".number_format($p->modal)."</td>
						<td>".$p->jumlah_beli."</td>
						<td>".number_format($p->total)."</td>
						<td>".number_format($p->laba)."</td>
						<td>".$grand."</td>
						<td>".number_format($p->biaya_admin)."</td>
						<td>".$keterengan."</td>
					</tr>
				";

				
				$total_laba = $total_laba + $p->laba;
				$total_Admin = $total_Admin + $p->biaya_admin;

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
					<td colspan='2'><b>".number_format($total_Admin)."</b></td>
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