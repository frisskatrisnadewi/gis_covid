@extends('layouts.masteredit')

@section('contentedit')
	   	@if(session('sukses'))
			<div class="alert alert-success" role="alert">
		 		{{'sukses'}}
			</div>
		@endif
		<h1><a></a></h1>
		@foreach($data_covid as $val)
			
		<div class="card shadow-sm">
			<div class="card-header"><i class="fa fa-fw fa-plus-circle"></i> <strong>Edit User</strong> <a href="index.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Home</a></div>
			<div class="card-body">
				<div class="row">
				<div class="col-sm-6">
					<h5 class="card-title">Tanda <span class="text-danger">*</span> harus diisi!</h5>

					<form action="/data/{{$val->id_data}}/update" method="post">
						{{ csrf_field() }}
						<div class="form-group">

							<label>Kabupaten <span class="text-danger">*</span></label>

	<!--						<select id="kabupaten" name="kabupaten" class="form-control" required>
 							  <option value="fiat" selected>Jembrana</option>
							  <option value="volvo">Tabanan</option>
							  <option value="saab">Badung</option>
							  <option value="fiat">Denpasar</option>
							  <option value="audi">Gianyar</option>
							  <option value="volvo">Klungkung</option>
							  <option value="saab">Karangasem</option>
							  <option value="fiat">Buleleng</option>
							  <option value="audi">Bangli</option>
							</select> -->

							<input type="text" name="kabupaten" id="kabupaten" class="form-control" value="{{ $val->nama_kab }}" readonly>

						</div>

						<div class="form-group">

							<label>Tanggal <span class="text-danger">*</span></label>

							<input type="date" name="tanggal" id="tanggal" min="2020-04-01" class="form-control" value="{{ $val->tanggal }}" readonly>

						</div>

						<div class="form-group">

							<label>Rawat <span class="text-danger">*</span></label>

							<input type="number" name="rawat" id="rawat" min="0" onKeyup="hitung();" class="form-control" value="{{ $val->rawat }}" required>

						</div>

						<div class="form-group">

							<label>Sembuh <span class="text-danger">*</span></label>

							<input type="number" name="sembuh" id="sembuh" min="0" onKeyup="hitung();" class="form-control" value="{{ $val->sembuh }}" required>

						</div>

						<div class="form-group">

							<label>Meninggal <span class="text-danger">*</span></label>

							<input type="number" name="meninggal" id="meninggal" min="0" onKeyup="hitung();" class="form-control" value="{{ $val->meninggal }}" required>

						</div>

						<div class="form-group">

							<label>Positif <span class="text-danger">*</span></label>

							<input type="number" name="positif" id="positif" onKeyup="hitung();" class="form-control" readonly="">

						</div>
						<div class="form-group">
							<button type="submit" name="submit" value="submit" id="submit" class="btn btn-warning"><i class="fa fa-fw fa-edit"></i> Update Data</button>
						</div>
					</form>
					
				</div>

				</div>
			</div>
			</div>
		</div>
		@endforeach
@endsection	


