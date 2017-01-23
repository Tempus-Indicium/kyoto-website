<table>
@foreach($stations as $station):
  <tr>
    <td>
      {{ $station->name }}
    </td>
    <td>
      {{ $station->latitude }}
    </td>
    <td>
      {{ $station->longitude }}
    </td>
  </tr>
@endforeach
</table>
