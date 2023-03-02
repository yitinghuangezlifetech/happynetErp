@extends('layouts.blank')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-12 text-center">
            <h1>{{$event->name}}</h1><br>
            <h4>{{$request->name}}  預覽</h4>
        </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-12" id="accordion">
            @php
                $i = 1;
            @endphp
            @foreach($request->topics as $k=>$topic)  
            <div class="card card-primary card-outline">
                <a class="d-block w-100" data-toggle="collapse show" href="#collapseOne_{{$k}}">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            {{$i}}. {{$topic['subject']}}
                        </h4>
                    </div>
                </a>
                @if(isset($topic['items']) && count($topic['items']) > 0)
                <div id="collapseOne_{{$k}}" class="collapse show" data-parent="#accordion">
                    <div class="card-body">
                        <div class="form-group">
                        @switch($topic['topic_type'])
                            @case('input')
                                <input type="text" class="form-control form-control-border">
                                @break
                            @case('input')
                                <textarea rows="10" style="width: 100%"></textarea>
                                @break
                            @case('select')
                                <select class="form-control">
                                    <option value="">請選擇</option>
                                    @foreach($topic['items']??[] as $item)
                                    <option>{{$item['name']}}</option>
                                    @endforeach
                                </select>
                                @break;
                            @case('radio')
                                @foreach($topic['items']??[] as $item)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radio">
                                    <label class="form-check-label">{{$item['name']}}</label>
                                </div>
                                @endforeach
                                @break
                            @case('checkbox')
                                @foreach($topic['items']??[] as $item)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="checkbox">
                                    <label class="form-check-label">{{$item['name']}}</label>
                                </div>
                                @endforeach
                                @break;
                        @endswitch
                        </div>
                    </div>
                </div>
                @else
                <div id="collapseOne_{{$k}}" class="collapse show" data-parent="#accordion">
                    <div class="card-body">
                        <div class="form-group">
                        @switch($topic['topic_type'])
                            @case('input')
                                <input type="text" class="form-control form-control-border">
                                @break
                            @case('text_area')
                                <textarea rows="10" style="width: 100%"></textarea>
                                @break
                        @endswitch
                        </div>
                    </div>
                </div>
                @endif
            </div>
                @php $i++; @endphp
            @endforeach
        </div>
    </div>
</section>
@endsection