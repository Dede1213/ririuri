<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------
 * CLASS NAME : Secure
 * ------------------------------------------------------------------------
 *
 * @author     Muhammad Akbar <muslim.politekniktelkom@gmail.com>
 * @copyright  2016
 * @license    http://aplikasiphp.net
 *
 */

class Secure extends MY_Controller 
{
	
	
	public function tambah()
	{
		$this->load->helper('captcha');
		$cap = $this->buat_captcha();
        $data['cap_img'] = $cap['image'];
		$this->session->set_userdata('kode_captcha', $cap['word']);
		
		$this->load->view('secure/registrasi', $data);
	}


	
    public function buat_captcha(){
        $vals = array(
            'img_path' => 'captcha/',
            'img_url' => base_url().'captcha/',
            'font_path' => FCPATH . 'captcha/font/1.ttf',
            'font_size' => 120,
            'img_width' => '160',
            'img_height' => 40,
			'expiration' => 5,
			'pool'		=> '0123456789',
			'word_length' => 5
        );
        $cap = create_captcha($vals);
        return $cap;
    }
 
    
 
    public function cek_captcha($input){
        if($input === $this->session->userdata('kode_captcha')){
            return TRUE;
        }else{
            $this->form_validation->set_message('cek_captcha', '%s yang anda input salah!');
            return FALSE;
        }
	}


	public function act_tambah()
	{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('username','Username','trim|required|max_length[40]|callback_exist_username[username]|alpha_numeric');
				$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[60]');
				$this->form_validation->set_rules('nama','Nama Toko','trim|required|max_length[50]|alpha_spaces');

				$this->form_validation->set_rules('kode_captcha', 'Kode Captcha', 'required|callback_cek_captcha');
        		$this->form_validation->set_error_delimiters('<div style="border: 1px solid: #999999; background-color: #ffff99;">', '</div>');
				
				$this->form_validation->set_message('required','%s harus diisi !');
				$this->form_validation->set_message('exist_username','%s sudah ada di database, pilih username lain yang unik !');
				$this->form_validation->set_message('alpha_spaces', '%s harus alphabet');
				$this->form_validation->set_message('alpha_numeric', '%s Harus huruf / angka !');

				if($this->form_validation->run() == TRUE)
				{
					$this->load->model('m_user');

					$username 	= $this->input->post('username');
					$password 	= $this->input->post('password');
					$nama		= $this->input->post('nama');
					$id_akses	= '1'; //Registrasi Baru jadi admin untuk tokonya sendiri
					$status		= 'Aktif';

					$insertToko = $this->m_user->registrasi_toko($nama);
					$id_toko =  $this->db->insert_id();
					$insert = $this->m_user->registrasi_baru($id_toko,$username, $password, $nama, $id_akses, $status);
					

					if($insert > 0)
					{
						$this->session->unset_userdata('kode_captcha');
						echo json_encode(array(
							'status' => 1,
							'pesan' => "<i class='fa fa-check' style='color:green;'></i> Toko berhasil didaftarkan, Silahkan LOGIN."
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

	public function exist_username($username)
	{
		$this->load->model('m_user');
		$cek_user = $this->m_user->cek_username($username);

		if($cek_user->num_rows() > 0)
		{
			return FALSE;
		}
		return TRUE;
	}

	public function index()
	{
		if($this->input->is_ajax_request())
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username','Username','trim|required|min_length[3]|max_length[40]');
			$this->form_validation->set_rules('password','Password','trim|required|min_length[3]|max_length[40]');
			$this->form_validation->set_message('required','%s harus diisi !');
			
			if($this->form_validation->run() == TRUE)
			{
				$username 	= $this->input->post('username');
				$password	= $this->input->post('password');

				$this->load->model('m_user');
				$validasi_login = $this->m_user->validasi_login($username, $password);

				if($validasi_login->num_rows() > 0)
				{
					$data_user = $validasi_login->row();

					$session = array(
						'ap_id_user' => $data_user->id_user,
						'ap_password' => $data_user->password,
						'ap_nama' => $data_user->nama,
						'ap_level' => $data_user->level,
						'ap_level_caption' => $data_user->level_caption,
						'id_toko' => $data_user->id_toko 
					);

					

					$this->session->set_userdata($session);	

					// insert log dede
					$this->m_user->insert_log();
					//end

					$URL_home = site_url('penjualan');
					if($data_user->level == 'inventory')
					{
						$URL_home = site_url('barang');
					}
					if($data_user->level == 'keuangan')
					{
						$URL_home = site_url('penjualan/history');
					}

					$json['status']		= 1;
					$json['url_home'] 	= $URL_home;
					echo json_encode($json);
				}
				else
				{
					$this->query_error("Login Gagal, Cek Kombinasi Username & Password !");
				}
			}
			else
			{
				$this->input_error();
			}
		}
		else
		{
			$this->load->view('secure/login_page');
		}
	}

	

	function logout()
	{
		$this->session->unset_userdata('ap_id_user');
		$this->session->unset_userdata('ap_password');
		$this->session->unset_userdata('ap_nama');
		$this->session->unset_userdata('ap_level');
		$this->session->unset_userdata('ap_level_caption');
		redirect();
	}
}
