<h1>Example Module</h1>
<ul>
@foreach($items as $item)
  <li><strong>{{ $item['id'] }}</strong> — {{ $item['title'] }}</li>
@endforeach
</ul>
