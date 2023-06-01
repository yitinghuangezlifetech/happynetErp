<div class="card card-secondary disabled" style="margin-top: 10px;">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-12">
        @foreach($data->terms??[] as $log)
        <div class="form-group">
        {{$log->term->title}}：{{$log->term->describe}}<br>@if(!empty($log->term->content))<br>@endif
        {!! $log->term->content !!}
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>