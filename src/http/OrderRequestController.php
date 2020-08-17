<?php

namespace Increment\Marketplace\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Increment\Marketplace\Models\OrderRequest;
use Carbon\Carbon;
class OrderRequestController extends APIController
{

  public $merchantClass = 'Increment\Marketplace\Http\MerchantController';
  function __construct(){
    $this->model = new OrderRequest();
    $this->notRequired = array('date_delivered', 'delivered_by');
  }

  public function create(Request $request){
    $data = $request->all();
    $data['code'] = $this->generateCode();
    $merchant = app($this->merchantClass)->getColumnByParams('id', $data['merchant_id'], 'prefix');
    $counter = OrderRequest::where('merchant_id', '=', $data['merchant_id'])->count();
    $data['status'] = 'pending';
    $data['order_number'] = $merhant ? $merchant['prefix'].$counter: $counter;
    $this->model = new OrderRequest();
    $this->insertDB($data);
    return $this->response();
  }


  public function generateCode(){
    $code = 'OR_'.substr(str_shuffle($this->codeSource), 0, 61);
    $codeExist = OrderRequest::where('code', '=', $code)->get();
    if(sizeof($codeExist) > 0){
      $this->generateCode();
    }else{
      return $code;
    }
  }

  public function retrieve(Request $request){
    $data = $request->all();
    $this->retrieveDB($data);
    $result = $this->response['data'];
    if(sizeof($result) > 0){
      $array = array();
      foreach ($result as $key) {
        $item = array(
          'merchant_to' => app($this->merchantClass)->getColumnByParams('id', $key['merchant_to'], 'name'),
          'date_of_delivery'  => Carbon::createFromFormat('Y-m-d H:i:s', $key['date_of_delivery'])->copy()->tz($this->response['timezone'])->format('F j, Y'),
          'status'        => $key['status'],
          'delivered_by'  => null,
          'delivered_date'=> null,
          'code'          => $key['code'],
          'added_by'      => $key['code'],
          'id'      => $key['id'],
          'order_number'      => $key['order_number']
        );
        $array[] = $item;
      }
      $this->response['data'] = $array;
    }
    $this->response['size'] = OrderRequest::where($data['condition'][0]['column'], '=', $data['condition'][0]['value'])->count();
    return $this->response();
  }
}
