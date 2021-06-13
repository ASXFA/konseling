<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// FRONTEND
$route['^(rekomendasi|selengkapnyaObjek|beriUlasan|getUlasan)(/:any)?$'] = 'Frontend/$0';

// BACKEND
$route['^(myProfile|editProfil|editFoto)(:any)?$'] = "Dashboard/$0";
$route['^(auth|action_login|logout)(/:any)?$'] = 'Auth/$0';
$route['^(index)(/:any)?$'] = 'Dashboard/$0';
$route['^(listGuru|guruLists|guruByInduk|doGuru|deleteGuru)(/:any)?$'] = 'Guru/$0';
$route['^(listSiswa|siswaLists|siswaByInduk|doSiswa|deleteSiswa|detailSiswa|resetPoin|resetAllPoin)(/:any)?$'] = 'Siswa/$0';
$route['^(listAkun|akunLists|setStatus|resetPass|gantiPass)(/:any)?$'] = 'Akun/$0';
$route['^(listKelas|kelasLists|kelasById|doKelas|deleteKelas)(/:any)?$'] = 'Kelas/$0';
$route['^(listJenispelanggaran|jenispelanggaranLists|jenispelanggaranByKode|jenispelanggaranById|doJenispelanggaran|deleteJenispelanggaran|getKode)(/:any)?$'] = 'Jenis_pelanggaran/$0';
$route['^(listWalimurid|walimuridLists|walimuridById|doWalimurid|deleteWalimurid)(/:any)?$'] = 'Walimurid/$0';
$route['^(listSanksi|sanksiLists|sanksiById|doSanksi|deleteSanksi)(/:any)?$'] = 'Sanksi/$0';
$route['^(listPelanggaran|pelanggaranLists|pelanggaranById|doPelanggaran|detailPelanggaran|deletePelanggaran|getKodePelanggaran)(/:any)?$'] = 'Pelanggaran/$0';
$route['^(listCatatan|catatanLists|catatanById|doCatatan|deleteCatatan|detailCatatan|cetakByKasus|cetakByKategori)(/:any)?$'] = 'Catatan_kasus/$0';

