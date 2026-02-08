<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello {{ $data['email'] }}</title>
</head>
<body>
    <h1>Hello {{ $data['email'] }}</h1>
    @php
        $content = $data['content'] ?? null;
    @endphp

    @if(is_array($content))
        @if(isset($content['title']))
            <h2>{{ $content['title'] }}</h2>
        @endif
        @if(isset($content['body']))
            <p>{{ $content['body'] }}</p>
        @else
            <p>{{ json_encode($content) }}</p>
        @endif
    @else
        <p>{{ $content }}</p>
    @endif

    <br>
    Thanks 
</body>
</html>
