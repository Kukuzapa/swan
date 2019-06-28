<?php
	class Pages extends CI_Controller {

		public function __construct()
		{
			header('Access-Control-Allow-Origin: *');
			header('Access-Control-Allow-Headers: Content-Type');
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
			parent::__construct();

			$this->load->model('library');

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters( '', '' );

			$this->form_validation->set_rules( 'author', 'author', 'required|min_length[3]', [ 
				'required' => 'Не указано имя автора.',
				'min_length' => 'Имя автора не может быть меньше трёх символов.'
			] );
			$this->form_validation->set_rules( 'name', 'name', 'required', [ 'required' => 'Не указано название.' ] );
			$this->form_validation->set_rules( 'genre', 'genre', 'required', [ 'required' => 'Не указан жанр.' ] );
			$this->form_validation->set_rules( 'year', 'year', 'required|less_than_equal_to[' . date("Y") . ']', [ 
				'required' => 'Не указан год.',
				'less_than_equal_to' => 'Поле год должно состоять из цифр и быть меньше либо равным текущему.'
			] );
		}

		public function index()
		{
			$this->load->view('front/index'); 
		}

		public function del()
		{
			$fin = [];

			$data = json_decode( $this->input->raw_input_stream, true );

			if ( count( $data['deleted'] ) == 0 ) {
				$fin['error'] = 'Список удаляемых книг не может быть пустым';
			} else {
				$this->library->del_book( $data['deleted'] );
				$fin['success'] = 'success';
			}

			echo json_encode( $fin );
		}

		public function upd()
		{
			$fin = [];

			$data = json_decode( $this->input->raw_input_stream, true );

			$id = $data['id'];

			unset( $data['id'] );

			$this->form_validation->set_data( $data );

			if ($this->form_validation->run() == FALSE) {
				$fin['error'] = validation_errors();
			} else {
				$this->library->upd_book( $data, $id );
				$fin['success'] = 'success';
			}

			echo json_encode( $fin );
		}

		public function add()
		{
			$fin = [];

			$data = json_decode( $this->input->raw_input_stream, true );

			$this->form_validation->set_data( $data );

			if ($this->form_validation->run() == FALSE) {
				$fin['error'] = validation_errors();
			} else {
				$this->library->add_book( $data );
				$fin['success'] = 'success';
			}

			echo json_encode( $fin );
		}
		
		public function library()
		{
			$limit = $this->uri->segment( 3 );
			$offset = ( $this->uri->segment( 4 ) - 1) * $limit;

			$bookList = $this->library->get_library( $limit, $offset );

			echo json_encode($bookList);
		}
	}
?>