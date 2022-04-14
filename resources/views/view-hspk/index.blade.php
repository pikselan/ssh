@extends('voyager::master')

@section('content')
      <div class="page-content browse container-fluid">
            <h1>
                  <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                  @if($admin_logo_img == '')
                  <img src="{{ voyager_asset('images/logo-icon-light.png') }}" alt="Logo Icon" style="display: inline; height: 50px;">
                  @else
                  <img src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon" style="display: inline; height: 50px;">
                  @endif
                  Data HSPK Kabupaten Pesawaran
            </h1>
            @include('voyager::alerts')
      </div>

@endsection

@section('css')
@stop

@section('javascript')
@stop