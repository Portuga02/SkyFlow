<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; margin: 0;">
    <div style="max-w: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        
        <!-- Altere para o logo do SkyFlow se desejar -->
        <h2 style="color: #1e293b; margin-top: 0;">Bem-vindo(a) à equipe, <?php echo e(explode(' ', $user->name)[0]); ?>! 🚀</h2>
        
        <p style="color: #475569; line-height: 1.6; font-size: 16px;">
            Você acaba de ser adicionado(a) ao nosso workspace para gerenciarmos nossos projetos juntos.
        </p>

        <p style="color: #475569; line-height: 1.6; font-size: 16px;">
            Aqui estão suas credenciais de acesso exclusivas:
        </p>

        <div style="background-color: #f1f5f9; border-left: 4px solid #2563eb; padding: 15px; border-radius: 6px; margin: 25px 0;">
            <p style="margin: 0 0 10px 0; color: #334155;"><strong>E-mail:</strong> <br><?php echo e($user->email); ?></p>
            <p style="margin: 0; color: #334155;"><strong>Senha temporária:</strong> <br><span style="background: #e2e8f0; padding: 3px 8px; border-radius: 4px; font-family: monospace; letter-spacing: 1px;"><?php echo e($password); ?></span></p>
        </div>

        <p style="color: #475569; line-height: 1.6; font-size: 14px;">
            Recomendamos que você altere sua senha assim que fizer o primeiro login no sistema.
        </p>

        <div style="text-align: center; margin-top: 30px;">
            <a href="<?php echo e(url('/login')); ?>" style="display: inline-block; padding: 14px 28px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">Acessar o Sistema</a>
        </div>
        
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #94a3b8; font-size: 12px;">
        Este é um e-mail automático, por favor não responda.
    </div>
</body>
</html><?php /**PATH C:\Workspace\SkyFlow\resources\views\layouts\team-invite.blade.php ENDPATH**/ ?>