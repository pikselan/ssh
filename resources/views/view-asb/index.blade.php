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
                  Data ASB Kabupaten Pesawaran
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
                                                            <label for="kode_objek">Filter : </label>
                                                            <button type="button" class="btn btn-default" disabled="disabled">{{$select_periode}}</button>
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
                                                            <th>No</th>
                                                            <th>Nama</th>
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

      var listSub = '';
      d.sub_turunan.forEach((item, index) => {
         listSub += '<tr><td></td><td></td><td>' + item.nama + '</td><td>' + numberWithCommas(item.nilai) + '</td></tr>';
      });

      return '<table class="table table-hover" style="width:100%;">'+
            '<thead>'+
            '<tr>'+
                  '<th></th>'+
                  '<th></th>'+
                  '<th>Nama</th>'+
                  '<th>Nilai</th>'+
            '</tr>'+
            '</thead>'+
            '<tbody>'
            +listSub+
            '<tr>'+
                  '<td colspan=4 style="font-size: smaller;font-weight: 300;">Periode '+d.periode+'</td>'+
            '</tr>'+
            '</tbody>'+
      '</table>';
}

$(document).ready(function(){
      var table = $('#empTable').DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 2000,
            ajax: "{{route('getDataAsb')}}?select_periode={{$select_periode}}",
            columns: [
                  {
                        "className": "dt-control",
                        "orderable": false,
                        "data": null,
                        "defaultContent": "",
                  },
                  { data: 'id' },
                  { data: 'kode' },
                  { data: 'nama' },
                  { data: 'periode' },
            ],
            columnDefs: [{
                  "targets": [ 1, 4 ],
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
                        extend: 'pdfHtml5',
                        pageSize: 'LEGAL',
                        title: 'Data ASB Kabupaten Pesawaran',
                        messageTop: 'Data yang ditampilkan merupakan versi {{$select_periode}}',
                        customize: function (doc) {
                           // Get the row data in in table order and search applied
                           var table = $('#empTable').DataTable();
                           var rowData = table.rows( {order: 'current', search:'applied'} ).data();
                           var headerLines = 0;  // Offset for accessing rowData array

                           var newBody = []; // this will become our new body (an array of arrays(lines))
                           //Loop over all lines in the table
                           doc.content[2].table.body.forEach(function(line, i){
                              // Remove detail-control column
                              newBody.push(
                                 [line[2], line[3], line[0]]
                              );

                              if (line[0].style !== 'tableHeader' && line[0].style !== 'tableFooter') {
                                 var data = rowData[i - headerLines];

                                 // Append child data, matching number of columns in table
                                 data.sub_turunan.forEach(function(sub, i){
                                    newBody.push(
                                       [
                                          {text: '', style:''},
                                          {text: sub.nama, style:''},
                                          {text: numberWithCommas(sub.nilai), style:''},
                                       ]
                                    );
                                 })
                              } else {
                                 headerLines++;
                              }
                           });

                           //Overwrite the old table body with the new one.
                           doc.content[2].table.headerRows = 1;
                           //doc.content[2].table.widths = [50, 50, 50, 50, 50, 50];
                           doc.content[2].table.body = newBody;
                           doc.content[2].layout = 'lightHorizontalLines';

                           doc.styles = {
                              subheader: {
                                 fontSize: 10,
                                 bold: true,
                                 color: 'black'
                              },
                              tableHeader: {
                                 bold: true,
                                 fontSize: 10.5,
                                 color: 'black'
                              },
                              lastLine: {
                                 bold: true,
                                 fontSize: 11,
                                 color: 'blue'
                              },
                              defaultStyle: {
                                 fontSize: 10,
                                 color: 'black',
                                 text:'center'
                              }
                           }
                        }
                  }),
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
});
</script>
@stop