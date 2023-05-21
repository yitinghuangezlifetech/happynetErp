<table class="table table-hover text-nowrap">
    <tbody>
      @foreach($terms??[] as $term)
        <tr id="term_id_{{$term->id}}">
          <td style="text-align: left;">
            <input type="radio" class="term_id" name="regulations[{{$row}}][term_id]" value="{{$term->id}}">
            {{$term->title}}:{{$term->describe}}
          </td>
        </tr>
      @endforeach
    </tbody>
</table>