<table class="table table-hover text-nowrap">
    <tbody>
      @php
          $lineCount = 1;
      @endphp
      @foreach($projects??[] as $project)
        @php
            $rows = $loop->count;
            $line = ceil($rows / 5);
        @endphp
        @if($loop->first)
        <tr>
        @endif
          <td style="text-align: center;max-width:20%; width:20%">
            <input type="checkbox" name="projects[]" value="{{$project->id}}" @if(isset($logs[$project->id])){{'checked'}}@endif>
            {{$project->name}}
        </td>
        @if($loop->iteration % 5 == 0)
        @php
            $lineCount++;
        @endphp
        </tr>
        <tr>
        @endif  
        @if($loop->last)  
          @if($line == $lineCount)
              @php
                  $remain = ($line * 5) - $rows;
              @endphp
              @for($i=1;$i<=$remain;$i++)
              <td style="max-width:20%; width:20%"></td>
              @endfor
          @endif
        @endif
      @endforeach
    </tbody>
</table>