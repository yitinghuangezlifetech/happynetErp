<table>
    <thead>
        <tr>
            <th style="text-align: center; background-color:gray">組織名稱</th>
            <th style="text-align: center; background-color:gray">成本</th>
            <th style="text-align: center; background-color:gray">應收金額</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($list ?? [] as $data)
            <tr>
                <td style="text-align: center">{{ $data->name }}</td>
                <td style="text-align: center">{{ number_format($data->fee, 2) }}</td>
                <td style="text-align: center">{{ number_format($data->charge_fee, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
