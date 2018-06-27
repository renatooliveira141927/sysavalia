<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 22/06/2018
 * Time: 22:48
 */

class Gabarito extends MY_Controller
{
    private $transacao = "manter_gabarito";

    public function __construct()
    {
        parent::__construct();
        if (!$this->is_logged()) {
            $this->lang->load('project');
            send_alert($this->lang->line('restrict_access'), 5000, 'warning');
            redirect(base_url());
        }
        addCSS(array("plugins/iCheck/custom"));
        addJS(array("jquery.mask.min", 'views/curso', "plugins/iCheck/icheck.min"));
        $this->load->model("gabarito_model");
    }

    public function index()
    {
        if (isRead($this->transacao)) {
            $this->buscar();
        } else {
            send_alert($this->lang->line('restrict_access'));
            redirect(base_url());
        }
    }

    public function buscar()
    {
        $page = isset($_GET['page']) ? $this->input->get('page', TRUE) : 1;
        if (isRead($this->transacao)) {

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                $page, "Gabarito",
                array('Gabarito', 'Pesquisa', 'Pagina ' . $page),
                'gabarito/novo', 'list-alt', false, isCreate($this->transacao)
            );
            $this->load_view('avalia/gabarito_view.php', $data);
        }else{
            send_alert($this->lang->line('restrict_access'));
            redirect(base_url());
        }

    }

}