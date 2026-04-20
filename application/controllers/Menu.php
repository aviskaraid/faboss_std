<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller 
{ 
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
        
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu','Menu', 'required');

        if( $this->form_validation->run() == false ){
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'menu' => $this->input->post('menu')
            ];
            $this->db->insert('user_menu', $data);
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">New menu added 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                redirect('menu');
        }
    }

    public function editmenu()
    {
        $this->load->model('Menu_model','menu');
        $this->menu->editMenu();
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu has been updated!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
            redirect('menu');
    }

    public function deletemenu($id)
    {
        $this->load->model('Menu_model','menu');
        $data['menu'] = $this->menu->getMenuById($id);
        $menu = $data['menu']['menu'];
        $this->menu->deleteMenu($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu '. $menu .' has been deleted!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
                redirect('menu');
    }

    public function submenu($id)
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
        $this->load->model('Menu_model','menu');

        $data['menu'] = $this->db->get_where('user_menu', ['id' => $id])->row_array();
        $data['subMenu'] = $this->menu->getSubMenu($id);

        $this->form_validation->set_rules('title','Title', 'required');
        $this->form_validation->set_rules('menu_id','Menu', 'required');
        $this->form_validation->set_rules('url','URL', 'required');

        if($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'is_active' => $this->input->post('is_active')
            ];
            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">New Sub Menu added <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                redirect('menu/submenu/'.$id);
        }
    }

    public function editsubmenu($id)
    {
        $data['title'] = 'Edit Submenu';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
        
        $this->load->model('Menu_model','menu');
        $data['subMenuById'] = $this->menu->getSubmenuById($id);

        $this->form_validation->set_rules('title','Title', 'required');
        $this->form_validation->set_rules('url','URL', 'required');

        if($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/editsubmenu', $data);
            $this->load->view('templates/footer');
        } else {
            $this->menu->editSubmenu();
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Submenu has been updated!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                redirect('menu/submenu/'.$data['subMenuById']['menu_id']);
        }
    }

    public function deletesubmenu($id)
    {
        $this->load->model('Menu_model','menu');
        $data['subMenuById'] = $this->menu->getSubmenuById($id);
        $titleSubMenu = $data['subMenuById']['title'];
        $this->menu->deleteSubmenu($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Submenu '. $titleSubMenu .' has been deleted!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
                redirect('menu/submenu/'.$data['subMenuById']['menu_id']);
    }
}