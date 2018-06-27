<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 03/11/2017
 * Time: 17:01
 */

class Cursos_model extends MY_Model
{
    private $limitPagina = 10;
    public function __construct(){
        parent::__construct();
        $this->table_name = "pronatec.tb_curso";
        $this->key_field = "ci_curso";
    }

    public function get_cursos_by_filter($objTerm, $page = 1){
        $objTerm = (Object) $objTerm;
        $db = $this->db;

        $db->select('*');
        $db->from('pronatec.tb_curso tc');
        $db->join("pronatec.tb_eixo te", "te.ci_eixo = tc.cd_eixo");
        if(isset($objTerm->search1)) $db->where('cd_eixo', $objTerm->search1);
        if(@$objTerm->search2) $db->like('nm_curso', $objTerm->search2);

        $query = $db->get();
        $data['num_rows'] = $query->num_rows();

        $db->select('ci_curso, cd_eixo, nr_codigo_censo, nm_curso, cd_usuario, dt_criacao, cd_usuario_modificacao, dt_modificacao, , ds_eixo');
        $db->from('pronatec.tb_curso tc');
        $db->join("pronatec.tb_eixo te", "te.ci_eixo = tc.cd_eixo");

        if(isset($objTerm->search1) && $objTerm->search1 != 0) $db->where('cd_eixo', $objTerm->search1);
        if(@$objTerm->search2) $db->like('nm_curso', $objTerm->search2);

        $db->order_by("nm_curso");
        $db->limit($this->limitPagina,($page - 1) * $this->limitPagina);

        $query = $db->get();
        //trace($db->last_query());
        $data['result']   = $query->result();
        return (Object) $data;

    }

    public function get_id($id){
        $db = $this->db;

        $db->select('ci_curso as id, cd_eixo as eixos, nr_codigo_censo, nm_curso as curso, cd_usuario, dt_criacao, 
                       cd_usuario_modificacao, dt_modificacao, ds_ementa as ementa, ds_objetivo_geral as objgeral, 
                       ds_objetivo_espec as objespec, ds_avaliacao as avaliacao, fl_ativo as ativo, nr_carga_horaria_presencial as chpresencial, 
                       nr_carga_horaria_distancia as chdistancia, ds_eixo');
        $db->from('pronatec.tb_curso tc');
        $db->join("pronatec.tb_eixo te", "te.ci_eixo = tc.cd_eixo");
        $db->where('tc.ci_curso', $id);

        $query = $db->get();

        return (Object) $query->row();
    }

    public function get_eixos(){
        $db = $this->db;
        $db->select('*');
        $db->from('pronatec.tb_eixo');

        $query = $db->get();

        return (Object) $query->result();
    }

    public function nextval(){
        $db = $this->db;
        $query = $db->query("select nextval('pronatec.tb_curso_ci_curso_seq'::regclass) as num");
        $rowID = $query->row();
        return $rowID->num;
    }

    public function save($post)
    {
        $usuario_aut = (Object) $this->session->get_userdata('user');

        $db = $this->db;
        $ci_curso = $this->nextval();
        $nm_curso = addslashes(strtoupper($post['curso']));
        $cd_eixo = $post['eixos'];
        $nr_codigo_censo =  date('Y').$cd_eixo.$ci_curso;
        $cd_usuario = $usuario_aut->user->ci_usuario;
        $dt_criacao = 'now()';
        $ds_ementa = addslashes($post['ementa']);
        $ds_objetivo_geral = addslashes($post['objgeral']);
        $ds_objetivo_espec = addslashes($post['objespec']);
        $ds_avaliacao = addslashes($post['avaliacao']);
        $ds_bibliografia = addslashes($post['bibliografia']);
        $fl_ativo = (isset($post['ativo']) ? 'true' : 'false');
        $nr_carga_horaria_presencial = $post['chpresencial'];
        $nr_carga_horaria_distancia = $post['chdistancia'];

        $data = array(
            'ci_curso' => $ci_curso,
            'cd_eixo' => $cd_eixo,
            'nr_codigo_censo' => $nr_codigo_censo,
            'nm_curso' => $nm_curso,
            'cd_usuario' => $cd_usuario,
            'dt_criacao' => $dt_criacao,
            'ds_ementa' => $ds_ementa,
            'ds_objetivo_geral' => $ds_objetivo_geral,
            'ds_objetivo_espec' => $ds_objetivo_espec,
            'ds_avaliacao' => $ds_avaliacao,
            'ds_bibliografia' => $ds_bibliografia,
            'fl_ativo' => $fl_ativo,
            'nr_carga_horaria_presencial' => $nr_carga_horaria_presencial,
            'nr_carga_horaria_distancia' => $nr_carga_horaria_distancia
        );

        if($db->insert('pronatec.tb_curso', $data)){
            $return = Array("valid"=>TRUE, "messeger"=>"Salvo com sucesso!", "ci_curso"=>$ci_curso);
            return $return;
        }else{
            $return = Array("valid"=>FALSE, "messeger"=>"Ocorreu um erro ao salvar!");
            return $return;
        }
    }

    public function alterar($id, $post)
    {
        $usuario_aut = (Object) $this->session->get_userdata('user');

        $db = $this->db;
        $nm_curso = addslashes($post['curso']);
        $cd_eixo = $post['eixos'];
        $cd_usuario_modificacao = $usuario_aut->user->ci_usuario;
        $dt_modificacao = 'now()';
        $ds_ementa = addslashes($post['ementa']);
        $ds_objetivo_geral = addslashes($post['objgeral']);
        $ds_objetivo_espec = addslashes($post['objespec']);
        $ds_avaliacao = addslashes($post['avaliacao']);
        $ds_bibliografia = addslashes($post['bibliografia']);
        $fl_ativo = (isset($post['ativo']) ? 'true' : 'false');
        $nr_carga_horaria_presencial = $post['chpresencial'];
        $nr_carga_horaria_distancia = $post['chdistancia'];

        $data = array(
            'cd_eixo' => $cd_eixo,
            'nm_curso' => $nm_curso,
            'ds_ementa' => $ds_ementa,
            'ds_objetivo_geral' => $ds_objetivo_geral,
            'ds_objetivo_espec' => $ds_objetivo_espec,
            'ds_avaliacao' => $ds_avaliacao,
            'ds_bibliografia' => $ds_bibliografia,
            'fl_ativo' => $fl_ativo,
            'nr_carga_horaria_presencial' => (integer) $nr_carga_horaria_presencial,
            'nr_carga_horaria_distancia' => (integer) $nr_carga_horaria_distancia,
            'cd_usuario_modificacao' => $cd_usuario_modificacao,
            'dt_modificacao' => $dt_modificacao
        );

        $db->where('ci_curso', $id);
        if($db->update('pronatec.tb_curso', $data)){
            $return = Array("valid"=>TRUE, "messeger"=>"Alteração realizada com sucesso!", "ci_curso"=>$id);
            return $return;
        }else{
            $return = Array("valid"=>FALSE, "messeger"=>"Ocorreu um erro ao salvar!");
            return $return;
        }
    }

}