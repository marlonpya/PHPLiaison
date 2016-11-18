<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Empresa extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function registrarEmpresa($data) {
		$data = array(
			'imagen'			=> $data['imagen'],
			
		);
		
		return false;
	}
	
	public function validarCorreoExistente($data) {
		$resultado = false;
		$consulta = $this->db->where('EMP_ID', $data);
		if($consulta->num_rows() < 0) {
			$resultado = true;
		}
		return $resultado;
	}
}

?>