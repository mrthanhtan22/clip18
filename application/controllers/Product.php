<?php 
	class Product extends MY_Controller
	{
		
		function __construct()
		{
			parent:: __construct();
			$this->load->model('product_model');
		}

		function catalog(){
			
			 
			$id = intval($this->uri->rsegment(3));
			$this->load->model('catalog_model');
			$catalog = $this->catalog_model->get_info($id);

			if (!$catalog) {
				redirect();
			}
			$this->data['catalog'] = $catalog;

			$input = array();

		if ($catalog->parent_id == 0) {
			$input_catalog = array();
			$input_catalog['where'] = array('parent_id' => $id);
			$catalog_subs = $this->catalog_model->get_list($input_catalog);
		 if(!empty($catalog_subs)) {
			$catalog_subs_id = array();
			foreach ($catalog_subs as $sub) {
				$catalog_subs_id[] = $sub->id;
			}
			$this->db->where_in('catalog_id', $catalog_subs_id);
		} else{
			$input['where'] = array('catalog_id'=>$id);
		}
		}else{
			$input['where'] = array('catalog_id'=>$id);
		}
		


			//lay tong so luong ta ca cac san pham trong websit
        	$total_rows = $this->product_model->get_total($input);
        	$this->data['total_rows'] = $total_rows;
        
        //load ra thu vien phan trang
        $this->load->library('pagination');
        $config = array();
        $config['total_rows'] = $total_rows;//tong tat ca cac san pham tren website
        $config['base_url']   = base_url('product/catalog/'.$id); //link hien thi ra danh sach san pham
        $config['per_page']   = 6;//so luong san pham hien thi tren 1 trang
        $config['uri_segment'] = 4;//phan doan hien thi ra so trang tren url
        $config['next_link']   = 'Trang kế tiếp';
        $config['prev_link']   = 'Trang trước';
        //khoi tao cac cau hinh phan trang
        $this->pagination->initialize($config);
        
        $segment = $this->uri->segment(4);
        $segment = intval($segment);

        if(isset($catalog_subs_id)){
        	$this->db->where_in('catalog_id', $catalog_subs_id);
        }
        
       
        $input['limit'] = array($config['per_page'], $segment); 
			

        $list = $this->product_model->get_list($input);
        $this->data['list'] = $list;

        /*layout master*/
        $this->data['temp'] = 'site/product/catalog';
        $this->load->view('site/layout', $this->data);


		}
		function view(){

			$id = intval($this->uri->rsegment(3));
			$product = $this->product_model->get_info($id);
			if(!$product) redirect();
        		$this->data['product'] = $product;

        	  //lay thong tin cua danh mục san pham
			    $catalog = $this->catalog_model->get_info($product->catalog_id);
			    $this->data['catalog'] = $catalog;
			    
			   // lay danh sach hinh anh
			   $image_list = @json_decode($product->image_list);
			   $this->data['image_list'] = $image_list;

			/*load layout master*/
			$this->data['temp'] = 'site/product/view';
			$this->load->view('site/layout', $this->data);
		}
		function search(){
			if ($this->uri->rsegment('3') == 1) {
				$key = $this->input->get('term');
			}else{
				$key = $this->input->get('key-search');
			}

			$input = array();
			$input['like'] =array('name', $key);
			$list = $this->product_model->get_list($input);
			$this->data['list'] = $list;

		}
	}
 ?>