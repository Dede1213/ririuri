<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------
 * CLASS NAME : Barang
 * ------------------------------------------------------------------------
 *
 * @author     Muhammad Akbar <muslim.politekniktelkom@gmail.com>
 * @copyright  2016
 * @license    http://aplikasiphp.net
 *
 */

class Pengeluaran extends MY_Controller 
{
	
	public function index()
	{
		$this->load->view('pengeluaran/pengeluaran_data');
	}

	public function list_pengeluaran_json()
	{
		$this->load->model('m_pengeluaran');
		$level 			= $this->session->userdata('ap_level');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_pengeluaran->fetch_data_pengeluaran($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= $row['keterangan'];
			$nestedData[]	= $row['nominal'];
			$nestedData[]	= date_format(date_create($row['tgl_keluar']),"d-m-Y");

			if($level == 'admin' OR $level == 'keuangan')
			{
				$nestedData[]	= "<a href='".site_url('pengeluaran/edit-pengeluaran/'.$row['id'])."' id='EditPengeluaran'><i class='fa fa-pencil'></i> Edit</a>";
				$nestedData[]	= "<a href='".site_url('pengeluaran/hapus-pengeluaran/'.$row['id'])."' id='HapusPengeluaran'><i class='fa fa-trash-o'></i> Hapus</a>";
			}

			$data[] = $nestedData;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
			);

		echo json_encode($json_data);
	}

	public function tambah_pengeluaran()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'keuangan')
		{
			if($_POST)
			{
				$this->load->model('m_pengeluaran');
					$keterangan 	= $this->input->post('keterangan');
					$nominal 	= $this->input->post('nominal');
					$tgl_keluar 	= $this->input->post('tgl_keluar');

					$insert = $this->m_pengeluaran->tambah($keterangan,$nominal,$tgl_keluar);
					if($insert)
					{
						echo json_encode(array(
							'status' => 1,
							'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data berhasil ditambahkan.</div>"
						));
					}
					else
					{
						$this->query_error();
					}
			}
			else
			{
				$this->load->view('pengeluaran/tambah');
			}
		}
	}

	public function hapus_pengeluaran($id)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'keuangan')
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_pengeluaran');
				$hapus = $this->m_pengeluaran->hapus_pengeluaran($id);
				if($hapus)
				{
					echo json_encode(array(
						"pesan" => "<font color='green'><i class='fa fa-check'></i> Data berhasil dihapus !</font>
					"));
				}
				else
				{
					echo json_encode(array(
						"pesan" => "<font color='red'><i class='fa fa-warning'></i> Terjadi kesalahan, coba lagi !</font>
					"));
				}
			}
		}
	}

	public function edit_pengeluaran($id = NULL)
	{
		if( ! empty($id))
		{
			$level = $this->session->userdata('ap_level');
			if($level == 'admin' OR $level == 'keuangan')
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_pengeluaran');
					
					if($_POST)
					{
						
						$keterangan 	= $this->input->post('keterangan');
						$nominal 	= $this->input->post('nominal');
						$tgl_keluar 	= $this->input->post('tgl_keluar');
							
							$insert = $this->m_pengeluaran->update($id, $keterangan, $nominal, $tgl_keluar);
							if($insert)
							{
								echo json_encode(array(
									'status' => 1,
									'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data berhasil diupdate.</div>"
								));
							}
							else
							{
								$this->query_error();
							}
						
					}
					else
					{
						$dt['pengeluaran'] = $this->m_pengeluaran->get_baris($id)->row();
						$this->load->view('pengeluaran/pengeluaran_edit', $dt);
					}
				}
			}
		}
	}

	
}