<table>
    <thead>
        <tr>
            <th style="text-align: center">稽核日期</th>
            <th style="text-align: center">輔導委員一</th>
            <th style="text-align: center">輔導委員二</th>
            <th style="text-align: center">商家</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list??[] as $data)
        <tr>
            <td style="text-align: center">{{$data->audit_day}}</td>
            <td style="text-align: center">{{optional($data->mainUser)->name}}</td>
            <td style="text-align: center">{{optional($data->subUser)->name}}</td>
            <td style="text-align: center">{{optional($data->store)->name}}</td>
        </tr>
        @endforeach
    </tbody>
</table>