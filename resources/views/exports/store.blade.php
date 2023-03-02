<table>
    <thead>
        <tr>
            <th style="text-align: center; background-color:gray">商店名稱</th>
            <th style="text-align: center; background-color:gray">表單類型</th>
            <th style="text-align: center; background-color:gray">商號地址</th>
            <th style="text-align: center; background-color:gray">聯絡人或負責人</th>
            <th style="text-align: center; background-color:gray">性別</th>
            <th style="text-align: center; background-color:gray">連絡電話</th>
            <th style="text-align: center; background-color:gray">業別</th>
            <th style="text-align: center; background-color:gray">食品業者登錄字號</th>
            <th style="text-align: center; background-color:gray">年度</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list??[] as $data)
        <tr>
            <td style="text-align: center">{{$data->name}}</td>
            <td style="text-align: center">{{optional($data->storeType)->name}}</td>
            <td style="text-align: center">{{$data->address}}</td>
            <td style="text-align: center">{{$data->manager}}</td>
            <td style="text-align: center">{{($data->sex=='m')?'男':'女'}}</td>
            <td style="text-align: center">{{$data->phone}}</td>
            <td style="text-align: center">{{optional($data->storeIndustry)->name}}</td>
            <td style="text-align: center">{{$data->food_no}}</td>
            <td style="text-align: center">{{$data->year}}</td>
        </tr>
        @endforeach
    </tbody>
</table>