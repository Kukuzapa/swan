<?php
	class Library extends CI_Model {

		public function __construct()
		{
			$this->load->database();
		}

		public function get_library( $limit, $offset )
		{
			$fin['count'] = $this->db->count_all( 'books' );
			$fin['list'] = $this->db->get( 'books', $limit, $offset )->result_array();

			return $fin;
		}

		public function del_book( $data )
		{
			$this->db->where_in( 'id', $data );
			$this->db->delete( 'books' );
		}

		public function add_book( $data )
		{
			$this->db->insert('books', $data );
		}

		public function upd_book( $data, $id )
		{
			$this->db->where( 'id', $id );
			$this->db->update( 'books', $data );
		}
	}
?>