<?php 

class User extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		 $this->load->model('user_model');
	}
	function check_email()
    {
        $email = $this->input->post('email');
        $where = array('email' => $email);
        //kiêm tra xem username đã tồn tại chưa
        if($this->user_model->check_exists($where))
        {
            //trả về thông báo lỗi
            $this->form_validation->set_message(__FUNCTION__, 'Tài khoản đã tồn tại');
            return false;
        }
        return true;
    }
    
    /*
     * Thêm mới quản trị viên
     */
    function register()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');
        
        //neu ma co du lieu post len thi kiem tra
        if($this->input->post())
        {
            $this->form_validation->set_rules('name', 'Tên', 'required|min_length[8]');
            $this->form_validation->set_rules('email', 'Email đăng nhập', 'required|valid_email|callback_check_email');
            $this->form_validation->set_rules('password', 'Mật khẩu', 'required|min_length[6]');
            $this->form_validation->set_rules('re_password', 'Nhập lại mật khẩu', 'matches[password]');
            $this->form_validation->set_rules('phone', 'Số điện thoại', 'required');
            $this->form_validation->set_rules('address', 'Địa chỉ', 'required');
  
            //nhập liệu chính xác
            if($this->form_validation->run())
            {
                //them vao csdl
                $password = $this->input->post('password');
                $password = md5($password);
                
                $data = array(
                    'name'     => $this->input->post('name'),
                    'email'    => $this->input->post('email'),
                    'phone'    => $this->input->post('phone'),
                    'address'  => $this->input->post('address'),
                    'password' => $password,
                    'created'  => now(),
                );
                if($this->user_model->create($data))
                {
                    //tạo ra nội dung thông báo
                    $this->session->set_flashdata('message', 'Đăng ký thành viên thành công');
                }else{
                    $this->session->set_flashdata('message', 'Không thêm được');
                }
                //chuyen tới trang danh sách quản trị viên
                redirect(site_url());
            }
        }
        
        //hiển thị ra view
        $this->data['temp'] = 'site/user/register';
        $this->load->view('site/layout', $this->data);
    }
   function login()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');
        if($this->input->post())
        {	
        	 $this->form_validation->set_rules('email', 'Email đăng nhập', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Mật khẩu', 'required|min_length[6]');
            $this->form_validation->set_rules('login' ,'login', 'callback_check_login');
            if($this->form_validation->run())
            {
            	/*lay thong tin*/
            	$user = $this-> _get_user_info();
                $this->session->set_userdata('user_id_login', $user->id);
                  $this->session->set_flashdata('message', 'Đăng nhập thành công');
                redirect(base_url());
            }
        }
        
        $this->data['temp']= 'site/user/login';
       $this->load->view('site/layout', $this->data);
    }
    
    /*
     * Kiem tra username va password co chinh xac khong
     */
    function check_login()
    {
       	$user = $this-> _get_user_info();
        if($user)
        {
            return true;
        }
        $this->form_validation->set_message(__FUNCTION__, 'Không đăng nhập thành công');
        return false;
    }

    private function _get_user_info(){
    	$email = $this->input->post('email');
        $password = $this->input->post('password');
        $password = md5($password);
        $where = array('email' => $email , 'password' => $password);
        $user = $this->user_model->get_info_rule($where);
        return $user;
    }
      function logout()
    {
        if($this->session->userdata('user_id_login'))
        {
            $this->session->unset_userdata('user_id_login');
        }
        redirect();
    }
}
 ?>