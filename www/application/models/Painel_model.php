<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model para a página inicial de listagem de chamados
 *
 * @author André Luiz Girol - Departamento de Física - FFCLRP
 */
class Painel_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /*
     * Busca todos os chamados abertos por um
     * determinado usuário (dono)
     *
     * - Busca chamados de todas as seções
     * - Filtra chamados por Status
     */

    public function get_os_by_owner($id_usuario , $id_status = NULL){
        $this->db->select('id_os, resumo, data_abertura, nome_status, bs_label, os_status.icone, nome_secao, secao.icone as icone_secao');
        $this->db->from('ordem_servico');
        $this->db->join('os_status', 'id_status = id_status_fk');
        $this->db->join('secao', 'id_secao = id_secao_fk');

        $this->db->where('id_relator_fk', $id_usuario);

        if(isset($id_status)){
            $this->db->where('id_status_fk', $id_status);
        }


        $this->db->order_by('id_status','ASC');

        $result = $this->db->get();

        return $result->result_array();

    }

    public function get_os_by_secao($id_secao , $id_status = NULL, $is_index = FALSE){
        $this->db->select('id_os, resumo, data_abertura, nome_status, bs_label, os_status.icone, nome_secao, secao.icone as icone_secao');
        $this->db->from('ordem_servico');
        $this->db->join('os_status', 'id_status = id_status_fk');
        $this->db->join('secao', 'id_secao = id_secao_fk');

        $this->db->where('id_secao', $id_secao);

        // Se é o index, mostra apenas os que estão abertos e em andamento
        // Índices 3 e 6 são para: 
        // Atendido e Cancelado respectivamente
        if ($is_index){
            $this->db->where_not_in('id_status', array(3,6));
        }

        if(!empty($id_status)){
            $this->db->where('id_status_fk', $id_status);
        }


        $this->db->order_by('data_abertura','ASC');

        $result = $this->db->get();

        return $result->result_array();

    }
    /*
        Busca todas as OS.
        Usado no painel do gestor da unidade
    */
    public function get_all_os($id_status = NULL){
        $this->db->select('id_os, resumo, data_abertura, nome_status, bs_label, os_status.icone, nome_secao, secao.icone as icone_secao');
        $this->db->from('ordem_servico');
        $this->db->join('os_status', 'id_status = id_status_fk');
        $this->db->join('secao', 'id_secao = id_secao_fk');


        if(!empty($id_status)){
            $this->db->where('id_status_fk', $id_status);
        } else {
            $this->db->where_in('id_status',array(1,2,4));
        }

        $this->db->order_by('data_abertura','ASC');

        $result = $this->db->get();

        return $result->result_array();

    }
}
