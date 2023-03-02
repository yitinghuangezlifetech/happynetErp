<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">設定SEO</h3>
    </div>
    <div class="card-body">
        @if($data)
        <input type="hidden" name="seo[id]" value="{{$data->id}}">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="page_title">Page Title</label>
                    <input type="text" class="form-control" id="page_title" name="seo[page_title]" value="{{$data->page_title}}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" name="seo[meta_title]" value="{{$data->meta_title}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <input type="text" class="form-control" id="meta_keywords" name="seo[meta_keywords]" value="{{$data->meta_keywords}}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <input type="text" class="form-control" id="meta_description" name="seo[meta_description]" value="{{$data->meta_description}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="og_title">Og Title</label>
                    <input type="text" class="form-control" id="og_title" name="seo[og_title]" value="{{$data->og_title}}">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="og_description">Og Description</label>
                    <input type="text" class="form-control" id="og_description" name="seo[og_description]" value="{{$data->og_description}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="og_image">Og Image (<span style="color: red; font-size:8px">建議尺寸為 1200px*630 px</span>)</label>
                    <input type="file" class="form-control dropify" id="og_image" name="seo[og_image]" data-id="{{$id}}" data-model="{{$model}}" data-field="og_image" data-max-width="1200" data-max-height="630" data-default-file="{{$data->og_image}}" data-max-file-size="3M">
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="page_title">Page Title</label>
                    <input type="text" class="form-control" id="page_title" name="seo[page_title]" value="">
                </div>
            </div>
            <div class="col-sm-6">    
                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" name="seo[meta_title]" value="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <input type="text" class="form-control" id="meta_keywords" name="seo[meta_keywords]" value="">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <input type="text" class="form-control" id="meta_description" name="seo[meta_description]" value="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="og_title">Og Title</label>
                    <input type="text" class="form-control" id="og_title" name="seo[og_title]" value="">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="og_description">Og Description</label>
                    <input type="text" class="form-control" id="og_description" name="seo[og_description]" value="">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="og_image">Og Image (<span style="color: red; font-size:8px">建議尺寸為 1200px*630 px</span>)</label>
                    <input type="file" class="form-control dropify" id="og_image" name="seo[og_image]" data-id="" data-model="" data-field="" data-max-file-size="3M">
                </div>
            </div>
        </div>
        @endif
    </div>
</div>