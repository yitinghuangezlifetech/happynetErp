@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="/plugins/owlcarousel2/dist/assets/owl.carousel.min.css" />
@endsection

@section('content')
<div class="col-md-12">
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">預覽</h3>
        </div>
        <div class="card-footer" style="display: block;">
            <div class="col-md-12">
                <div class="text-center"><h5>市招照片</h5></div>
                <div class="text-center">
                    @if(!empty($photo))
                    <img src="{{$photo->img}}" style="max-width:100%;height:auto;"/>
                    @endif
                </div>
            </div>
            <hr>
            <div class="col-md-12">
                <div class="text-center"><h5>自主輔導管理</h5></div>
            </div>
            <div class="col-md-12 table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="text-left">設管理衛生人員</td>
                            <td class="text-center">
                                @if($route->counseling)
                                    @switch($route->counseling->has_manager_staff)
                                        @case(1) 有 @break
                                        @case(2) 沒有 @break
                                        @case(3) 不適用 @break
                                    @endswitch
                                @endif  
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">自主檢查紀錄</td>
                            <td class="text-center">
                                @if($route->counseling)
                                    @switch($route->counseling->has_self_check)
                                        @case(1) 有 @break
                                        @case(2) 沒有 @break
                                        @case(3) 不適用 @break
                                    @endswitch
                                @endif  
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">持證率 @if($route->counseling){{$route->counseling->certificate_rate}}@endif</td>
                            <td class="text-center">
                                @if($route->counseling)
                                    @switch($route->counseling->certificate)
                                        @case(1) 有 @break
                                        @case(2) 沒有 @break
                                        @case(3) 不適用 @break
                                    @endswitch
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="text-center">
        <button type="button" class="btn bg-gradient-primary" onclick="location.href='{{route('audit_records.failPreview', $routeId)}}'">預覽缺失紀錄</button>
     </div>
</div>
@endsection

@section('js')
<script src="/plugins/owlcarousel2/dist/owl.carousel.min.js"></script>
<script>
$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    responsiveClass:true,
});
</script>
@endsection