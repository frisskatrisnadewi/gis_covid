<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Kabupaten;
use App\Data;
use Illuminate\Support\Carbon;

class PetaController extends Controller
{
    //
    public function index(){
    	$tanggalSekarang = CARBON::now()->locale('id')->isoFormat('LL');
        $dateNow = Carbon::now()->format('Y-m-d');
    	$data = Data::select('id_data','nama_kab','meninggal','sembuh','positif','tanggal','rawat') -> join('tb_kabupaten','tb_data_covid.id_kab','=','tb_kabupaten.id_kab')->where('tanggal', $dateNow)->orderBy('positif','desc')->get();

		$meninggal = Data::select(DB::raw('COALESCE(SUM(meninggal),0) as meninggal'))->where('tanggal',$dateNow)->get();
        $positif = Data::select(DB::raw('COALESCE(SUM(positif),0) as positif'))->where('tanggal',$dateNow)->get();
        $rawat = Data::select(DB::raw('COALESCE(SUM(rawat),0) as rawat'))->where('tanggal',$dateNow)->get();
        $sembuh = Data::select(DB::raw('COALESCE(SUM(sembuh),0) as sembuh'))->where('tanggal',$dateNow)->get();   	
    	$kabupaten=Kabupaten::get();

    	return view('data.peta',compact("kabupaten","data","dateNow","meninggal","positif","rawat","sembuh","tanggalSekarang")); //asosiatif array melempar data ke view ['data_covid'=>$data_covid]);
    }


    public function search(Request $request){
        
        $tanggal = $request->tanggal;
        $tanggalSekarang = Carbon::parse($request->tanggal)->format('d F Y');
        $cekData = Data::select('tb_data_covid.id_data','nama_kab','meninggal','sembuh','positif','tanggal','rawat')
            ->rightjoin('tb_kabupaten','tb_data_covid.id_kab','=','tb_kabupaten.id_kab')
            ->where('tanggal',$request->tanggal)
            ->orderBy('tb_data_covid.id_kab','ASC')
            ->get();
        if (count($cekData) == 0) {
            $data = Kabupaten::select('nama_kab',DB::raw('IFNULL("0",0) as meninggal'), DB::raw('IFNULL("0",0) as positif'), DB::raw('IFNULL("0",0) as rawat'),DB::raw('IFNULL("0",0) as sembuh'))->get();
        }else{
            $data = $cekData;
        }

        $meninggal = Data::select(DB::raw('COALESCE(SUM(meninggal),0) as meninggal'))->where('tanggal',$request->tanggal)->get();
        $positif = Data::select(DB::raw('COALESCE(SUM(positif),0) as positif'))->where('tanggal',$request->tanggal)->get();
        $rawat = Data::select(DB::raw('COALESCE(SUM(rawat),0) as rawat'))->where('tanggal',$request->tanggal)->get();
        $sembuh = Data::select(DB::raw('COALESCE(SUM(sembuh),0) as sembuh'))->where('tanggal',$request->tanggal)->get();
        
        return view('data.peta',compact("data","meninggal","positif","rawat","sembuh","tanggalSekarang","tanggal"));
    }



    public function getData(Request $request){
        $dateNow = Carbon::now()->format('Y-m-d');
        if (is_null($request->date)) {
            $tanggal = $dateNow;
        }else{
            $tanggal = $request->date; 
        }

        $data = Data::select('tb_data_covid.id_data','tb_data_covid.id_kab','nama_kab','sembuh','rawat','positif','meninggal')
                ->rightjoin('tb_kabupaten','tb_data_covid.id_kab','=','tb_kabupaten.id_kab')
                ->where('tanggal',$tanggal)
                ->orderBy('id_kab','ASC')
                ->get();
        return $data;
    }


    public function getPositif(Request $request)
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        if (is_null($request->date)) {
            $tanggal = $dateNow;
        }else{
            $tanggal = $request->date;
        }

        $pos = Data::select('tb_data_covid.id_data','tb_data_covid.id_kab','nama_kab','sembuh','rawat','positif','meninggal')
                ->rightjoin('tb_kabupaten','tb_data_covid.id_kab','=','tb_kabupaten.id_kab')
                ->where('tanggal',$tanggal)
                ->orderBy('positif','DESC')
                ->get();
        return $pos;
    }


	public function createPallette(Request $request)
    {

        $HexFrom = ltrim($request->start, '#');
        $HexTo = ltrim($request->end, '#');

    
        $ColorSteps = 9;
        $FromRGB['r'] = hexdec(substr($HexFrom, 0, 2));
        $FromRGB['g'] = hexdec(substr($HexFrom, 2, 2));
        $FromRGB['b'] = hexdec(substr($HexFrom, 4, 2));
    
        $ToRGB['r'] = hexdec(substr($HexTo, 0, 2));
        $ToRGB['g'] = hexdec(substr($HexTo, 2, 2));
        $ToRGB['b'] = hexdec(substr($HexTo, 4, 2));
    
        $StepRGB['r'] = ($FromRGB['r'] - $ToRGB['r']) / ($ColorSteps - 1);
        $StepRGB['g'] = ($FromRGB['g'] - $ToRGB['g']) / ($ColorSteps - 1);
        $StepRGB['b'] = ($FromRGB['b'] - $ToRGB['b']) / ($ColorSteps - 1);
    
        $GradientColors = array();
    
        for($i = 0; $i <= $ColorSteps; $i++) {
        $RGB['r'] = floor($FromRGB['r'] - ($StepRGB['r'] * $i));
        $RGB['g'] = floor($FromRGB['g'] - ($StepRGB['g'] * $i));
        $RGB['b'] = floor($FromRGB['b'] - ($StepRGB['b'] * $i));
    
        $HexRGB['r'] = sprintf('%02x', ($RGB['r']));
        $HexRGB['g'] = sprintf('%02x', ($RGB['g']));
        $HexRGB['b'] = sprintf('%02x', ($RGB['b']));
    
        $GradientColors[] = implode(NULL, $HexRGB);
        }
        $collect = collect($GradientColors);
        $filtered = $collect->filter(function($value, $key){
            return strlen($value) == 6;
        });
        return $filtered;
    }

    function len($val){
        return (strlen($val) == 6 ? true : false );
    }



}
