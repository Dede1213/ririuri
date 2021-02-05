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

			if($level == 'admin' OR $level == 'inventory')
			{
				$nestedData[]	= "<a href='".site_url('barang/edit-merek/'.$row['id_merk_barang'])."' id='EditMerek'><i class='fa fa-pencil'></i> Edit</a>";
				$nestedData[]	= "<a href='".site_url('barang/hapus-merek/'.$row['id_merk_barang'])."' id='HapusMerek'><i class='fa fa-trash-o'></i> Hapus</a>";
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

	public function tambah_merek()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('merek','Merek','trim|required|max_length[40]|alpha_numeric_spaces');				
				$this->form_validation->set_message('required','%s harus diisi !');
				$this->form_validation->set_message('alpha_numeric_spaces', '%s Harus huruf / angka !');

				if($this->form_validation->run() == TRUE)
				{
					$this->load->model('m_merk_barang');
					$merek 	= $this->input->post('merek');
					$insert = $this->m_merk_barang->tambah_merek($merek);
					if($insert)
					{
						echo json_encode(array(
							'status' => 1,
							'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> <b>".$merek."</b> berhasil ditambahkan.</div>"
						));
					}
					else
					{
						$this->query_error();
					}
				}
				else
				{
					$this->input_error();
				}
			}
			else
			{
				$this->load->view('barang/merek/merek_tambah');
			}
		}
	}

	public function hapus_merek($id_merk_barang)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_merk_barang');
				$hapus = $this->m_merk_barang->hapus_merek($id_merk_barang);
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

	public function edit_merek($id_merk_barang = NULL)
	{
		if( ! empty($id_merk_barang))
		{
			$level = $this->session->userdata('ap_level');
			if($level == 'admin' OR $level == 'inventory')
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_merk_barang');
					
					if($_POST)
					{
						$this->load->library('form_validation');
						$this->form_validation->set_rules('merek','Merek','trim|required|max_length[40]|alpha_numeric_spaces');				
						$this->form_validation->set_message('required','%s harus diisi !');
						$this->form_validation->set_message('alpha_numeric_spaces', '%s Harus huruf / angka !');

						if($this->form_validation->run() == TRUE)
						{
							$merek 	= $this->input->post('merek');
							$insert = $this->m_merk_barang->update_merek($id_merk_barang, $merek);
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
							$this->input_error();
						}
					}
					else
					{
						$dt['merek'] = $this->m_merk_barang->get_baris($id_merk_barang)->row();
						$this->load->view('barang/merek/merek_edit', $dt);
					}
				}
			}
		}
	}

	
}