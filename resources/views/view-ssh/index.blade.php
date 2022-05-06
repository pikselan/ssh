@extends('voyager::master')

@section('content')
      <div id="data-loader" class="data-loader hidden">
            <?php $admin_loader_img = Voyager::setting('admin.loader', ''); ?>
            @if($admin_loader_img == '')
                  <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Data Loader">
            @else
                  <img src="{{ Voyager::image($admin_loader_img) }}" alt="Data Loader">
            @endif
      </div>

      <div class="page-content browse container-fluid">
            <h1>
                  <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                  @if($admin_logo_img == '')
                  <img src="{{ voyager_asset('images/logo-icon-light.png') }}" alt="Logo Icon" style="display: inline; height: 50px;">
                  @else
                  <img src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon" style="display: inline; height: 50px;">
                  @endif
                  Data SSH Kabupaten Pesawaran
            </h1>
            @include('voyager::alerts')
            <div class="row">
                  <div class="col-md-12">
                        <div class="panel panel-bordered">
                              <div class="panel-body">
                                    <div class="table-responsive">
                                          <table class="table" style="width:100%">
                                                <tr>
                                                      <td>
                                                            <label for="select_periode">Periode data : </label>
                                                            <form class="form-inline" action="{{url()->current()}}" method="POST" enctype="multipart/form-data">
                                                                  @csrf
                                                                  <div class="form-group">
                                                                        <select id="select_periode" class="form-control" name="select_periode">
                                                                              <option selected="true" disabled="disabled"> - </option>
                                                                              @foreach($list_periode as $key => $item)
                                                                                    <option value="{{$item->periode}}"> {{$item->periode}} </option>
                                                                              @endforeach
                                                                        </select>
                                                                  </div>
                                                                  <button type="submit" value="Submit" class="btn btn-warning">Pilih</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <tr>
                                                      <td>
                                                            <label for="kode_objek">Kode Objek Rekening : </label>
                                                            <form class="form-inline" action="{{url()->current()}}" method="POST" enctype="multipart/form-data">
                                                                  @csrf
                                                                  <div class="form-group hidden">
                                                                        <select id="select_periode" class="form-control" name="select_periode">
                                                                              <option selected="true" disabled="disabled"> - </option>
                                                                              @foreach($list_periode as $key => $item)
                                                                                    <option value="{{$item->periode}}" {{ ( $item->periode == $select_periode) ? 'selected' : '' }}> {{$item->periode}} </option>
                                                                              @endforeach
                                                                        </select>
                                                                  </div>
                                                                  <div class="form-group">
                                                                        <select id="kode_rincian_objek" class="form-control" name="kode_rincian_objek">
                                                                              <option selected="true" disabled="disabled"> - </option>
                                                                              @foreach($list_kode_rincian_objek as $key => $item)
                                                                                    <option value="{{$item->kode_produk}}">{{$item->kode_produk}} -  {{$item->nama}}</option>
                                                                              @endforeach
                                                                        </select>
                                                                  </div>
                                                                  <div class="form-group">
                                                                        <select id="kode_sub_rincian_objek" class="form-control" name="kode_sub_rincian_objek">
                                                                              <option selected="true" disabled="disabled"> - </option>
                                                                        </select>
                                                                  </div>
                                                                  <div class="form-group">
                                                                        <select id="kode_sub_sub_rincian_objek" class="form-control" name="kode_sub_sub_rincian_objek">
                                                                              <option selected="true" disabled="disabled"> - </option>
                                                                        </select>
                                                                  </div>
                                                                  <button type="submit" value="Submit" class="btn btn-warning">Pilih</button>
                                                            </form>
                                                      </td>
                                                </tr>
                                                <tr>
                                                      <td>
                                                            <label for="kode_objek">Filter : </label>
                                                            <button type="button" class="btn btn-default" disabled="disabled">{{$select_periode}}</button>
                                                            @if($kode_sub_sub_rincian_objek != '')
                                                                  <button type="button" class="btn btn-default" disabled="disabled">{{$kode_sub_sub_rincian_objek}} </button>
                                                                  <a class="btn btn-warning" href="{{url()->current()}}" role="button" style="text-decoration:none;">Reset</a>
                                                            @endif
                                                      </td>
                                                </tr>
                                                <tr>
                                                      <td>
                                                            <label for="kode_objek">Peraturan : </label>
                                                            {{$data_peraturan->keterangan}} | 
                                                            
                                                            @foreach(json_decode($data_peraturan->file) as $file)
                                                                  <a class="btn btn-default" href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank" style="text-decoration:none;">
                                                                  Unduh
                                                                  </a>
                                                            @endforeach
                                                      </td>
                                                </tr>
                                          </table>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="panel panel-bordered">
                              <div class="panel-body">
                                    <div class="table-responsive">
                                          <table id="empTable" class="table table-hover" style="width:99%">
                                                <thead>
                                                      <tr>
                                                            <th></th>
                                                            <th>Id</th>
                                                            <th>Kode Produk</th>
                                                            <th>Kode Belanja</th>
                                                            <th>Nama</th>
                                                            <th>Spesifikasi</th>
                                                            <th>Satuan</th>
                                                            <th>Harga</th>
                                                            <th>Periode</th>
                                                      </tr>
                                                </thead>
                                          </table>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
@endsection

@section('css')
<!-- Datatable CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-html5-2.2.2/datatables.min.css"/>
<style>
.data-loader {
      background: rgba(255, 255, 255, 0.6);
      position: fixed;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      z-index: 99;
}
.data-loader.hidden {
      display: none;
}
.data-loader img {
      width: 100px;
      height: 100px;
      position: absolute;
      top: 50%;
      left: 50%;
      margin-left: -50px;
      margin-right: -50px;
      -webkit-animation: spin 1s linear infinite;
      animation: spin 1s linear infinite;
}
</style>
@stop

@section('javascript')
<!-- Datatable JS -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-html5-2.2.2/datatables.min.js"></script>

<script type="text/javascript"> 
function numberWithCommas(x) {
      return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
}

function format ( d ) {
      // `d` is the original data object for the row
      Object.keys(d).forEach(
            (key) => (d[key] === null) ? d[key] = '' : d[key]
      );

      return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;width:100%;">'+
            '<tr>'+
                  '<td>Kode Produk</td>'+
                  '<td>: '+d.kode_produk+'</td>'+
                  '<td>Satuan</td>'+
                  '<td>: '+d.satuan+'</td>'+
            '</tr>'+
            '<tr>'+
                  '<td>Kode Belanja</td>'+
                  '<td>: '+d.kode_belanja+'</td>'+
                  '<td>Harga</td>'+
                  '<td>: '+d.harga+'</td>'+
            '</tr>'+
            '<tr>'+
                  '<td>Nama</td>'+
                  '<td>: '+d.nama+'</td>'+
                  '<td colspan=2></td>'+
            '</tr>'+
            '<tr>'+
                  '<td>Spesifikasi</td>'+
                  '<td>: '+d.spesifikasi+'</td>'+
                  '<td>Periode</td>'+
                  '<td>: '+d.periode+'</td>'+
            '</tr>'+
      '</table>';
}

$(document).ready(function(){
      var table = $('#empTable').DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 2000,
            ajax: "{{route('getData')}}?select_periode={{$select_periode}}&kode_sub_sub_rincian_objek={{$kode_sub_sub_rincian_objek}}",
            columns: [
                  {
                        "className": 'dt-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                  },
                  { data: 'id' },
                  { data: 'kode_produk' },
                  { data: 'kode_belanja' },
                  { data: 'nama' },
                  { data: 'spesifikasi' },
                  { data: 'satuan' },
                  { 
                        data: 'harga',
                        className: 'dt-body-right',
                        render: function ( data, type, row ) {
                              if(data != null) {
                                    return numberWithCommas(data);
                              } else {
                                    return null;
                              }
                        } 
                  },
                  { data: 'periode' },
            ],
            columnDefs: [{
                  "targets": [ 1, 8 ],
                  "visible": false,
                  "searchable": false
            }],
            order: [[1, 'asc']],
            language: {
                  url: '/datatables/localisation/id.json'
            },
            dom: 'Bfrtip',
            buttons: [
                  'pageLength',
                  $.extend( true, {}, {
                        extend: 'excelHtml5',
                        title: 'Data SSH dan SBM Kabupaten Pesawaran',
                        messageTop: 'Data yang ditampilkan merupakan versi {{$select_periode}}',
                        exportOptions: {
                              modifier : {
                                    // DataTables core
                                    order : 'current',  // 'current', 'applied', 'index',  'original'
                                    page : 'all',      // 'all',     'current'
                                    search : 'applied'     // 'none',    'applied', 'removed'
                              },
                              columns: [ 2, 3, 4, 5, 6, 7 ],
                              format: {
                                    body: function ( data, row, column, node ) {
                                          return column === 5 ?
                                                data.replace( /[.]/g, '' ) :
                                                data;
                                    }
                              }
                        }
                  } ),
                  $.extend( true, {}, {
                        extend: 'pdfHtml5',
                        // download: 'open',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        title: 'Data SSH dan SBM Kabupaten Pesawaran',
                        messageTop: 'Data yang ditampilkan merupakan versi {{$select_periode}}',
                        exportOptions: {
                              modifier : {
                                    // DataTables core
                                    order : 'current',  // 'current', 'applied', 'index',  'original'
                                    page : 'all',      // 'all',     'current'
                                    search : 'applied'     // 'none',    'applied', 'removed'
                              },
                              columns: [ 2, 3, 4, 5, 6, 7 ],
                        }
                  } ),
            ],

            @if($admin == 'superadmin' || $admin == 'admin' )
                  lengthMenu: [
                        [ 10, 25, 50, 100, -1 ],
                        [ '10 baris', '25 baris', '50 baris', '100 baris', 'Tampilkan Semua' ]
                  ],
            @else
                  lengthMenu: [
                        [ 10, 25, 50, 100 ],
                        [ '10 baris', '25 baris', '50 baris', '100 baris' ]
                  ],
            @endif
      });

      // Add event listener for opening and closing details
      $('#empTable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
      
            if ( row.child.isShown() ) {
                  // This row is already open - close it
                  row.child.hide();
                  tr.removeClass('shown');
            }
            else {
                  // Open this row
                  row.child( format(row.data()) ).show();
                  tr.addClass('shown');
            }
      } );
      
      $('#kode_rincian_objek').change(function(){
            var id = $(this).val();
            $('#kode_sub_rincian_objek').find('option').not(':first').remove();
            $('#kode_sub_sub_rincian_objek').find('option').not(':first').remove();
            $.ajax({
                  url: '{{route('getSubRincianObjek')}}?select_periode={{$select_periode}}&select_rincian_objek='+id,
                  type: 'get',
                  dataType: 'json',
                  beforeSend: function(){
                        $('#data-loader').removeClass('hidden')
                  },
                  success: function(response){
                        setTimeout(function() {
                              var len = 0;
                                    if(response['data'] != null){
                                    len = response['data'].length;
                              }
                              if(len > 0){
                                    for(var i=0; i<len; i++){
                                          var kode_produk = response['data'][i].kode_produk;
                                          var nama = response['data'][i].nama;
                                          var option = "<option value='"+kode_produk+"'>"+kode_produk+" - "+nama+"</option>";
                                          $("#kode_sub_rincian_objek").append(option); 
                                    }
                              }
                        }, 500);
                  },
                  complete: function () {
                        $('#data-loader').addClass('hidden')
                  },
            });
      });
      
      $('#kode_sub_rincian_objek').change(function(){
            var id = $(this).val();
            $('#kode_sub_sub_rincian_objek').find('option').not(':first').remove();
            $.ajax({
                  url: '{{route('getSubSubRincianObjek')}}?select_periode={{$select_periode}}&select_sub_rincian_objek='+id,
                  type: 'get',
                  dataType: 'json',
                  beforeSend: function(){
                        $('#data-loader').removeClass('hidden')
                  },
                  success: function(response){
                        setTimeout(function() {
                              var len = 0;
                                    if(response['data'] != null){
                                    len = response['data'].length;
                              }
                              if(len > 0){
                                    for(var i=0; i<len; i++){
                                          var kode_produk = response['data'][i].kode_produk;
                                          var nama = response['data'][i].nama;
                                          var option = "<option value='"+kode_produk+"'>"+kode_produk+" - "+nama+"</option>";
                                          $("#kode_sub_sub_rincian_objek").append(option); 
                                    }
                              }
                        }, 500);
                  },
                  complete: function () {
                        $('#data-loader').addClass('hidden')
                  },
            });
      });
});
</script>
@stop