<?php if($penjualan->num_rows() > 0) { ?>
	<table class='table table-bordered table-striped table-responsive table-sm' style="font-size:12px;">
		<thead>
			<tr>
				<th>#</th>
				<th>Tanggal</th>
				<th>Keterangan</th>
				<th>Nominal</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			$total_pengeluaran = 0;
			foreach($penjualan->result() as $p)
			{

				$total_pengeluaran = $total_pengeluaran + $p->nominal;
				
				

				echo "
					<tr>
						<td>".$no."</td>
						<td>".$p->tgl_keluar."</td>
						<td>".$p->keterangan."</td>
						<td>".$p->nominal."</td>
					</tr>
				";
			
				$no++;
				

				
				
				
			}

			echo "
				<tr>
					<td colspan='3'><b>TOTAL</b></td>
					<td><b>".number_format($total_pengeluaran)."</b></td>
				</tr>
			";
			?>
		</tbody>
	</table>
		

	
	<br />
<?php } ?>

<?php if($penjualan->num_rows() == 0) { ?>
<div class='alert alert-info'>
Data dari tanggal <b><?php echo $from; ?></b> sampai tanggal <b><?php echo $to; ?></b> tidak ditemukan
</div>
<br />
<?php } ?>