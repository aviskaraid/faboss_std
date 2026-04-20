<?php 
// function is_logged_in()
// {
//     $ci = get_instance();
//     if(!$ci->session->userdata('email')){
//         redirect('auth');
//     }   
// }



function is_logged_in()
{
    $ci = get_instance(); 
    if (!$ci->session->userdata('email')) {
        redirect('auth');
    } else {
        $role_id = $ci->session->userdata('id_role');
        $url_submenu = $ci->uri->segment(1).'/'.$ci->uri->segment(2);

        $queryMenu = $ci->db->get_where('user_sub_menu', ['url' => $url_submenu])->row_array();
        $cekAccess = $ci->db->get_where('user_access_menu', ['role_id' => $role_id, 'sub_menu_id' => $queryMenu['id']])->row_array();

        if (!isset($cekAccess)) {
            redirect('auth/blocked');
        }

    }
}


function check_access($role_id, $sub_menu_id)
{
    $ci = get_instance();

    $ci->db->where('role_id', $role_id);
    $ci->db->where('sub_menu_id', $sub_menu_id);
    $result = $ci->db->get('user_access_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function is_admin()
{
    $ci = get_instance();
    $role = $ci->session->userdata('id_role');

    $status = true;

    if ($role != '1') {
        $status = false;
    }

    return $status;
}

function is_user()
{
    $ci = get_instance();
    $role = $ci->session->userdata('id_role');

    $status = true;

    if ($role != '2') {
        $status = false;
    }

    return $status;
}

function is_keuangan()
{
    $ci = get_instance();
    $role = $ci->session->userdata('id_role');

    $status = true;

    if ($role != '3') {
        $status = false;
    }

    return $status;
}

function  getBulan($bln){
    switch  ($bln){
        case  1:
        return  "Januari";
        break;
        case  2:
        return  "Februari";
        break;
        case  3:
        return  "Maret";
        break;
        case  4:
        return  "April";
        break;
        case  5:
        return  "Mei";
        break;
        case  6:
        return  "Juni";
        break;
        case  7:
        return  "Juli";
        break;
        case  8:
        return  "Agustus";
        break;
        case  9:
        return  "September";
        break;
        case  10:
        return  "Oktober";
        break;
        case  11:
        return  "November";
        break;
        case  12:
        return  "Desember";
        break;
    }
}

function tgl_indo($tanggal) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    ];

    $exp = explode('-', $tanggal);
    return $exp[2] . ' ' . $bulan[(int)$exp[1]] . ' ' . $exp[0];
}


    
function getAllBulan()
{
    $bulan = array(
        array('id_bln' => 1, 'nm_bln' => 'Januari'),
        array('id_bln' => 2, 'nm_bln' => 'Februari'),
        array('id_bln' => 3, 'nm_bln' => 'Maret'),
        array('id_bln' => 4, 'nm_bln' => 'April'),
        array('id_bln' => 5, 'nm_bln' => 'Mei'),
        array('id_bln' => 6, 'nm_bln' => 'Juni'),
        array('id_bln' => 7, 'nm_bln' => 'Juli'),
        array('id_bln' => 8, 'nm_bln' => 'Agustus'),
        array('id_bln' => 9, 'nm_bln' => 'September'),
        array('id_bln' => 10, 'nm_bln' => 'Oktober'),
        array('id_bln' => 11, 'nm_bln' => 'November'),
        array('id_bln' => 12, 'nm_bln' => 'Desember'),
    );
    
    return $bulan;
}


function convertDateToDbdate($inputDate)
{
    if (!$inputDate) {
        return null;
    }

    $inputDate = trim($inputDate);

    $dateObj = DateTime::createFromFormat('d-m-Y', $inputDate);

    if ($dateObj === false) {
        return null;
    }

    return $dateObj->format('Y-m-d');
}


function convertDbdateToDate($mysqlDate)
{
    if (empty($mysqlDate)) {
        return null;
    }

    // Handle DATE or DATETIME
    $format = (strlen($mysqlDate) > 10) ? 'Y-m-d H:i:s' : 'Y-m-d';

    $dateObj = DateTime::createFromFormat($format, $mysqlDate);
    $errors  = DateTime::getLastErrors();

    if ($dateObj === false ||
        $errors['warning_count'] > 0 ||
        $errors['error_count'] > 0) {
        return null;
    }

    return $dateObj->format('d-m-Y');
    
}

function bulan_indo($bulan)
{
    $nama_bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    return $nama_bulan[(int)$bulan] ?? '';
}
