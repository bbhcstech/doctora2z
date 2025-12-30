<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Doctora2z HTML Sitemap</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
  <h1>Doctora2z HTML Sitemap</h1>

  <p>XML sitemap: <a href="{{ url('/sitemap.xml') }}">{{ url('/sitemap.xml') }}</a></p>

  <ul>
    @if(!empty($links) && is_array($links))
      @foreach($links as $link)
        <li><a href="{{ $link['url'] }}">{{ $link['title'] }}</a></li>
      @endforeach
    @else
      <li>No links available</li>
    @endif
  </ul>
</body>
</html>
