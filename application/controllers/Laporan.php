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

	public function excel($from, $to)
	{
		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan($from, $to);
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
							<th>No</th>
							<th>Tanggal</th>
							<th>Total Penjualan</th>
						</tr>
					</thead>
					<tbody>
			";

			$no = 1;
			$total_penjualan = 0;
			foreach($penjualan->result() as $p)
			{
				echo "
					<tr>
						<td>".$no."</td>
						<td>".date('d F Y', strtotime($p->tanggal))."</td>
						<td>Rp. ".str_replace(",", ".", number_format($p->total_penjualan))."</td>
					</tr>
				";

				$total_penjualan = $total_penjualan + $p->total_penjualan;
				$no++;
			}

			echo "
				<tr>
					<td colspan='2'><b>Total Seluruh Penjualan</b></td>
					<td><b>Rp. ".str_replace(",", ".", number_format($total_penjualan))."</b></td>
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
		$pdf->Cell(35, 7, 'No.Nota', 1, 0, 'L'); 
		$pdf->Cell(20, 7, 'Tanggal', 1, 0, 'L'); 
		$pdf->Cell(50, 7, 'Nama Barang', 1, 0, 'L');
		$pdf->Cell(18, 7, 'Harga/pc', 1, 0, 'L'); 
		$pdf->Cell(18, 7, 'Modal', 1, 0, 'L'); 
		$pdf->Cell(8, 7, 'Qty', 1, 0, 'L'); 
		$pdf->Cell(20, 7, 'Subtotal', 1, 0, 'L'); 
		$pdf->Cell(18, 7, 'Laba', 1, 0, 'L'); 
		$pdf->Cell(20, 7, 'Grand Total', 1, 0, 'L'); 
		$pdf->Cell(18, 7, 'Admin', 1, 0, 'L'); 
		$pdf->Cell(50, 7, 'Keterangan', 1, 0, 'L'); 
		$pdf->Ln();

		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan($from, $to, $id_barang);

		
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

			$pdf->Cell(7, 7, $no, 1, 0, 'L'); 
			$pdf->Cell(35, 7, $nota, 1, 0, 'L'); 
			$pdf->Cell(20, 7, $tanggal, 1, 0, 'L'); 
			$pdf->Cell(50, 7, substr($p->nama_barang,0,25), 1, 0, 'L');  //
			$pdf->Cell(18, 7, number_format($p->harga_satuan), 1, 0, 'L'); 
			$pdf->Cell(18, 7, number_format($p->modal), 1, 0, 'L'); 
			$pdf->Cell(8, 7, $p->jumlah_beli, 1, 0, 'L'); 
			$pdf->Cell(20, 7, number_format($p->total), 1, 0, 'L'); 
			$pdf->Cell(18, 7, number_format($p->laba), 1, 0, 'L'); 
			$pdf->Cell(20, 7, $grand, 1, 0, 'L'); 
			$pdf->Cell(18, 7, number_format($p->biaya_admin), 1, 0, 'L');
			$pdf->Cell(50, 7, substr($keterengan,0,25), 1, 0, 'L');
			$pdf->Ln();

				$total_laba = $total_laba + $p->laba;
				$total_Admin = $total_Admin + $p->biaya_admin;

				if($last_nota == $p->nomor_nota){
					$no = $no_old;
				}

				$last_nota = $p->nomor_nota;
			
				$no++;
		}

		$pdf->Cell(176, 7, 'Total Seluruh Penjualan', 1, 0, 'L'); 
		$pdf->Cell(18, 7, number_format($total_laba), 1, 0, 'L'); 
		$pdf->Cell(20, 7, number_format($total_penjualan), 1, 0, 'L');
		$pdf->Cell(18, 7, number_format($total_Admin), 1, 0, 'L'); 
		$pdf->Cell(50, 7, '', 1, 0, 'L');
		$pdf->Ln();

		$pdf->Output();
	}
}