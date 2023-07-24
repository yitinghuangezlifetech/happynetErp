<div class="modal fade" id="importForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form id="importForm" method="POSt" action="{{route($menu->slug.'.importData')}}" enctype="multipart/form-data">
            @csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{$menu->menu_name}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						@if($menu->slug == 'dial_records')
						<div class="col-sm-12">
							<div class="form-group">
								<label for="file">通聯資料類型</label>
								<select class="form-control" name="dail_record_type_id" required>
									<option value="">請選擇</option>
									@foreach($dailRecordType->getChildNoFilter??[] as $recordType)
									<option value="{{$recordType->id}}">{{$recordType->type_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						@endif
						<div class="col-sm-12">
							<div class="form-group">
								<label for="file">匯入檔案上傳</label>
								<input type="file" class="form-control" name="file" value="" id="file" placeholder="請上傳檔案" accept=".xlsx,.xls,.csv,.txt">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn bg-gradient-secondary btn-sm" data-dismiss="modal">關閉</button>
					<button type="submit" class="btn bg-gradient-secondary btn-sm">上傳檔案</button>
				</div>
			</div>
		</form>
	</div>
</div>