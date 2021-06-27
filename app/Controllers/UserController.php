<?php

namespace App\Controllers;

class UserController extends BaseController
{

    public function __construct(){
        require_once APPPATH.'ThirdParty/ssp.php';
        $this->db = db_connect();
    }

    public function index()
    {
        $data['pageTitle'] = 'Home';
        return view('dashboard/home', $data);
    }

    public function profile(){
        $data['pageTitle'] = 'Profile';
        return view('dashboard/profile',$data);
    }

    public function countries(){
        $data['pageTitle'] = 'Countries List';
        return view('dashboard/countries', $data);
    }

    public function addCountry(){
        $countryModel = new \App\Models\Country();
        $validation = \Config\Services::validation();
        $this->validate([
             'country_name'=>[
                 'rules'=>'required|is_unique[countries.country_name]',
                 'errors'=>[
                     'required'=>'Country name is required',
                     'is_unique'=>'This country is already exists',
                 ]
             ],
             'capital_city'=>[
                  'rules'=>'required',
                  'errors'=>[
                      'required'=>'Capital city is required'
                  ]
             ]
        ]);

        if($validation->run() == FALSE){
            $errors = $validation->getErrors();
            echo json_encode(['code'=>0, 'error'=>$errors]);
        }else{
             //Insert data into db
             $data = [
                 'country_name'=>$this->request->getPost('country_name'),
                 'capital_city'=>$this->request->getPost('capital_city'),
             ];
             $query = $countryModel->insert($data);
             if($query){
                 echo json_encode(['code'=>1,'msg'=>'New country has been saved to database']);
             }else{
                 echo json_encode(['code'=>0,'msg'=>'Something went wrong']);
             }
        }
    }

    public function getAllCountries(){
        //DB Details
        $dbDetails = array(
            "host"=>$this->db->hostname,
            "user"=>$this->db->username,
            "pass"=>$this->db->password,
            "db"=>$this->db->database,
        );

        $table = "countries";
        $primaryKey = "id";

        $columns = array(
            array(
                "db"=>"id",
                "dt"=>0,
            ),
            array(
                "db"=>"country_name",
                "dt"=>1,
            ),
            array(
                "db"=>"capital_city",
                "dt"=>2,
            ),
            array(
                "db"=>"id",
                "dt"=>3,
                "formatter"=>function($d, $row){
                    return "<div class='btn-group'>
                                  <button class='btn btn-sm btn-primary' data-id='".$row['id']."' id='updateCountryBtn'>Update</button>
                                  <button class='btn btn-sm btn-danger' data-id='".$row['id']."' id='deleteCountryBtn'>Delete</button>
                             </div>";
                }
            ),
        );

        echo json_encode(
            \SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }


    public function getCountryInfo(){
        $countryModel = new \App\Models\Country();
        $country_id = $this->request->getPost('country_id');
        $info = $countryModel->find($country_id);
        if($info){
            echo json_encode(['code'=>1, 'msg'=>'', 'results'=>$info]);
        }else{
            echo json_encode(['code'=>0, 'msg'=>'No results found', 'results'=>null]);
        }
    }

    public function updateCountry(){
        $countryModel = new \App\Models\Country();
        $validation = \Config\Services::validation();
        $cid = $this->request->getPost('cid');

        $this->validate([
            'country_name'=>[
                 'rules'=>'required|is_unique[countries.country_name,id,'.$cid.']',
                 'errors'=>[
                      'required'=>'Country name is required',
                      'is_unique'=>'This country is already exists'
                 ]
            ],
            'capital_city'=>[
                  'rules'=>'required',
                  'errors'=>[
                      'required'=>'Capital city is required'
                  ]
            ]
        ]);

        if($validation->run() == FALSE){
            $errors = $validation->getErrors();
            echo json_encode(['code'=>0,'error'=>$errors]);
        }else{
            //Update country
            $data = [
               'country_name'=>$this->request->getPost('country_name'),
               'capital_city'=>$this->request->getPost('capital_city'),
            ];
            $query = $countryModel->update($cid,$data);

            if($query){
                echo json_encode(['code'=>1, 'msg'=>'Country info have been updated successfully']);
            }else{
                echo json_encode(['code'=>0, 'msg'=>'Something went wrong']);
            }
        }
    }


    public function deleteCountry(){
        $countryModel = new \App\Models\Country();
        $country_id = $this->request->getPost('country_id');
        $query = $countryModel->delete($country_id);

        if($query){
            echo json_encode(['code'=>1,'msg'=>'Country deleted Successfully']);
        }else{
            echo json_encode(['code'=>0,'msg'=>'Something went wrong']);
        }
    }

  

}