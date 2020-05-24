<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Kabupaten;
use App\Data;
use Carbon\Carbon as Carbon;

class DataController extends Controller
{
   
	private $dateTimeNow;
    private $dateNow;
    private $dateFormatName;
    private $dateFormatName1;

    public function __construct()
    {
    	//inisialisasi
        $this->dateTimeNow = Carbon::now()->addHours(8);
        $this->dateNow = Carbon::now()->format('Y-m-d');
        $this->dateFormatName = Carbon::now()->locale('id')->isoFormat('LL');
    }

    //fungsi crud
    public function index(){
        $tanggalSekarang = CARBON::now()->locale('id')->isoFormat('LL');
        $dateNow = Carbon::now()->format('Y-m-d');
        $data_covid = Data::select('id_data','nama_kab','meninggal','sembuh','positif','tanggal','rawat') -> join('tb_kabupaten','tb_data_covid.id_kab','=','tb_kabupaten.id_kab')->where('tanggal', $dateNow)->orderBy('positif','desc')->get();

        $meninggal = Data::select(DB::raw('COALESCE(SUM(meninggal),0) as meninggal'))->get();
        $positif = Data::select(DB::raw('COALESCE(SUM(positif),0) as positif'))->get();
        $rawat = Data::select(DB::raw('COALESCE(SUM(rawat),0) as rawat'))->get();
        $sembuh = Data::select(DB::raw('COALESCE(SUM(sembuh),0) as sembuh'))->get();    
        $kabupaten=Kabupaten::get();

        return view('data.index',compact("kabupaten","data_covid","dateNow","meninggal","positif","rawat","sembuh","tanggalSekarang"));
    }

    public function create(Request $request){

        $kabupaten = $request->kabupaten;
        $tanggal = $request->tanggal;
        $rawat= $request->rawat;
        $sembuh= $request->sembuh;
        $meninggal= $request->meninggal;
        $positif= $request->positif;

        $data = array(
        'Kabupaten' => $kabupaten,
        'Tanggal' => $tanggal
        );

        $count = DB::table('tb_data_covid')->where('id_kab', $kabupaten)->where('tanggal',  $tanggal)->count();

        if($count > 0){
            return redirect()->back();
        }else{
            $data_covid = new Data;
            $data_covid->tanggal= $request->tanggal;
            $data_covid->id_kab= $request->kabupaten;
            $data_covid->sembuh = $request->sembuh;
            $data_covid->rawat= $request->rawat;
            $data_covid->positif= $positif;
            $data_covid->meninggal= $request->meninggal;
            $data_covid->save();
        }


    	return redirect ('/data')->with('sukses', 'Data Berhasil Disimpan');


    }

    public function edit($id){
    	$data_covid= Data::select('id_data','nama_kab','meninggal','sembuh','positif','tanggal','rawat') -> join('tb_kabupaten','tb_data_covid.id_kab','=','tb_kabupaten.id_kab')->where('id_data',$id)->get();
    	return view('data/edit',['data_covid'=>$data_covid]);
    }

    public function update(Request $request, $id){
    	$data_covid = Data::where('id_data',$id);
    	$data_covid->where('id_data',$request->id)->update([
        'rawat' => $request->rawat,
        'sembuh' => $request->sembuh,
        'meninggal' => $request->meninggal,
        'positif' => $request->positif,
        'tanggal' => $request->tanggal
    ]);
        

	/*	    $data = array(
        'Kabupaten' => $kabupaten,
        'Tanggal' => $tgl_data
    );

    $count = DB::table('tb_data')->where('id_kabupaten', $kabupaten)
                                ->where('tgl_data',  $tgl_data)
                                ->count();
    if($count > 0){
        return redirect()->back();
    }else{
        // DB::table('teammembersall')->insert($data);
        $data = new Data;
        $data->tgl_data= $request->tgl_data;
        $data->id_kabupaten= $request->kabupaten;
        $data->sembuh = $request->sembuh;
        $data->rawat= $request->rawat;
        $data->positif= $positif;
        $data->meninggal= $request->meninggal;
        $data->save();
    }
    ]);*/


    	return redirect('/data')->with('sukses', 'Data Berhasil Diupdate');
    }

    public function delete($id){
    	$data_covid = Data::where('id_data',$id);
    	$data_covid->delete($data_covid);
    	return redirect('/data')->with('sukses', 'Data Berhasil Dihapus');
    }

    public function search(Request $request){
        $tanggal = $request->tanggal;
        $kabupaten=Kabupaten::get();
        $tanggalSekarang = Carbon::parse($request->tanggal)->format('d F Y');
        $cekData = Data::select('tb_data_covid.id_data','nama_kab','meninggal','sembuh','positif','tanggal','rawat')
            ->rightjoin('tb_kabupaten','tb_data_covid.id_kab','=','tb_kabupaten.id_kab')
            ->where('tanggal',$request->tanggal)
            ->orderBy('tb_data_covid.id_kab','ASC')
            ->get();
        if (count($cekData) == 0) {
            $data_covid = Kabupaten::select('nama_kab',DB::raw('IFNULL("0",0) as meninggal'), DB::raw('IFNULL("0",0) as positif'), DB::raw('IFNULL("0",0) as rawat'),DB::raw('IFNULL("0",0) as sembuh'))->get();
        }else{
            $data_covid = $cekData;
        }

        $meninggal = Data::select(DB::raw('COALESCE(SUM(meninggal),0) as meninggal'))->where('tanggal',$request->tanggal)->get();
        $positif = Data::select(DB::raw('COALESCE(SUM(positif),0) as positif'))->where('tanggal',$request->tanggal)->get();
        $rawat = Data::select(DB::raw('COALESCE(SUM(rawat),0) as rawat'))->where('tanggal',$request->tanggal)->get();
        $sembuh = Data::select(DB::raw('COALESCE(SUM(sembuh),0) as sembuh'))->where('tanggal',$request->tanggal)->get();
        
        return view('data.index',compact("data_covid","meninggal","positif","rawat","sembuh","tanggalSekarang","tanggal","kabupaten"));


    }
    
}
