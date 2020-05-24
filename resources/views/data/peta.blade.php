@extends('layouts.masterpeta')
	
@section('search')
		
		<div class="row mt-4">
			<div class="col-sm-6">
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
					
                
                
                 <div class="row">
                  
                </div>
                
					   	</div>

					</div>
				</div>
			
		</div>
    <div class="col-sm-6 " style="margin-bottom: 15px">
        <div class="card shadow-sm">
          <div class="card-title d-flex justify-content-center ">
            <strong>Generate Warna Maps</strong>
          </div>
          <hr>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                Color Start
                  <input type="color" value="#E5000D" class="form-control" id="colorStart">
              </div>
              <div class="col-6">
                 Color End
                   <input type="color" value="#FFFFFF" class="form-control" id="colorEnd">
              </div>
              </div>
             <div class="row mt-2">
                <div class="col-12">
                <button class="btn btn-primary form-control" id="btnGenerateColor">Generate Color</button>
              </div>

          </div>
        </div>
      
    </div>
               
   </div>
 </div>
		
	
@endsection

@section('peta')

<div class="row mt-4 ">
	<div class="col-sm-8 mt-4">
		<div class="card shadow-sm">
			<div class="card-header bg-warning text-white">
				<div class="row">
					<div class="col-6">
						<h5>Peta Sebaran Data {{$tanggalSekarang}}</h5>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div id="map"></div>

			</div>

		</div>
	</div>

	<div class="col-sm-4 mt-4">
		<div class="row row-cols-1 row-cols-md-2">
		  <div class="col mb-4  ">
		    <div class="card border-primary shadow-sm "style="max-width: 18rem;">
		    <div class="card-header d-flex justify-content-center "><h5>Positif</h5></div>
		      <div class="card-body bg-primary">
		        <h6 class="card-title text-white d-flex justify-content-center">Total Kasus</h6>
		        <p class="card-text text-white d-flex justify-content-center ">{{$positif[0]->positif}}</p>
		      </div>
		    </div>
		  </div>

		  <div class="col mb-4 ">
		    <div class="card border-danger shadow-sm"style="max-width: 18rem;">
		    <div class="card-header d-flex justify-content-center "><h5>Meninggal</h5></div>
		      <div class="card-body bg-danger">
		        <h6 class="card-title text-white d-flex justify-content-center">Total Kasus</h6>
		        <p class="card-text text-white d-flex justify-content-center">{{$meninggal[0]->meninggal}}</p>
		      </div>
		    </div>
		  </div>

		 <div class="col mb-4 ">
		    <div class="card border-warning shadow-sm"style="max-width: 18rem;">
		    <div class="card-header d-flex justify-content-center "><h5>Dirawat</h5></div>
		      <div class="card-body bg-warning">
		        <h6 class="card-title text-white d-flex justify-content-center">Total Kasus</h6>
		        <p class="card-text text-white d-flex justify-content-center ">{{$rawat[0]->rawat}}</p>
		      </div>
		    </div>
		  </div>

		  <div class="col mb-4 ">
		    <div class="card border-success shadow-sm"style="max-width: 18rem;">
		    <div class="card-header d-flex justify-content-center"><h5>Sembuh</h5></div>
		      <div class="card-body bg-success">
		        <h6 class="card-title text-white d-flex justify-content-center">Total Kasus</h6>
		        <p class="card-text text-white d-flex justify-content-center">{{$sembuh[0]->sembuh}}</p>
		      </div>
		    </div>
		  </div>
    </div>
</div>
</div>
<br>

<script src="https://pendataan.baliprov.go.id/assets/frontend/map/leaflet.markercluster-src.js"></script>
<!-- <script type="text/javascript" class="init">
  
  $(document).ready(function() {
      $('#example').DataTable();
  } );
  
</script> -->
<script>
  $(document).ready(function () {
    var dataMap=null;
    var dataPos=null;
    var colorMap=[
      "#40E93F",
      "#69EA39",
      "#96EC33",
      "#C7ED2D",
      "#EFE027",
      "#F0AA21",
      "#F2701B",
      "#F33215",
      "#F50E2E"
    ];
    var tanggal = $('#tanggalSearch').val();
    console.log(tanggal);
    $.ajax({
      async:false,
      url:'/getData',
      type:'get',
      dataType:'json',
      data:{date: tanggal},
      success: function(response){
        dataMap = response;
      }
    });
    console.log(dataMap);

    $.ajax({
      async:false,
      url:'/getPositif',
      type:'get',
      dataType:'json',
      data:{date: tanggal},
      success: function(response){
        dataPos = response;
      }
    });
    console.log(dataPos);
    
    $('#btnGenerateColor').on('click',function(e){
      var colorStart = $('#colorStart').val();
      var colorEnd = $('#colorEnd').val();
      $.ajax({
        async:false,
        url:'/create-pallete',
        type:'get',
        dataType:'json',
        data:{start: colorStart, end:colorEnd},
        success: function(response){
          colorMap = response;
          setMapColor();
        }
      });
      
    });

    
   

    var map = L.map('map').setView([-8.655924, 115.216934], 13);
            L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
                        maxZoom: 20,
                        subdomains:['mt0','mt1','mt2','mt3']
                    }).addTo(map);
    setMapColor();
    // define variables
    var lastLayer;
    var defStyle = {opacity:'1',color:'#000000',fillOpacity:'0',fillColor:'#CCCCCC'};
    var selStyle = {color:'#0000FF',opacity:'1',fillColor:'#00FF00',fillOpacity:'1'};
    
    function setMapColor(){
      var markerIcon = L.icon({
        iconUrl: '/marker.png',
        iconSize: [40, 40],
      });
      var BADUNG,BULELENG,BANGLI,DENPASAR,GIANYAR,JEMBRANA,KARANGASEM,KLUNGKUNG,TABANAN;

      dataPos.forEach(function(value,index){
        var colorKab = dataPos[index].nama_kab.toUpperCase();
        console.log(colorKab);
        if(colorKab == "BADUNG"){
          BADUNG = {opacity:'1',color:'#000',fillOpacity:'1',fillColor: '#'+colorMap[index]};
        }else if(colorKab=="BANGLI"){
          BANGLI = {opacity:'1',color:'#000',fillOpacity:'1',fillColor: '#'+colorMap[index]};
        } else if(colorKab=="BULELENG"){
          BULELENG = {opacity:'1',color:'#000',fillOpacity:'1',fillColor: '#'+colorMap[index]};
        }else if(colorKab=="DENPASAR"){
          DENPASAR = {opacity:'1',color:'#000',fillOpacity:'1',fillColor: '#'+colorMap[index]};
        }else if(colorKab=="GIANYAR"){
          GIANYAR = {opacity:'1',color:'#000',fillOpacity:'1',fillColor: '#'+colorMap[index]};
        }else if(colorKab =="JEMBRANA"){
          JEMBRANA = {opacity:'1',color:'#000',fillOpacity:'1',fillColor: '#'+colorMap[index]};
        }else if(colorKab=="KARANGASEM"){
          KARANGASEM = {opacity:'1',color:'#000',fillOpacity:'1',fillColor: '#'+colorMap[index]};
        }else if(colorKab=="KLUNGKUNG"){
          KLUNGKUNG = {opacity:'1',color:'#000',fillOpacity:'1',fillColor: '#'+colorMap[index]};
        }else if(colorKab =="TABANAN"){
          TABANAN = {opacity:'1',color:'#000',fillOpacity:'1',fillColor: '#'+colorMap[index]};
        }

      });

    // Instantiate KMZ parser (async)
          var kmzParser = new L.KMZParser({
          onKMZLoaded: function (layer, name) {
          control.addOverlay(layer, name);
          var markers = L.markerClusterGroup();
          var layers = layer.getLayers()[0].getLayers();

            // fetching sub layer
          layers.forEach(function(layer, index){
          
          var kab  = layer.feature.properties.NAME_2;
          kab = kab.toUpperCase();
          var prov = layer.feature.properties.NAME_1;
          
          
          //
          if(!Array.isArray(dataMap) || !dataMap.length == 0 ){
            // set sub layer default style positif covid
            if(kab == 'BADUNG'){
              layer.setStyle(BADUNG);
            }else if(kab == 'BANGLI'){
              layer.setStyle(BANGLI);
            }else if(kab == 'BULELENG'){
              layer.setStyle(BULELENG);
            }else if(kab == 'DENPASAR'){
              layer.setStyle(DENPASAR);
            }else if(kab == 'GIANYAR'){
              layer.setStyle(GIANYAR);
            }else if(kab == 'JEMBRANA'){
              layer.setStyle(JEMBRANA);
            }else if(kab == 'KARANGASEM'){
              layer.setStyle(KARANGASEM);
            }else if(kab == 'KLUNGKUNG'){
              layer.setStyle(KLUNGKUNG);
            }else if(kab == 'TABANAN'){
              layer.setStyle(TABANAN);
            } 


            
            // peparing data format
            var data = '<table width="300">';
                data +='  <tr>';
                data +='    <th colspan="2">'+kab+'</th>';
                data +='  </tr>';
              
              
               data +='  <tr>';
              data +='    <td>Kabupaten</td>';
              data +='    <td>: '+kab+'</td>';
              data +='  </tr>';              

              
             data +='  <tr style="color:red">';
              data +='    <td>Positif</td>';
              data +='    <td>: '+dataMap[index].positif+'</td>';
              data +='  </tr>';
              

              data +='  <tr style="color:green">';
              data +='    <td>Sembuh</td>';
              data +='    <td>: '+dataMap[index].sembuh+'</td>';
              data +='  </tr>'; 

              data +='  <tr style="color:black">';
              data +='    <td>Meninggal</td>';
              data +='    <td>: '+dataMap[index].meninggal+'</td>';
              data +='  </tr>';

          
              data +='  <tr style="color:blue">';
              data +='    <td>Dalam Perawatan</td>';
              data +='    <td>: '+dataMap[index].rawat+'</td>';
              data +='  </tr>';               
              
              
            data +='</table>';
    
            if(kab == 'BANGLI'){
              markers.addLayer( 
                L.marker([-8.254251, 115.366936] ,{
                  icon: markerIcon
                }).bindPopup(data).addTo(map)
              );
            }
            else if(kab == 'GIANYAR'){
              markers.addLayer( 
                L.marker([-8.422739, 115.255700] ,{
                  icon: markerIcon
                }).bindPopup(data).addTo(map)
              );

            }else if(kab == 'KLUNGKUNG'){
              markers.addLayer( 
                L.marker([-8.487338, 115.380029] ,{
                  icon: markerIcon
                }).bindPopup(data).addTo(map)
              );

            }else{
              markers.addLayer( 
                L.marker(layer.getBounds().getCenter(),{
                  icon: markerIcon
                }).bindPopup(data).addTo(map)
              );
            }

          }else{
            var data = "Tidak ada Data pada tanggal tersebut"
            layer.setStyle(defStyle);
          }
          layer.bindPopup(data);
        });
        map.addLayer(markers);
        layer.addTo(map);
        }
    });
  
    // Add remote KMZ files as layers (NB if they are 3rd-party servers, they MUST have CORS enabled)
    kmzParser.load('bali-kabupaten.kmz');
    // kmzParser.load('https://raruto.github.io/leaflet-kmz/examples/globe.kmz');

    var control = L.control.layers(null, null, {
        collapsed: false
    }).addTo(map);
    $('.leaflet-control-layers').hide();
    }
  });
</script>


@endsection

@section('content')
<br>
 
<div class="col">
		
			<div class="card-header bg-info">
				<div class="row">
					<div class="col-6">
						<h5 class="text-white">Tabel Sebaran Data {{$tanggalSekarang}}</h5>
					</div>
				</div>
			</div>
			
				
				<table class="table table-hover table-dark table-responsive-md " >		
					<tr class="bg-dark">
            <th>No</th>
            <th>Record Date</th>
            <th>Kabupaten</th>
            <th>Rawat</th>
            <th>Sembuh</th>
            <th>Meninggal</th>
            <th>Positif</th>
						
					</tr>
					
					<?php 
						if(count($data)>0){
							$s	=	'';
							foreach($data as $val){
								$s++;
					?>
					<tr>
						<td class="bg-secondary text-light" ><?php echo $s ?? '';?></td>
            <td class="bg-secondary text-light">{{$val->tanggal}}</td>
						<td class="bg-secondary text-light">{{$val->nama_kab}}</td>
						<td class="bg-secondary text-light">{{$val->rawat}}</td>
						<td class="bg-secondary text-light">{{$val->sembuh}}</td>
            <td class="bg-secondary text-light">{{$val->meninggal}}</td>
						<td class="bg-secondary text-light">{{$val->positif}}</td>
			
					</tr>
					<?php 
							}
						}else{
					?>
						<tr><td colspan="6" align="center">No Record(s) Found!</td></tr>
					<?php } ?>					
				
				</table>
      </div>
			</div>
      <br>
		



@endsection

@section('footer')

<blockquote class="blockquote text-center">
  <p class="mb-0">Friska trisnadewi</p>
  <footer class="blockquote-footer">1705551078 </footer>
</blockquote>



@endsection
