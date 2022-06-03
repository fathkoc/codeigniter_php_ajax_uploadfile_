<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Homepage extends Veripay_Controller
{

    function __construct()
    {
        parent:: __construct();

        $this->result = new StdClass();
        $this->result->status = false;
        $this->load->model( $this->router->fetch_class() . '_model', 'model');

    }

    public function response()
    {
        echo json_encode($this->result);
        exit();
    }

    public function index()
    {
        $data = new stdClass();
        $data->site_settings = new stdClass();
        $data->active = "homepage";
        $data->contact = $this->model->get_contact();
        $data->slider = $this->model->get_slider();
        $data->banner = $this->model->get_banner();
        $data->what_we = $this->model->get_what_we();
        $data->references = $this->model->get_references();
        $data->homepage_blog = $this->model->get_homepage_blog();
        $data->video = $this->model->get_video();
        $data->site_settings = $this->model->get_site_settings(1);
        $this->load->view('header',$data);
        $this->load->view('index');
        $this->load->view('footer');
    }

    public function blog()
    {
        $data = new stdClass();
        $data->site_settings = new stdClass();
        $data->active = "blog";
        $data->contact = $this->model->get_contact();
        $post_data = new stdClass();

        $post_data->search_word = $this->input->get('search_word',true);

        $pagination_config = new stdClass();
        $this->load->library('pagination');
        $page = $this->input->get('per_page',true);
        if (empty($page)) {
            $data->current_page = 1;
        } else {
            $data->current_page = $this->input->get('per_page',true) ?? 1;
        }
        $pagination_model_config = ['page' =>9, 'current_page' => $data->current_page];

        $data->list = $this->model->get_blog($pagination_model_config,$post_data);

        $pagination_config->url    		= site_url('blog');

        $pagination_config->per_page   		= $pagination_model_config['page'];
        $pagination_config->total_rows 	= $data->list->total_rows;
        $this->pagination->initialize(create_front_pagination_config($pagination_config));
        $data->pagination_text = $this->pagination->create_links();
        $data->tickets = $this->model->get_selected_blog_tickets();
        $data->site_settings = $this->model->get_site_settings(3);

        $this->load->view('header',$data);
        $this->load->view('blog');
        $this->load->view('footer');
    }

    public function blog_ticket($slug = '')
    {

        if(empty($slug)) {
            redirect(site_url('blog'));
        }

        $data = new stdClass();
        $data->site_settings = new stdClass();
        $data->contact = $this->model->get_contact();
        $post_data = new stdClass();
        $data->active = "blog";
        $post_data->ticket = $this->input->get('ticket',true);

        $pagination_config = new stdClass();
        $this->load->library('pagination');
        $page = $this->input->get('per_page',true);
        if (empty($page)) {
            $data->current_page = 1;
        } else {
            $data->current_page = $this->input->get('per_page',true) ?? 1;
        }
        $pagination_model_config = ['page' =>9, 'current_page' => $data->current_page];

        $data->list = $this->model->get_ticket_blog($pagination_model_config,$slug);

        $pagination_config->url    		= site_url('blog-etiket/'.$slug);

        $pagination_config->per_page   		= $pagination_model_config['page'];
        $pagination_config->total_rows 	= $data->list->total_rows;
        $this->pagination->initialize(create_front_pagination_config($pagination_config));
        $data->pagination_text = $this->pagination->create_links();
        $data->site_settings = $this->model->get_site_settings(3);
        $this->load->view('header',$data);
        $this->load->view('blog');
        $this->load->view('footer');
    }


    public function blog_category($slug = '')
    {

        if(empty($slug)) {
            redirect(site_url('blog'));
        }

        $data = new stdClass();
        $data->site_settings = new stdClass();
        $data->contact = $this->model->get_contact();
        $data->active = "blog";
        $pagination_config = new stdClass();
        $this->load->library('pagination');
        $page = $this->input->get('per_page',true);
        if (empty($page)) {
            $data->current_page = 1;
        } else {
            $data->current_page = $this->input->get('per_page',true) ?? 1;
        }
        $pagination_model_config = ['page' =>9, 'current_page' => $data->current_page];

        $data->list = $this->model->get_category_blog($pagination_model_config,$slug);

        $pagination_config->url    		= site_url('blog-kategori/'.$slug);

        $pagination_config->per_page   		= $pagination_model_config['page'];
        $pagination_config->total_rows 	= $data->list->total_rows;
        $this->pagination->initialize(create_front_pagination_config($pagination_config));
        $data->pagination_text = $this->pagination->create_links();
        $data->site_settings = $this->model->get_site_settings(3);
        $this->load->view('header',$data);
        $this->load->view('blog');
        $this->load->view('footer');
    }

    public function blog_detail($slug = '')
    {
        if (empty($slug)) {
            redirect(site_url());
        }
        $data = new stdClass();
        $data->site_settings = new stdClass();
        $data->active = "blog";
        $data->contact = $this->model->get_contact();
        $data->categories = $this->model->get_categories();
        if($data->selected_blog = $this->model->get_selected_blog($slug)) {
            $data->tickets = $this->model->get_selected_blog_tickets($data->selected_blog->id);
            $data->site_settings->title = $data->selected_blog->site_title;
            $data->site_settings->description = $data->selected_blog->site_description;
            $this->load->view('header',$data);
            $this->load->view('blog-detail');
            $this->load->view('footer');
        } else {
            redirect(site_url());
        }
    }


    public function about()
    {
        $data = new stdClass();
        $data->active = "about";
        $data->site_settings = new stdClass();
        $data->contact = $this->model->get_contact();
        $data->references = $this->model->get_references();
        $data->about = $this->model->get_about();
        $data->site_settings->title = $data->about->site_title;
        $data->site_settings->description = $data->about->site_description;

        $this->load->view('header',$data);
        $this->load->view('about');
        $this->load->view('footer');
    }

    public function services()
    {
        $data = new stdClass();
        $data->active = "services";
        $data->site_settings = new stdClass();
        $data->contact = $this->model->get_contact();
        $data->services = $this->model->get_services();
        $data->site_settings->title = $data->services->site_title;
        $data->site_settings->description = $data->services->site_description;

        $this->load->view('header',$data);
        $this->load->view('services');
        $this->load->view('footer');
    }

    public function kvkk()
    {
        $data = new stdClass();
        $data->active = "kvkk";
        $data->site_settings = new stdClass();
        $data->contact = $this->model->get_contact();
        $data->references = $this->model->get_references();
        $data->kvkk = $this->model->get_kvkk();
        $data->site_settings->title = $data->kvkk->site_title;
        $data->site_settings->description = $data->kvkk->site_description;

        $this->load->view('header',$data);
        $this->load->view('kvkk');
        $this->load->view('footer');
    }

    public function e_commerce()
    {
        $data = new stdClass();
        $data->active = "e_commerce";
        $data->site_settings = new stdClass();
        $data->contact = $this->model->get_contact();
        $data->references = $this->model->get_references();
        $data->e_commerce = $this->model->get_e_commerce();
        $data->site_settings->title = $data->e_commerce->site_title;
        $data->site_settings->description = $data->e_commerce->site_description;

        $this->load->view('header',$data);
        $this->load->view('ecommerce');
        $this->load->view('footer');
    }

    public function references()
    {
        $data = new stdClass();
        $data->active = "references";
        $data->site_settings = new stdClass();
        $data->contact = $this->model->get_contact();
        $data->references = $this->model->get_references();
        $data->site_settings = $this->model->get_site_settings(6);

        $this->load->view('header',$data);
        $this->load->view('references');
        $this->load->view('footer');
    }

    public function contact()
    {
        $data = new stdClass();
        $data->active = "contact";
        $data->site_settings = new stdClass();
        $data->contact = $this->model->get_contact();
        $data->site_settings = $this->model->get_site_settings(4);

        $this->load->view('header',$data);
        $this->load->view('contact');
        $this->load->view('footer');
    }

    public function request()
    {
        $data = new stdClass();
        $data->active = "request";
        $data->site_settings = new stdClass();
        $data->contact = $this->model->get_contact();
        $data->site_settings = $this->model->get_site_settings(5);

        $this->load->view('header',$data);
        $this->load->view('request');
        $this->load->view('footer');
    }

}