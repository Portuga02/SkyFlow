<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à equipe</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0b0e17; padding: 40px 15px; margin: 0; color: #f8fafc;">
    
    <!-- Container Central -->
    <div style="max-width: 560px; margin: 0 auto; background-color: #111625; border: 1px solid #1e293b; border-radius: 20px; padding: 40px 32px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);">
        
        <!-- Header / Logo -->
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="display: inline-block; width: 48px; height: 48px; line-height: 48px; border-radius: 14px; background: linear-gradient(135deg, #0284c7, #38bdf8); color: #ffffff; font-size: 22px; font-weight: bold; margin-bottom: 12px;">
                ⚡
            </div>
            <h1 style="color: #ffffff; font-size: 22px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">SkyFlow</h1>
        </div>

        <h2 style="color: #ffffff; font-size: 18px; font-weight: 700; margin: 0 0 12px 0;">
            Bem-vindo(a) à equipe, {{ explode(' ', $user->name)[0] }}! 🚀
        </h2>
        
        <p style="color: #94a3b8; line-height: 1.6; font-size: 14px; margin: 0 0 16px 0;">
            Você foi adicionado(a) ao nosso workspace para organizarmos projetos, tarefas e fluxos de trabalho de forma colaborativa.
        </p>

        <p style="color: #94a3b8; line-height: 1.6; font-size: 14px; margin: 0 0 24px 0;">
            Abaixo estão as suas credenciais temporárias para o primeiro acesso:
        </p>

        <!-- Bloco de Credenciais -->
        <div style="background-color: #181f33; border: 1px solid #334155; border-left: 4px solid #0284c7; padding: 18px; border-radius: 12px; margin-bottom: 24px;">
            <div style="margin-bottom: 12px;">
                <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; display: block; margin-bottom: 2px;">E-mail</span>
                <span style="color: #f1f5f9; font-size: 14px; font-weight: 600;">{{ $user->email }}</span>
            </div>
            <div>
                <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; display: block; margin-bottom: 4px;">Senha Temporária</span>
                <code style="background-color: #0b0e17; border: 1px solid #334155; color: #38bdf8; padding: 4px 10px; border-radius: 6px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 14px; font-weight: bold; letter-spacing: 1px; display: inline-block;">{{ $password }}</code>
            </div>
        </div>

        <p style="color: #64748b; line-height: 1.5; font-size: 12px; margin: 0 0 30px 0;">
            * Por motivos de segurança, recomendamos que você atualize sua senha após realizar o primeiro login.
        </p>

        <!-- Botão CTA -->
        <div style="text-align: center; margin-bottom: 10px;">
            <a href="{{ url('/login') }}" style="display: inline-block; width: 100%; box-sizing: border-box; padding: 14px 24px; background: linear-gradient(135deg, #0284c7, #0369a1); color: #ffffff; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 14px; letter-spacing: 0.5px;">
                ACESSAR PAINEL
            </a>
        </div>
    </div>
    
    <!-- Rodapé -->
    <div style="text-align: center; margin-top: 24px; color: #475569; font-size: 11px;">
        &copy; {{ date('Y') }} SkyFlow. Este é um e-mail automático, favor não responder.
    </div>
</body>
</html>