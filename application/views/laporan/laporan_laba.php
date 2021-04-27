<?php if($laba_bersih != 0) { ?>
	<table class='table table-bordered table-striped table-responsive table-sm' style="font-size:12px;">
		<thead>
			<tr>
				<th>Date From</th>
				<th>Date To</th>
				<th>Total Pendapatan (A)</th>
				<th>Total Income+ (B)</th>
				<th>Total Admin (C)</th>
				<th>Total Pengeluaran (D)</th>				
				<th>Total Laba (E)</th>
				<th>Total Modal (F)</th>
				<th>Pendapatan Bersih ((A+B)-(C+D))</th>
				<th>Laba Bersih ((E+B)-(C+D))</th>
			</tr>
		</thead>
		<tbody>
					<tr>
						<td><?php echo $from;?></td>
						<td><?php echo $to;?></td>
						<td><?php echo number_format($total_grand);?></td>
						<td><?php echo number_format($total_laba_tambahan);?></td>
						<td><?php echo number_format($total_admin);?></td>
						<td><?php echo number_format($total_pengeluaran);?></td>
						<td><?php echo number_format($total_laba);?></td>
						<td><?php echo number_format($total_modal);?></td>
						<td><?php echo number_format($pendapatan_bersih);?></td>
						<td><?php echo number_format($laba_bersih);?></td>
					</tr>
		</tbody>
	</table>
		

	
	<br />
<?php } ?>

<?php if($laba_bersih == 0) { ?>
<div class='alert alert-info'>
Data dari tanggal <b><?php echo $from; ?></b> sampai tanggal <b><?php echo $to; ?></b> tidak ditemukan
</div>
<br />
<?php } ?>