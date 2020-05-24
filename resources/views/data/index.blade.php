@extends('layouts.master')
	@if(session('sukses'))
		<div class="alert alert-success" role="alert">
		 {{'sukses'}}
			 <button type="button" class="close" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		</div>
		@endif
@section('search')
<div class="row mt-4">
	<div class="col-sm-8">
		<div >
          	<div class="card-body">
           		<div class="col">
              		<blockquote class="blockquote text-center">
                		<h3><strong>PENYEBARAN DATA COVID-19 PROVINSI BALI</strong></h3>
                		<footer >
                  			<div class=" row justify-content-center">
                   				<img src="kalender.png" class="rounded float-left" style="width:10%">
                  
                  			</div>
                  			<h5 class="justify-content-center">{{$tanggalSekarang}}</h5> 
                        </footer>
              		</blockquote>                
				</div>
			</div>
		</div>		
	</div>
    <div class="col-sm-4 " style="margin-bottom: 15px">
        <div class="col">
          <div>
            <strong>Cari Data Berdasarkan Tanggal</strong>
          </div>
          <hr>
          	<div class="row">
				<form action="/data/search" method="post" class="form-inline my-2 my-lg-0">
				@csrf
				    <input class="form-control mr-md-2 " type="date"  @if(isset($tanggal)) value="{{$tanggal}}" @endif name="tanggal" id="tanggalSearch"placeholder="Masukan tanggal" aria-label="Search" required>
				    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
				</form>
			</div>
		 </div>            
    </div>
</div>

	
@endsection

@section('content')
		<div>
			<div class="card-header ">
				<div class="row ">
					<div class="col-6">
						<h5>Tabel Sebaran Data {{$tanggalSekarang}}</h5>
					</div>
					<div class="col-6">
						<button type="button" class="btn btn-primary btn-md float-right" data-toggle="modal" data-target="#exampleModal">
						  Tambah Data
						</button>
					</div>
				</div>
			</div>
			<div class="card-body">
				
				<table class="table table-hover table-dark table-responsive-md shadow-sm rounded" id="tables" >		
					<tr class="bg-info text-light" >
						<th>No</th>
						<th>Record Date</th>
						<th>Kabupaten</th>
						<th>Rawat</th>
						<th>Sembuh</th>
						<th>Meninggal</th>
						<th>Positif</th>
						<th >Action</th>
					</tr>
					
					<?php 
						if(count($data_covid)>0){
							$s	=	'';
							foreach($data_covid as $val){
								$s++;
					?>
					<tr>
						<td ><?php echo $s ?? '';?></td>
						<td>{{$val->tanggal}}</td>
						<td>{{$val->nama_kab}}</td>
						<td>{{$val->rawat}}</td>
						<td>{{$val->sembuh}}</td>
						<td>{{$val->meninggal}}</td>
						<td>{{$val->positif}}</td>
					
						<td >
							<a href="/data/{{$val->id_data}}/edit" class="btn btn-warning btn-sm">Edit</a> | 
							<a href="/data/{{$val->id_data}}/delete "  class="btn btn-danger btn-sm" onClick="return confirm('Apakah yakin menghapus data?');"> Delete</a>
						</td>
					</tr>
					<?php 
							}
						}else{
					?>
						<tr><td colspan="6" align="center">No Record(s) Found!</td></tr>
					<?php } ?>					
				
				</table>
		</div>




<!-- Modal -->
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">

						<h6 class="card-title"> Tanda <span class="text-danger">*</span> wajib diisi!</h6>

				        <form action="/data/create" method="POST">
				        	{{ csrf_field() }}
						 	<div class="form-group">
								<label>Kabupaten <span class="text-danger">*</span></label>

								<select id="kabupaten" name="kabupaten" class="form-control" required>
									 <option value="" selected>Pilih</option>
									 @foreach($kabupaten as $item)
									 <option value="{{$item->id_kab}}" >{{$item->nama_kab}}</option>

									 @endforeach
									</select>


							<div class="form-group">
								<label>Tanggal <span class="text-danger">*</span></label>
								<input type="date" name="tanggal" id="tanggal" min="2020-04-01" class="form-control" placeholder="masukkan angka" required>
							</div>

							<div class="form-group">
								<label>Total Rawat <span class="text-danger">*</span></label>
								<input type="number" name="rawat" id="rawat" min="0" onKeyup="hitung();" class="form-control" placeholder="masukkan angka" required>
							</div>

							<div class="form-group">
								<label>Total Sembuh <span class="text-danger">*</span></label>
								<input type="number" name="sembuh" id="sembuh" min="0" onKeyup="hitung();" class="form-control" placeholder="masukkan angka" required>
							</div>

							<div class="form-group">
								<label>Total Meninggal <span class="text-danger">*</span></label>
								<input type="number" name="meninggal" id="meninggal" min="0" onKeyup="hitung();" class="form-control" placeholder="masukkan angka" required>
							</div>

							<div class="form-group">
								<label>Total Positif </label>
								<input type="number" name="positif" id="positif" onKeyup="hitung();" class="form-control" readonly="">
							</div>
				      		</div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				        <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Add Data</button>
				        </form>
				      </div>
				    </div>
				  </div>
				</div>
			


@endsection

