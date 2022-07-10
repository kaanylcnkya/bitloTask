<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyPackage;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class CompanyController extends Controller
{
    public function register(Request $request){
        
        $fields = $request->validate([
            'site_url' => 'required|string',
            'name' => 'required|string',
            'lastname' => 'required|string',
            'company_name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $company = Company::create([
            'site_url' => $fields['site_url'],
            'name' => $fields['name'],
            'lastname' => $fields['lastname'],
            'company_name' => $fields['company_name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        
        $token = $company->createToken('loginToken')->plainTextToken;

        $response = [
            'company_id' => $company->id,
            'token' => $token
        ];

        return response($response,201);
    }

    public function company_package(Request $request){
        
        $package = Package::where('id', $request->package_id)->first();

        if($package->id == 1) {
            $start_date = Carbon::now()->format('d-m-Y H:i:s');
            $end_date = Carbon::now()->addMonth()->format('d-m-Y H:i:s');;    
        }
        else{
            $start_date = Carbon::now()->format('d-m-Y H:i:s');
            $end_date = Carbon::now()->addMonth(12)->format('d-m-Y H:i:s');;
        }
    
        $company = CompanyPackage::create([
            'company_id' => $request->company_id,
            'package_id' => $request->package_id,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
        $response = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'package' => $package->name
        ];

        return response($response,201);
    }

    public function check_company_package(Request $request, $id){
        
       $companyPackage = CompanyPackage::where('id', $id)->first();

       $company = Company::where('id', $companyPackage->company_id)->first()->company_name;
       $package = Package::where('id', $companyPackage->package_id)->first()->name;

        $response = [
            'company_name' => $company,
            'package' => $package,
            'start_date' => $companyPackage->start_date,
            'end_date' => $companyPackage->end_date
        ];

        return response($response,201);
    }

    public function login(Request $request){

        $user = Company::where('email', $request->email)->first();

        if( !$user || !Hash::check($request->password, $user->password)){
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user->createToken('loginToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function getUser(){
        return User::all();
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

            return [
                'message' => 'Logged Out'
            ];
    }
}
