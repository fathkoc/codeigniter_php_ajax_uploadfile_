<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
class Ajax extends Veripay_Controller
{

    public function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json');
        $this->result = new StdClass();
        $this->result->status = false;
        $this->load->model($this->router->fetch_class() . '_model', 'model');
    }

    public function response()
    {
        echo json_encode($this->result);
        exit();
    }

    protected function post(string $key, bool $clear_html = true, bool $xss = true, int $flag = ENT_COMPAT): string
    {
        $value = trim($this->input->post($key, $xss));
        if ($clear_html) {
            return htmlspecialchars(strip_tags($value), $flag);
        }
        return $value;
    }


    public function add_message()
    {

        if (!empty($this->input->post('confirm', true))) {

            $post_data = new StdClass();

            $this->form_validation->set_rules('name', 'Ad', 'required|min_length[1]|max_length[250]|trim|xss_clean');
            $this->form_validation->set_rules('surname', 'Soyad', 'required|min_length[1]|max_length[250]|trim|xss_clean');
            $this->form_validation->set_rules('phone', 'Telefon Numaranız', 'required|min_length[17]|max_length[17]|trim|xss_clean', [
                'min_length' => "Lütfen Telefon Numaranızı Kontrol Edip  Doğru Girdiğinizden Emin Olunuz",
                'max_length' => "Lütfen Telefon Numaranızı Kontrol Edip  Doğru Girdiğinizden Emin Olunuz",
            ]);
            $this->form_validation->set_rules('e_mail', 'E-Posta', 'required|min_length[1]|valid_email|max_length[250]|trim|xss_clean');
            $this->form_validation->set_rules('message', 'Mesaj', 'required|min_length[1]|trim|xss_clean');

            if ($this->form_validation->run() != false) {
                $post_data->name_surname = $this->input->post('name', true).' '.$this->input->post('surname', true);
                $post_data->phone = $this->input->post('phone', true);
                $post_data->e_mail = $this->input->post('e_mail', true);
                $post_data->message = $this->input->post('message', true);
                try {
                    $mail = new PHPMailer();
                    $mail->IsSMTP();  // telling the class to use SMTP
                    $mail->SMTPDebug = 0;
                    $mail->Mailer = "smtp";
                    $mail->Host = "mail.guvendata.com.tr";
                    $mail->Port = 587;
                    $mail->SMTPAuth = true; // turn on SMTP authentication
                    $mail->Username = "noreply@guvendata.com.tr"; // SMTP username
                    $mail->Password = '#0mOlq71'; // SMTP password
                    $mail->Priority = 1;
                    $mail->AddAddress("info@guvendata.com.tr", "guvendata.com.tr");
                    $mail->SetFrom('noreply@guvendata.com.tr', 'guvendata.com.tr');
                    $mail->AddReplyTo($post_data->e_mail);
                    $mail->CharSet = 'UTF-8';
                    $mail->IsHTML(true);
                    $mail->Subject = 'guvendata.com.tr addressinden bir kullanıcı sizinle iletişime geçmek istiyor';
                    $mail->Body = '
                                    <p>Ad Soyad : ' . $post_data->name_surname . '</p>
                                    </br></br>
                                    <p>e_mail : ' . $post_data->e_mail . '</p>
                                    </br></br>
                                    <p>Telefon :' . $post_data->phone . '</p>
                                    </br></br>
                                    <p>Mesaj : ' . $post_data->message . '</p>
                                    </br></br>
                                    ';
                    if (!$mail->Send()) {
                        $this->result->error = "Mesajınız İletilemedi Lütfen Tekrar Deneyin";
                        $this->response();
                    } else {
                        $this->result->status = true;
                    }
                } catch (Exception $e) {
                    $this->result->error = "Mesajınızı Alamadık Lütfen Tekrar Deneyin {$mail->ErrorInfo}";
                    $this->response();
                }
                if ($this->model->add_message($post_data)) {
                    $this->result->success = "Mesajınızı Aldık Size En Kısa Sürede Dönüş Yapacağız";
                    $this->result->status = true;
                } else {
                    $this->result->error = "İşlem Başarısız Lütfen Tekrar Deneyin";
                    $this->response();
                }
            } else {
                if (form_error('name')) {
                    $this->result->error = form_error('name');
                } else if (form_error('surname')) {
                    $this->result->error = form_error('surname');
                } else if (form_error('phone')) {
                    $this->result->error = form_error('phone');
                } else if (form_error('e_mail')) {
                    $this->result->error = form_error('e_mail');
                } else if (form_error('message')) {
                    $this->result->error = form_error('message');
                }
                $this->response();
            }
        } else {
            $this->result->error = "Lütfen Aydınlatma Metnini Okudukdan Sonra Onaylayın.";
            $this->response();
        }
        $this->response();
    }


    public function add_request()
    {

        if (!empty($this->input->post('confirm', true))) {

            $post_data = new StdClass();

            $this->form_validation->set_rules('name', 'Ad', 'required|min_length[1]|max_length[250]|trim|xss_clean');
            $this->form_validation->set_rules('surname', 'Soyad', 'required|min_length[1]|max_length[250]|trim|xss_clean');
            $this->form_validation->set_rules('phone', 'Telefon Numaranız', 'required|min_length[17]|max_length[17]|trim|xss_clean', [
                'min_length' => "Lütfen Telefon Numaranızı Kontrol Edip  Doğru Girdiğinizden Emin Olunuz",
                'max_length' => "Lütfen Telefon Numaranızı Kontrol Edip  Doğru Girdiğinizden Emin Olunuz",
            ]);
            $this->form_validation->set_rules('e_mail', 'E-Posta', 'required|min_length[1]|valid_email|max_length[250]|trim|xss_clean');

            if ($this->form_validation->run() != false) {
                $post_data->name_surname = $this->input->post('name', true).' '.$this->input->post('surname', true);
                $post_data->phone = $this->input->post('phone', true);
                $post_data->e_mail = $this->input->post('e_mail', true);
                try {
                    $mail = new PHPMailer();
                    $mail->IsSMTP();  // telling the class to use SMTP
                    $mail->SMTPDebug = 0;
                    $mail->Mailer = "smtp";
                    $mail->Host = "mail.guvendata.com.tr";
                    $mail->Port = 587;
                    $mail->SMTPAuth = true; // turn on SMTP authentication
                    $mail->Username = "noreply@guvendata.com.tr"; // SMTP username
                    $mail->Password = '#0mOlq71'; // SMTP password
                    $mail->Priority = 1;
                    $mail->AddAddress("info@guvendata.com.tr", "guvendata.com.tr");
                    $mail->SetFrom('noreply@guvendata.com.tr', 'guvendata.com.tr');
                    $mail->AddReplyTo($post_data->e_mail);
                    $mail->CharSet = 'UTF-8';
                    $mail->IsHTML(true);
                    $mail->Subject = 'guvendata.com.tr addressinden bir kullanıcı Detaylı Bilgi Talep Ediyor';
                    $mail->Body = '
                                    <p>Ad Soyad : ' . $post_data->name_surname . '</p>
                                    </br></br>
                                    <p>e_mail : ' . $post_data->e_mail . '</p>
                                    </br></br>
                                    <p>Telefon :' . $post_data->phone . '</p>
                                    </br></br>
                                    <p>Mesaj : Detaylı Bİlgi Talebi</p>
                                    </br></br>
                                    ';
                    if (!$mail->Send()) {
                        $this->result->error = "Talebiniz İletilemedi Lütfen Tekrar Deneyin";
                        $this->response();
                    } else {
                        $this->result->status = true;
                    }
                } catch (Exception $e) {
                    $this->result->error = "Talebinizi Alamadık Lütfen Tekrar Deneyin {$mail->ErrorInfo}";
                    $this->response();
                }
                if ($this->model->add_request($post_data)) {
                    $this->result->success = "Talebinizi Aldık Size En Kısa Sürede Dönüş Yapacağız";
                    $this->result->status = true;
                } else {
                    $this->result->error = "İşlem Başarısız Lütfen Tekrar Deneyin";
                    $this->response();
                }
            } else {
                if (form_error('name')) {
                    $this->result->error = form_error('name');
                } else if (form_error('surname')) {
                    $this->result->error = form_error('surname');
                }  else if (form_error('phone')) {
                    $this->result->error = form_error('phone');
                } else if (form_error('e_mail')) {
                    $this->result->error = form_error('e_mail');
                }
                $this->response();
            }
        } else {
            $this->result->error = "Lütfen Aydınlatma Metnini Okudukdan Sonra Onaylayın.";
            $this->response();
        }
        $this->response();
    }


    public function get_contract()
    {
        if($contract = $this->model->get_contract()) {
            $this->result->contract = $contract;
            $this->result->status = true;
        } else {
            $this->result->error = "Aydınlatma Metni Yüklenemedi Lütfen Tekrar Deneyin";
        }
        $this->response();
    }
}
