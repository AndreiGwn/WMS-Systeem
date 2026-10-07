<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nieuwe Schademelding</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; padding: 24px; color: #1e293b;">
    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <div style="background-color: #4f46e5; color: #ffffff; padding: 24px;">
            <div style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.85; font-weight: 600;">Workplace Management System</div>
            <h1 style="margin: 8px 0 0; font-size: 22px; font-weight: 700;">Nieuw Defect Gemeld</h1>
        </div>
        <div style="padding: 24px;">
            <p style="font-size: 15px; margin-top: 0; color: #334155;">Beste Manager,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #475569;">
                Er is zojuist een nieuw defect of ontbrekende apparatuur gemeld door <strong>{{ $ticket->user->name }}</strong> ({{ $ticket->user->email }}).
            </p>
            
            <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin: 20px 0;">
                <div style="font-size: 12px; font-weight: 700; color: #b45309; text-transform: uppercase; margin-bottom: 4px;">Titel melding</div>
                <div style="font-weight: 700; font-size: 16px; color: #78350f;">{{ $ticket->title }}</div>
                
                <div style="margin-top: 10px; font-size: 13px; color: #92400e;">
                    <strong>Locatie / Werkplek:</strong> {{ $ticket->desk ? $ticket->desk->name . ' (' . $ticket->desk->zone . ')' : 'Algemene kantoorruimte' }}
                </div>
                
                <div style="margin-top: 10px; font-size: 14px; color: #78350f; white-space: pre-line; background: #ffffff; padding: 12px; border-radius: 6px; border: 1px solid #fef3c7;">
                    {{ $ticket->description }}
                </div>
            </div>

            @if($ticket->image_path)
                <p style="font-size: 13px; color: #64748b;">
                    📎 Er is een bewijsfoto geüpload. Bekijk deze in het manager dashboard.
                </p>
            @endif

            <div style="margin-top: 28px; text-align: center;">
                <a href="{{ url('/manager/tickets') }}" style="display: inline-block; background-color: #4f46e5; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                    Bekijk Tickets in Dashboard &rarr;
                </a>
            </div>
        </div>
        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; text-align: center; font-size: 12px; color: #94a3b8;">
            WMS Kantoor &bull; Notificatie verzonden via Mailpit (SMTP :1025)
        </div>
    </div>
</body>
</html>
