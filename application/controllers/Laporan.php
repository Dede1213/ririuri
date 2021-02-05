<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------
 * CLASS NAME : Laporan
 * ------------------------------------------------------------------------
 *
 * @author     Muhammad Akbar <muslim.politekniktelkom@gmail.com>
 * @copyright  2016
 * @license    http://aplikasiphp.net
 *
 */

class Laporan extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$level 		= $this->session->userdata('ap_level');
		$allowed	= array('admin', 'keuangan');

		if( ! in_array($level, $allowed))
		{
			redirect();
		}
	}

	public function index()
	{
		$this->load->model('m_barang');
		$data['barang']	= $this->m_barang->getBarangdd();
		
		$this->load->view('laporan/form_laporan',$data);
	}

	public function penjualan($from, $to, $id_barang)
	{
		$this->load->model('m_penjualan_master');
		$dt['penjualan'] 	= $this->m_penjualan_master->laporan_penjualan($from, $to, $id_barang);
		$dt['from']			= date('d F Y', strtotime($from));
		$dt['to']			= date('d F Y', strtotime($to));
		$dt['id_barang']			= $id_barang;
		$this->load->view('laporan/laporan_penjualan', $dt);
	}

	public function excel($from, $to, $id_barang)
	{
		
		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan($from, $to, $id_barang);
		if($penjualan->num_rows() > 0)
		{
			
			$filename = 'Laporan_Penjualan_'.$from.'_'.$to;
			header("Content-type: application/x-msdownload");
			header("Content-Disposition: attachment; filename=".$filename.".xls");

			echo "
				<h4>Laporan Penjualan Tanggal ".date('d/m/Y', strtotime($from))." - ".date('d/m/Y', strtotime($to))."</h4>
				<table border='1' width='100%'>
					<thead>
						<tr>
							<th>#</th>
							<th>No. Pesanan</th>
							<th>Tanggal</th>
							<th>Nama Barang</th>
							<th>Harga Satuan</th>
							<th>Modal</th>
							<th>Qty</th>
							<th>Subtotal</th>
							<th>Laba</th>
							<th>Grand Total</th>
							<th>Admin</th>
							<th>Income+</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
			";

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
			   </tbody>
			</table>
			";
			
		}
	}

	public function pdf($from, $to, $id_barang)
	{
		$this->load->library('cfpdf');
					
		$pdf = new FPDF('L','mm','A4'); //new FPDF('P','mm','A4');
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);

		$pdf->SetFont('Arial','',10);
		$pdf->Cell(0, 8, "Laporan Penjualan Tanggal ".date('d/m/Y', strtotime($from))." - ".date('d/m/Y', strtotime($to)), 0, 1, 'L'); 
		
		$pdf->Cell(7, 7, 'No', 1, 0, 'L'); 
		$pdf->Cell(35, 7, 'No.Pesanan', 1, 0, 'L'); 
		$pdf->Cell(20, 7, 'Tanggal', 1, 0, 'L'); 
		$pdf->Cell(70, 7, 'Nama Barang', 1, 0, 'L');
		$pdf->Cell(25, 7, 'Harga/pc', 1, 0, 'L'); 
		$pdf->Cell(25, 7, 'Modal', 1, 0, 'L'); 
		$pdf->Cell(8, 7, 'Qty', 1, 0, 'L'); 
		$pdf->Cell(25, 7, 'Laba', 1, 0, 'L'); 
		$pdf->Cell(25, 7, 'Admin', 1, 0, 'L'); 
		$pdf->Cell(25, 7, 'Income +', 1, 0, 'L'); 
		$pdf->Ln();

		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan($from, $to, $id_barang);

		
		$no = 1;
		$total_penjualan = 0;
		$total_laba = 0;
		$total_Admin = 0;
		$last_nota = '';
		$total_laba_tambahan = 0;

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


			$pdf->Cell(7, 7, $no, 1, 0, 'L'); 
			$pdf->Cell(35, 7, $p->nomor_nota, 1, 0, 'L'); 
			$pdf->Cell(20, 7, $tanggal, 1, 0, 'L'); 
			$pdf->Cell(70, 7, substr($p->nama_barang,0,25), 1, 0, 'L');  //
			$pdf->Cell(25, 7, number_format($p->harga_satuan), 1, 0, 'L'); 
			$pdf->Cell(25, 7, number_format($p->modal), 1, 0, 'L'); 
			$pdf->Cell(8, 7, $p->jumlah_beli, 1, 0, 'L'); 
			$pdf->Cell(25, 7, number_format($p->laba), 1, 0, 'L'); 
			$pdf->Cell(25, 7, $biaya_admin, 1, 0, 'L');
			$pdf->Cell(25, 7, $laba_tambahan, 1, 0, 'L');
			$pdf->Ln();

				$total_laba = $total_laba + $p->laba;

				if($last_nota == $p->nomor_nota){
					$no = $no_old;
				}

				$last_nota = $p->nomor_nota;
			
				$no++;
		}

		$pdf->Cell(190, 7, '                                                                                    Total', 1, 0, 'L'); 
		$pdf->Cell(25, 7, number_format($total_laba), 1, 0, 'L'); 
		$pdf->Cell(25, 7, number_format($total_Admin), 1, 0, 'L'); 
		$pdf->Cell(25, 7, number_format($total_laba_tambahan), 1, 0, 'L'); 
		$pdf->Ln();

		$pdf->Output();
	}

	public function log(){

		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_user->get_log()->result_array();

		foreach($penjualan as $row){
			print_r($row);
			echo "<br><hr>";
		}
	}

	public function graphic(){

		$this->load->model('m_penjualan_master');
		$penjualan['data'] 	= $this->m_penjualan_master->get_trx_day()->result_array();
		$penjualan['data_before'] 	= $this->m_penjualan_master->get_trx_day_before()->result_array();

	
		
		$penjualan['data_month'] 	= $this->m_penjualan_master->get_trx_month()->result_array();
		$penjualan['data_month_before'] 	= $this->m_penjualan_master->get_trx_month_before()->result_array();


		$penjualan['controller'] = "graphic";
		// print_r($penjualan['data_month']);
		$this->load->view('laporan/graphic',$penjualan);
		
	}
}