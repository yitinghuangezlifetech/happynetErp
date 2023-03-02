@extends('layouts.main')

@section('content')
<div class="col-md-12">
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">{{$route->store->name}}</h3>
        </div>
        <div class="card-footer" style="display: block;">
            <div class="col-md-12">
                <form method="POST" enctype="multipart/form-data" action="{{route('audit_records.uploadPhoto', $routeId)}}">
                    @csrf
                    <div><input type="file" class="dropify" name="img" required></div>
                    <div class="text-center" style="margin-top: 5px"><button type="submit" class="btn bg-gradient-primary">確認</button></div>
                </form>
            </div>
            <hr>
            <div class="col-md-12">
                <div class="text-center"><h5>市招照片</h5></div>
            </div>
            <div class="col-md-12 table-responsive">
                <div class="card card-success">
                    <div class="card-body">
                        <div class="row">
                        @if($photos->count() > 0)  
                            @foreach($photos as $photo)
                            <div class="col-md-12 col-lg-6 col-xl-4" id="area{{$photo->id}}">
                                <div class="card mb-2 bg-gradient-dark">
                                    <img class="card-img-top" src="{{$photo->img}}" style="max-width:100%;height:auto;"><br>
                                </div>
                                <div class="form-group text-center">
                                    <label for="is_main">設為主照片</label>
                                    <input type="radio" class="is_main" name="is_main" value="1" data-id="{{$photo->id}}" @if($photo->is_main==1){{'checked'}}@endif>
                                </div>
                                <div class="form-group text-center">
                                    <button class="btn bg-gradient-danger" onclick="deletePic('{{$photo->id}}')">刪除</button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="text-center">
        <button type="button" class="btn bg-gradient-secondary" onclick="location.href='{{route('audit_records.index', $routeId)}}'">回上一頁</button>
     </div>
</div>
@endsection

@section('js')
<script>
$('.dropify').dropify({
    messages: {
        'default': '請上傳照片'
    }
});
$('.is_main').on('click', function(){
    var id = $(this).data('id');

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        method: 'post',
        url: '{{ route('audit_records.setMainPhoto') }}',
        data: {
            "routeId": '{{$routeId}}',
            "id": id
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                text: res.message
            })
        }
    })
});
var deletePic = function(id){
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        method: 'post',
        url: '{{ route('audit_records.removePhoto') }}',
        data: {
            id: id
        },
        success: function(res){
            $('#area'+id).remove();
        },
        error: function(res) {
            Swal.fire({
                icon: 'error',
                text: res.data.message
            })
        }
    })
}
</script>
@endsection