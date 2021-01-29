<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>

<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			<h5><i class='fa fa-file-text-o fa-fw'></i> Data Graphic Penjualan</h5>
			<hr />

			<?php echo form_open('laporan', array('id' => 'FormLaporan')); ?>
			

			<div class="row">
				<div class="col-sm-6">
					
						<canvas id="myChart2"></canvas>
					
				</div>
				<div class="col-sm-6">
					
						<canvas id="myChart"></canvas>
					
				</div>
			</div>	

		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
  <script type="text/javascript">
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
        labels: [
          <?php
		  	
            if (count($data_month)>0) {
              foreach ($data_month as $row) {
                echo "'" .$row['tanggal'] ."',";
              }
            }
          ?>
        ],
        datasets: [{
            label: 'Data Penjualan Per Tahun Berjalan',
            backgroundColor: '#ccff99',
            borderColor: '#ccff99',
            data: [
              <?php
                if (count($data_month)>0) {
                   foreach ($data_month as $row) {
                    echo $row['total_transaksi'] . ", ";
                  }
                }
              ?>
            ]
        }]
    },
});
 
  </script>
<br>
  <hr>
  <br>
  <script type="text/javascript">
    var ctx = document.getElementById('myChart2').getContext('2d');
    var chart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
        labels: [
          <?php
		  	
            if (count($data)>0) {
              foreach ($data as $row) {
                echo "'" .$row['tanggal'] ."',";
              }
            }
          ?>
        ],
        datasets: [{
            label: 'Data Penjualan Per Bulan Berjalan',
            backgroundColor: '#ADD8E6',
            borderColor: '##93C3D2',
            data: [
              <?php
                if (count($data)>0) {
                   foreach ($data as $row) {
                    echo $row['total_transaksi'] . ", ";
                  }
                }
              ?>
            ]
        }]
    },
});
 
  </script>
<p class='footer'><?php echo config_item('web_footer'); ?></p>



<?php $this->load->view('include/footer'); ?>