@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-md-12">
      <div class="card card-widget widget-user">
        <div class="widget-user-header bg-info">
          @php
            switch ($auditRoute->audit_status) {
              case 0: $status = '未開始';break;
              case 1: $status = '輔導';break;
              case 2: $status = '評核';break;
              case 3: $status = '追評';break;
            }
          @endphp
          <h3 class="widget-user-username">{{$auditRoute->store->name}} ({{$status}})</h3>
          <h5 class="widget-user-desc">聯絡人:{{$auditRoute->store->manager}}</h5>
        </div>
        <div class="widget-user-image">
          @if($mainImage)
          <img class="img-circle elevation-2" src="{{$mainImage->img}}" style="cursor: pointer" onclick="location.href='{{route('audit_records.photos', $auditRoute->id)}}'" alt="商家主照片">
          @else
          <img class="img-circle elevation-2" src="/img/icon-user.png" style="cursor: pointer" onclick="location.href='{{route('audit_records.photos', $auditRoute->id)}}'" alt="商家預設照片">
          @endif
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-sm-4 border-right">
              @if($auditRoute->audit_status == 2)
              <div class="description-block">
                <h5 class="description-header" style="cursor: pointer" @if($auditRoute->status!=4 || $user->role->has_audit_route == 1) onclick="location.href='{{route('audit_records.getFailItems', $auditRoute->id)}}'" @endif>{{$pendings->count()}}/{{$pendingChecks->count()}}</h5>
                <span class="description-text" style="cursor: pointer" @if($auditRoute->status!=4 || $user->role->has_audit_route == 1) onclick="location.href='{{route('audit_records.getFailItems', $auditRoute->id)}}'" @endif>待複檢項目</span>
              </div>
              @endif
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4 border-right">
              <div class="description-block">
                <h5 class="description-header"></h5>
                <span class="description-text" style="cursor: pointer" onclick="location.href='{{route('counselings.index', $auditRoute->id)}}'"><i class="fa fa-edit"></i>&nbsp;自主管理</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4">
              <div class="description-block">
                <h5 class="description-header">
                      @switch($auditRoute->status)
                          @case(0) <span style="color:gray">未開始</span>@break
                          @case(1) <span style="color:cadetblue">開始</span>@break
                          @case(2) <span style="color:coral">稽核中</span>@break
                          @case(3) <span style="color:deeppink">開始上傳</span>@break
                          @case(4) <span style="color:forestgreen">上傳完成</span>@break
                      @endswitch
                </h5>
                <span class="description-text">稽核狀態</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
          </div>
          <div class="col-md-12">
              @if($mainAttributes->count() > 0)
              <ul class="nav nav-pills flex-column">
                  @foreach($mainAttributes as $mainAttribute)
                  <li class="nav-item">
                      <a href="{{route('audit_records.regulations', ['routeId'=>$auditRoute->id, 'mainAttributeId'=>$mainAttribute->id])}}" class="nav-link">
                          {{$mainAttribute->name}}&nbsp;&nbsp;({{$auditRoute->getValidatingRegulations($auditRoute->id, $auditRoute->store_id, $mainAttribute->id)->count()}}/{{$mainAttribute->getRegulationsByType($type)->count()}}) <span class="float-right badge"><i class="right fas fa-angle-right"></i></span>
                      </a>
                  </li>
                  @endforeach
              </ul>
              @endif
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12 text-center">
      <button class="btn bg-gradient-primary" id="finishBtn" data-routeid="{{$auditRoute->id}}">完成檢查</button>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function(){
  $('#finishBtn').on('click', function(){
    var routeId = $(this).data('routeid');
    var pass = true;

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      async: false,
      method: 'post',
      url: '{{ route('audit_routes.hasAuditCompleted') }}',
      data: {
          "id": routeId,
      },
      error: function(rs){
        pass = false;

        Swal.fire({
            icon: 'error',
            title: '提示訊息',
            html: rs.responseJSON.message,
        })
      }
    })

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      async: false,
      method: 'post',
      url: '{{ route('audit_routes.hasPhotoUpload') }}',
      data: {
          "id": routeId,
      },
      error: function(rs){
        pass = false;

        Swal.fire({
            icon: 'error',
            title: '提示訊息',
            html: rs.responseJSON.message,
        })
      }
    })

    if (pass) {
      location.href='{{route('audit_records.finishRecord', $auditRoute->id)}}';
    }
  })
})
</script>
@endsection