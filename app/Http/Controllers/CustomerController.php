<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customerList = Customer::paginate();
        return response()->json($customerList, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|max:30',
            'mobile'      => 'required|max:12',
            'address'     => 'required',
            'email'       => 'required|max:100|email',
            'ktp'         => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'pic_name'    => 'required',
         ]);
         if ($validator->fails()) {
           return $request->validate($validator);
         }

        $check_mobile = Customer::where('mobile', $request->mobile)->first();
        if ($check_mobile){
            return "No HP Sudah Terdaftar";
        }else{
            $customer = new Customer;
            $customer->name     = $request->name;
            $customer->mobile   = $request->mobile;
            $customer->address  = $request->address;
            $customer->email    = $request->email;
            $customer->ktp      = $request->file('ktp')->store('customer');
            $customer->pic_name = json_encode($request->pic_name);
            $customer->save();
            return "Data Customer Tersimpan";
        }
    }

    public function update(Request $request, $id)
    {
        $name       = $request->name;
        $mobile     = $request->mobile;
        $address    = $request->address;
        $email      = $request->email;
        $ktp        = $request->ktp;
        $pic_name   = json_encode($request->pic_name);

        $customer = Customer::find($id);
        $customer->name     = $name;
        $customer->mobile   = $mobile;
        $customer->address  = $address;
        $customer->email    = $email;
        $customer->ktp      = $ktp;
        $customer->pic_name = $pic_name;
        $customer->save();

        return "Data Customer Berhasil diperbaharui";
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return "Data Customer Berhasil dihapus";
    }
}
