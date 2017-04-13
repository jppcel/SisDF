<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model para a página inicial de listagem de chamados
 *
 * @author André Luiz Girol - Departamento de Física - FFCLRP
 */
class Relator_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /*
     * Busca todos os chamados abertos por um
     * determinado usuário
     * 
     * Busca chamados de todas as seções
     */
    
    public function get_os_by_user($id_usuario , $id_status = NULL){
        $this->db->select('id_os, resumo, data_abertura, nome_status, bs_label, os_status.icone, nome_secao');
        $this->db->from('ordem_servico');
        $this->db->join('os_status', 'id_status = id_status_fk');
        $this->db->join('secao', 'id_secao = id_secao_fk');

        $this->db->where('id_usuario_fk', $id_usuario);

        if(isset($id_status)){
            $this->db->where('id_status_fk', $id_status);
        }


        $this->db->order_by('id_status','ASC');
        
        $result = $this->db->get();
        
        return $result->result_array();
        
    }
    

}