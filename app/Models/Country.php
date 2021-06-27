<?php
namespace App\Models;

use CodeIgniter\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'id';
    protected $allowedFields = ['country_name','capital_city'];
}