<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Hello {{ $data['email'] }}</title>
        <style>
            /* Basic reset for email clients */
            body { margin:0; padding:0; background:#f4f6f8; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
            .container { width:100%; max-width:680px; margin:0 auto; }
            .card { background:#ffffff; border-radius:8px; padding:24px; box-shadow:0 2px 6px rgba(0,0,0,0.06); }
            h1 { font-size:20px; margin:0 0 12px 0; color:#0f1724; }
            h2 { font-size:18px; margin:8px 0 12px 0; color:#0f1724; }
            p { color:#374151; line-height:1.5; margin:0 0 12px 0; }
            .muted { color:#6b7280; font-size:13px; }
            .btn { display:inline-block; padding:10px 16px; background:#2563eb; color:#ffffff; text-decoration:none; border-radius:6px; }
            .footer { text-align:center; color:#9ca3af; font-size:12px; margin-top:18px; }
            @media (max-width:480px) { .card { padding:16px; } h1{font-size:18px;} }
        </style>
    </head>
    <body>
        <table class="container" cellpadding="0" cellspacing="0" role="presentation" width="100%">
            <tr>
                <td style="padding:28px 16px;">
                    <table class="card" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                        <tr>
                            <td>
                                <h1>Hello {{ $data['name'] ?? $data['email'] ?? 'there' }},</h1>

                                @php $content = $data['content'] ?? null; @endphp

                                @if(is_array($content))
                                        @if(isset($content['title']))
                                                <h2>{{ $content['title'] }}</h2>
                                        @endif

                                        @if(isset($content['body']))
                                                <p>{!! nl2br(e($content['body'])) !!}</p>
                                        @else
                                                <p>{{ json_encode($content) }}</p>
                                        @endif

                                        <!-- @if(isset($content['action']) && is_array($content['action']) && isset($content['action']['url']))
                                                <p style="margin-top:12px;">
                                                    <a href="{{ $content['action']['url'] }}" class="btn">{{ $content['action']['text'] ?? 'Open' }}</a>
                                                </p>
                                        @endif -->
                                @else
                                        <p>{!! nl2br(e($content)) !!}</p>
                                @endif

                                <p class="muted">If you didn't expect this email, you can ignore it.</p>
                                <div class="footer">&copy; {{ date('Y') }} UniIdeas • All rights reserved.</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
