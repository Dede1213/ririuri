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

class Barang extends MY_Controller 
{
	public function index()
	{
		$this->load->view('barang/barang_data');
	}

	public function barang_json()
	{
		$this->load->model('m_barang');
		$level 			= $this->session->userdata('ap_level');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_barang->fetch_data_barang($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		// print_r($fetch);
		// exit;
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$modal = 0;
		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			// ########## update stok bundling ###########
			$this->load->model('m_bundling_barang');
			$getBundling = $this->m_bundling_barang->get_baris($row['id_barang']);
			$bundling = "";

			if($getBundling){
				
				foreach($getBundling as $value){
					$bundling .= "*";
				}
				
			}

			//end
			
			$modal = $row['total_stok'] * $row['modal'];
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= $row['kode_barang'].$bundling;
			$nestedData[]	= $row['nama_barang'];
			$nestedData[]	= $row['kategori'];
			$nestedData[]	= $row['merk'];
			$nestedData[]	= ($row['total_stok'] == 'Kosong') ? "<font color='red'><b>".$row['total_stok']."</b></font>" : $row['total_stok'];
			$nestedData[]	= $row['modal'];
			$nestedData[]	= $row['harga'];
			$nestedData[]	= preg_replace("/\r\n|\r|\n/",'<br />', $row['keterangan']);

			if($level == 'admin' OR $level == 'inventory')
			{
				$nestedData[]	= "<a href='".site_url('barang/edit/'.$row['id_barang'])."' id='EditBarang'><i class='fa fa-pencil'></i> Edit</a>";
				$nestedData[]	= "<a href='".site_url('barang/hapus/'.$row['id_barang'])."' id='HapusBarang'><i class='fa fa-trash-o'></i> Hapus</a>";
			}

			$data[] = $nestedData;
		}

		

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data,
			"totalmodal"		=> $modal,
			);

		echo json_encode($json_data);
	}

	public function total_modal()
	{
		
		$this->load->model('m_barang');
		
		$fetch			= $this->m_barang->getBarangdd();
		
		$modal = 0;
		foreach($fetch as $row)
		{ 
			$modalCount = $row['total_stok'] * $row['modal'];
			$modal = $modal+$modalCount;
		}


		echo number_format($modal);
	}

	public function hapus($id_barang)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_barang');
				$hapus = $this->m_barang->hapus_barang($id_barang);
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

	public function tambah()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($_POST)
			{
				$this->load->library('form_validation');

				$no = 0;
				foreach($_POST['kode'] as $kode)
				{
					$this->form_validation->set_rules('kode['.$no.']','Kode Barang #'.($no + 1),'trim|required|alpha_numeric|max_length[40]|callback_exist_kode[kode['.$no.']]');
					$this->form_validation->set_rules('nama['.$no.']','Nama Barang #'.($no + 1),'trim|required|max_length[60]|alpha_numeric_spaces');
					$this->form_validation->set_rules('id_kategori_barang['.$no.']','Kategori #'.($no + 1),'trim|required');
					$this->form_validation->set_rules('id_merk_barang['.$no.']','Merek #'.($no + 1),'trim');
					$this->form_validation->set_rules('stok['.$no.']','Stok #'.($no + 1),'trim|required|numeric|max_length[10]|callback_cek_titik[stok['.$no.']]');
					$this->form_validation->set_rules('modal['.$no.']','Modal #'.($no + 1),'trim|required|numeric|min_length[3]|max_length[10]|callback_cek_titik[modal['.$no.']]');
					$this->form_validation->set_rules('harga['.$no.']','Harga #'.($no + 1),'trim|required|numeric|min_length[3]|max_length[10]|callback_cek_titik[harga['.$no.']]');
					$this->form_validation->set_rules('keterangan['.$no.']','Keterangan #'.($no + 1),'trim|max_length[2000]');
					$no++;
				}
				
				$this->form_validation->set_message('required','%s harus diisi !');
				$this->form_validation->set_message('numeric','%s harus angka !');
				$this->form_validation->set_message('exist_kode','%s sudah ada di database, pilih kode lain yang unik !');
				$this->form_validation->set_message('cek_titik','%s harus angka, tidak boleh ada titik !');
				$this->form_validation->set_message('alpha_numeric_spaces', '%s Harus huruf / angka !');
				$this->form_validation->set_message('alpha_numeric', '%s Harus huruf / angka !');
				if($this->form_validation->run() == TRUE)
				{
					$this->load->model('m_barang');

					$no_array = 0;
					$inserted = 0;
					foreach($_POST['kode'] as $k)
					{
						$kode 				= $_POST['kode'][$no_array];
						$nama 				= $_POST['nama'][$no_array];
						$id_kategori_barang	= $_POST['id_kategori_barang'][$no_array];
						$id_merk_barang		= $_POST['id_merk_barang'][$no_array];
						$stok 				= $_POST['stok'][$no_array];
						$modal 				= $_POST['modal'][$no_array];
						$harga 				= $_POST['harga'][$no_array];
						$keterangan 		= $this->clean_tag_input($_POST['keterangan'][$no_array]);

						$insert = $this->m_barang->tambah_baru($kode, $nama, $id_kategori_barang, $id_merk_barang, $stok, $modal, $harga, $keterangan);
						if($insert){
							$inserted++;
						}
						$no_array++;
					}

					if($inserted > 0)
					{
						echo json_encode(array(
							'status' => 1,
							'pesan' => "<i class='fa fa-check' style='color:green;'></i> Data barang berhasil dismpan."
						));
					}
					else
					{
						$this->query_error("Oops, terjadi kesalahan, coba lagi !");
					}
				}
				else
				{
					$this->input_error();
				}
			}
			else
			{
				$this->load->model('m_kategori_barang');
				$this->load->model('m_merk_barang');

				$dt['kategori'] = $this->m_kategori_barang->get_all();
				$dt['merek'] 	= $this->m_merk_barang->get_all();
				$this->load->view('barang/barang_tambah', $dt);
			}
		}
		else
		{
			exit();
		}
	}

	public function ajax_cek_kode()
	{
		if($this->input->is_ajax_request())
		{
			$kode = $this->input->post('kodenya');
			$this->load->model('m_barang');

			$cek_kode = $this->m_barang->cek_kode($kode);
			if($cek_kode->num_rows() > 0)
			{
				echo json_encode(array(
					'status' => 0,
					'pesan' => "<font color='red'>Kode sudah ada</font>"
				));
			}
			else
			{
				echo json_encode(array(
					'status' => 1,
					'pesan' => ''
				));
			}
		}
	}

	public function exist_kode($kode)
	{
		$this->load->model('m_barang');
		$cek_kode = $this->m_barang->cek_kode($kode);

		if($cek_kode->num_rows() > 0)
		{
			return FALSE;
		}
		return TRUE;
	}

	public function cek_titik($angka)
	{
		$pecah = explode('.', $angka);
		if(count($pecah) > 1){
			return FALSE;
		}
		return TRUE;
	}

	public function edit($id_barang = NULL)
	{
		if(!empty($id_barang))
		{
			
			$level = $this->session->userdata('ap_level');
			if($level == 'admin' OR $level == 'inventory')
			{
				if($this->input->is_ajax_request())
				{
					
					$this->load->model('m_barang');
					
					if($_POST)
					{
						$this->load->library('form_validation');

						$kode_barang 		= $this->input->post('kode_barang');
						$kode_barang_old	= $this->input->post('kode_barang_old');

						$callback			= '';
						if($kode_barang !== $kode_barang_old){
							$callback = "|callback_exist_kode[kode_barang]";
						}

						$this->form_validation->set_rules('kode_barang','Kode Barang','trim|required|alpha_numeric|max_length[40]'.$callback);
						$this->form_validation->set_rules('nama_barang','Nama Barang','trim|required|max_length[60]|alpha_numeric_spaces');
						$this->form_validation->set_rules('id_kategori_barang','Kategori','trim|required');
						$this->form_validation->set_rules('id_merk_barang','Merek','trim');
						$this->form_validation->set_rules('total_stok','Stok','trim|required|numeric|max_length[10]|callback_cek_titik[total_stok]');
						$this->form_validation->set_rules('harga','Harga','trim|required|numeric|min_length[3]|max_length[10]|callback_cek_titik[harga]');
						$this->form_validation->set_rules('keterangan','Keterangan','trim|max_length[2000]');
						
						$this->form_validation->set_message('required','%s harus diisi !');
						$this->form_validation->set_message('numeric','%s harus angka !');
						$this->form_validation->set_message('exist_kode','%s sudah ada di database, pilih kode lain yang unik !');
						$this->form_validation->set_message('cek_titik','%s harus angka, tidak boleh ada titik !');
						$this->form_validation->set_message('alpha_numeric_spaces', '%s Harus huruf / angka !');
						$this->form_validation->set_message('alpha_numeric', '%s Harus huruf / angka !');
						
						if($this->form_validation->run() == TRUE)
						{
							$nama 				= $this->input->post('nama_barang');
							$id_kategori_barang	= $this->input->post('id_kategori_barang');
							$id_merk_barang		= $this->input->post('id_merk_barang');
							$stok 				= $this->input->post('total_stok');
							$modal 				= $this->input->post('modal');
							$stok_new 				= $this->input->post('stok_new');
							$modal_new 				= $this->input->post('modal_new');

							$stokFinal =  $stok + $stok_new;
							$modalFinal = (($modal*$stok)+($modal_new*$stok_new))/$stokFinal;

							if(is_nan($modalFinal)){
							    $modalFinal = $modal;
							}
							// echo $modal."*".$stok."+".$modal_new."*".$stok_new."/".$stokFinal."=".$modalFinal;
							// exit;
							$harga 				= $this->input->post('harga');
							$keterangan 		= $this->clean_tag_input($this->input->post('keterangan'));

							$update = $this->m_barang->update_barang($id_barang, $kode_barang, $nama,  $id_kategori_barang, $id_merk_barang, $stokFinal, $modalFinal, $harga, $keterangan);
							if($update)
							{
								echo json_encode(array(
									'status' => 1,
									'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data barang berhasil diupdate.</div>"
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
						
						$this->load->model('m_kategori_barang');
						$this->load->model('m_merk_barang');

						
						$dt['barang'] 	= $this->m_barang->get_baris($id_barang)->row();
			// 			echo "asdasd";
			// exit;
						$dt['kategori'] = $this->m_kategori_barang->get_all();
						$dt['merek'] 	= $this->m_merk_barang->get_all();
						
						$this->load->view('barang/barang_edit', $dt);
					}
				}
			}
		}
	}

	public function list_merek()
	{
		$this->load->view('barang/merek/merek_data');
	}

	public function list_merek_json()
	{
		$this->load->model('m_merk_barang');
		$level 			= $this->session->userdata('ap_level');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_merk_barang->fetch_data_merek($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= $row['merk'];

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

	public function list_kategori()
	{
		$this->load->view('barang/kategori/kategori_data');
	}

	public function list_kategori_json()
	{
		$this->load->model('m_kategori_barang');
		$level 			= $this->session->userdata('ap_level');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_kategori_barang->fetch_data_kategori($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= $row['kategori'];

			if($level == 'admin' OR $level == 'inventory')
			{
				$nestedData[]	= "<a href='".site_url('barang/edit-kategori/'.$row['id_kategori_barang'])."' id='EditKategori'><i class='fa fa-pencil'></i> Edit</a>";
				$nestedData[]	= "<a href='".site_url('barang/hapus-kategori/'.$row['id_kategori_barang'])."' id='HapusKategori'><i class='fa fa-trash-o'></i> Hapus</a>";
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

	public function tambah_kategori()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('kategori','Kategori','trim|required|max_length[40]|alpha_numeric_spaces');				
				$this->form_validation->set_message('required','%s harus diisi !');
				$this->form_validation->set_message('alpha_numeric_spaces', '%s Harus huruf / angka !');

				if($this->form_validation->run() == TRUE)
				{
					$this->load->model('m_kategori_barang');
					$kategori 	= $this->input->post('kategori');
					$insert 	= $this->m_kategori_barang->tambah_kategori($kategori);
					if($insert)
					{
						echo json_encode(array(
							'status' => 1,
							'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> <b>".$kategori."</b> berhasil ditambahkan.</div>"
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
				$this->load->view('barang/kategori/kategori_tambah');
			}
		}
	}

	public function hapus_kategori($id_kategori_barang)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_kategori_barang');
				$hapus = $this->m_kategori_barang->hapus_kategori($id_kategori_barang);
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

	public function edit_kategori($id_kategori_barang = NULL)
	{
		if( ! empty($id_kategori_barang))
		{
			$level = $this->session->userdata('ap_level');
			if($level == 'admin' OR $level == 'inventory')
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_kategori_barang');
					
					if($_POST)
					{
						$this->load->library('form_validation');
						$this->form_validation->set_rules('kategori','Kategori','trim|required|max_length[40]|alpha_numeric_spaces');				
						$this->form_validation->set_message('required','%s harus diisi !');
						$this->form_validation->set_message('alpha_numeric_spaces', '%s Harus huruf / angka !');

						if($this->form_validation->run() == TRUE)
						{
							$kategori 	= $this->input->post('kategori');
							$insert 	= $this->m_kategori_barang->update_kategori($id_kategori_barang, $kategori);
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
						$dt['kategori'] = $this->m_kategori_barang->get_baris($id_kategori_barang)->row();
						$this->load->view('barang/kategori/kategori_edit', $dt);
					}
				}
			}
		}
	}

	public function cek_stok()
	{
		if($this->input->is_ajax_request())
		{
			$this->load->model('m_barang');
			$kode = $this->input->post('kode_barang');
			$stok = $this->input->post('stok');

			$get_stok = $this->m_barang->get_stok($kode);
			if($stok > $get_stok->row()->total_stok)
			{
				echo json_encode(array('status' => 0, 'pesan' => "Stok untuk <b>".$get_stok->row()->nama_barang."</b> saat ini hanya tersisa <b>".$get_stok->row()->total_stok."</b> !"));
			}
			else
			{
				echo json_encode(array('status' => 1));
			}
		}
	}



	#---------------- Bundling
	public function list_bundling()
	{
		$this->load->view('barang/bundling/bundling_data');
	}

	public function list_bundling_json()
	{
	
		$this->load->model('m_bundling_barang');
		$level 			= $this->session->userdata('ap_level');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_bundling_barang->fetch_data_bundling($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);


		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= $row['id_bundling'];
			$nestedData[]	= $row['kode_barang'];
			$nestedData[]	= $row['nama_barang'];
			$nestedData[]	= $row['kode_barang_bundling'];
			$nestedData[]	= $row['nama_barang_bundling'];

			if($level == 'admin' OR $level == 'inventory')
			{
				$nestedData[]	= "<a href='".site_url('barang/hapus-bundling/'.$row['id_bundling'])."' id='HapusBundling'><i class='fa fa-trash-o'></i> Hapus</a>";
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

	public function tambah_bundling()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($_POST)
			{
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('id_barang','Nama Barang Utama','required');
				$this->form_validation->set_rules('id_barang_bundling','Nama Barang Bundling','required');				
				
				$this->form_validation->set_message('required','%s harus diisi !');

				if($this->form_validation->run() == TRUE)
				{
					
					$this->load->model('m_bundling_barang');
					$id_barang 				= $this->input->post('id_barang');
					$id_barang_bundling 	= $this->input->post('id_barang_bundling');
					$insert = $this->m_bundling_barang->tambah_bundling($id_barang,$id_barang_bundling);
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
					$this->input_error();
				}
			}
			else
			{
				$this->load->model('m_barang');				
				$data['barang'] =  $this->m_barang->getBarangdd();
				$this->load->view('barang/bundling/bundling_tambah',$data);
			}
		}
	}

	public function hapus_bundling($id_bundling)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_bundling_barang');
				$hapus = $this->m_bundling_barang->hapus_bundling($id_bundling);
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

	// public function edit_merek($id_merk_barang = NULL)
	// {
	// 	if( ! empty($id_merk_barang))
	// 	{
	// 		$level = $this->session->userdata('ap_level');
	// 		if($level == 'admin' OR $level == 'inventory')
	// 		{
	// 			if($this->input->is_ajax_request())
	// 			{
	// 				$this->load->model('m_merk_barang');
					
	// 				if($_POST)
	// 				{
	// 					$this->load->library('form_validation');
	// 					$this->form_validation->set_rules('merek','Merek','trim|required|max_length[40]|alpha_numeric_spaces');				
	// 					$this->form_validation->set_message('required','%s harus diisi !');
	// 					$this->form_validation->set_message('alpha_numeric_spaces', '%s Harus huruf / angka !');

	// 					if($this->form_validation->run() == TRUE)
	// 					{
	// 						$merek 	= $this->input->post('merek');
	// 						$insert = $this->m_merk_barang->update_merek($id_merk_barang, $merek);
	// 						if($insert)
	// 						{
	// 							echo json_encode(array(
	// 								'status' => 1,
	// 								'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data berhasil diupdate.</div>"
	// 							));
	// 						}
	// 						else
	// 						{
	// 							$this->query_error();
	// 						}
	// 					}
	// 					else
	// 					{
	// 						$this->input_error();
	// 					}
	// 				}
	// 				else
	// 				{
	// 					$dt['merek'] = $this->m_merk_barang->get_baris($id_merk_barang)->row();
	// 					$this->load->view('barang/merek/merek_edit', $dt);
	// 				}
	// 			}
	// 		}
	// 	}
	// }
}