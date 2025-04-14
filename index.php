<?php
require_once 'conexao.php';
$sql = "SELECT * FROM textos_site";
$res = $conn->query($sql);
$textos = [];
while ($t = $res->fetch_assoc()) {
    $textos[$t['id']] = $t['conteudo'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Sistema de controle de ponto moderno e seguro. Registre seus horários com fotos, acompanhe seu banco de horas e receba alertas automáticos!">
    <title>Controle de Ponto Inteligente - Controle de Ponto Moderno</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Variáveis de cores e estilos */
        :root {
            --primary-color: #0099ff;
            --secondary-color: #0055ff;
            --gradient: linear-gradient(130deg, #131313, #1c1c1c);
            --gradient-primary: linear-gradient(45deg, #0099ff, #0055ff);
            --gradient-dark: linear-gradient(130deg, #0a0a0a, #131313);
            --text-color: #e0e0e0;
            --light-text: #6c757d;
            --background-color: #131313;
            --card-bg: rgba(36, 36, 53, 0.8);
            --card-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --success-color: #00ff88;
            --error-color: #ff4444;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        /* Reset e estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: var(--gradient);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Container principal */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        header {
            background: var(--gradient-dark);
            color: var(--text-color);
            text-align: center;
            padding: 6rem 2rem;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--glass-border);
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(0, 153, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 85, 255, 0.1) 0%, transparent 50%);
            z-index: 1;
        }

        header h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
            font-weight: 700;
        }

        header p {
            font-size: 1.4rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto 2rem;
            position: relative;
            z-index: 2;
            line-height: 1.8;
        }

        .header-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .header-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem 2rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 1.1rem;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .header-btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 153, 255, 0.3);
        }

        .header-btn-secondary {
            background: var(--glass-bg);
            color: var(--text-color);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
        }

        .header-btn i {
            font-size: 1.2rem;
        }

        .header-btn:hover {
            transform: translateY(-3px);
        }

        .header-btn-primary:hover {
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }

        .header-btn-secondary:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
        }

        /* Seção de Benefícios */
        .beneficios {
            padding: 6rem 2rem;
            background: transparent;
            position: relative;
        }

        .beneficios::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-dark);
            z-index: -1;
        }

        .beneficios h2 {
            text-align: center;
            margin-bottom: 4rem;
            color: var(--primary-color);
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
        }

        .beneficios h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: 3px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .item {
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            text-align: center;
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: var(--transition);
        }

        .item:hover::before {
            transform: scaleX(1);
        }

        .item:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
            border-color: var(--primary-color);
        }

        .item i {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .item h3 {
            color: var(--text-color);
            margin-bottom: 1rem;
            font-size: 1.8rem;
            font-weight: 500;
        }

        .item p {
            color: var(--light-text);
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* Seção de Preço */
        .preco {
            background: var(--gradient-dark);
            color: var(--text-color);
            text-align: center;
            padding: 6rem 2rem;
            position: relative;
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
        }

        .preco::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 30%, rgba(0, 153, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(0, 85, 255, 0.1) 0%, transparent 50%);
            z-index: 1;
        }

        .preco h2 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: var(--success-color);
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .preco p {
            font-size: 1.4rem;
            margin-bottom: 3rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            z-index: 2;
            line-height: 1.8;
        }

        .btn {
            display: inline-block;
            background: var(--gradient-primary);
            color: white;
            padding: 1.2rem 2.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(0, 153, 255, 0.3);
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
            transition: var(--transition);
        }

        .btn:hover::before {
            transform: translateX(100%);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }

        /* Footer */
        footer {
            background: var(--gradient-dark);
            color: var(--text-color);
            text-align: center;
            padding: 4rem 2rem;
            font-size: 1rem;
            border-top: 1px solid var(--glass-border);
            position: relative;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-section {
            text-align: left;
            position: relative;
        }

        .footer-section h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 500;
        }

        .footer-section p {
            color: var(--light-text);
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .social-links {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            color: var(--text-color);
            font-size: 1.8rem;
            transition: var(--transition);
            position: relative;
        }

        .social-links a::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            background: var(--glass-bg);
            border-radius: 50%;
            z-index: -1;
            transition: var(--transition);
        }

        .social-links a:hover {
            color: var(--primary-color);
            transform: translateY(-5px);
        }

        .social-links a:hover::before {
            background: var(--gradient-primary);
            transform: scale(1.2);
        }

        /* Animações */
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        .item {
            animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            opacity: 0;
        }

        .item:nth-child(2) { animation-delay: 0.2s; }
        .item:nth-child(3) { animation-delay: 0.4s; }
        .item:nth-child(4) { animation-delay: 0.6s; }

        /* Responsividade */
        @media screen and (max-width: 768px) {
            header h1 {
                font-size: 2.5rem;
            }

            .beneficios h2,
            .preco h2 {
                font-size: 2rem;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .item {
                margin: 0 1rem;
                padding: 2rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 2rem;
            }

            .footer-section {
                text-align: center;
            }

            .social-links {
                justify-content: center;
            }

            .header-buttons {
                flex-direction: column;
                gap: 1rem;
                padding: 0 1rem;
            }

            .header-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Efeitos de hover */
        .item:hover h3 {
            color: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Utilitários */
        .horas-extras {
            color: var(--success-color);
            text-shadow: 0 2px 4px rgba(0, 255, 136, 0.3);
        }

        .horas-faltantes {
            color: var(--error-color);
            text-shadow: 0 2px 4px rgba(255, 68, 68, 0.3);
        }

        /* Scroll Indicator */
        .scroll-indicator-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: none;
            z-index: 100;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            padding: 15px 25px;
            border-radius: 50px;
            border: 2px solid var(--primary-color);
            box-shadow: 0 0 20px rgba(0, 153, 255, 0.3);
            backdrop-filter: blur(5px);
        }

        .scroll-text {
            color: white;
            font-size: 1rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .scroll-arrow {
            color: var(--primary-color);
            font-size: 24px;
            animation: bounceArrow 2s infinite;
        }

        @keyframes bounceArrow {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        /* Mostrar apenas em dispositivos móveis */
        @media screen and (max-width: 768px) {
            .scroll-indicator-container {
                display: block;
            }
        }

        /* Ocultar quando rolar a página */
        .scroll-indicator-container.hidden {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s ease;
        }

        /* Substituir o elemento scroll-indicator anterior */
        .scroll-indicator {
            display: none;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="header-content">
                <h1>📊 Controle de Ponto Inteligente</h1>
                <p>Automatize seus registros, evite erros e tenha total controle do seu tempo! Uma solução completa para
                    gestão de jornada de trabalho.</p>
                <div class="header-buttons">
                    <a href="login.php" class="header-btn header-btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Entrar
                    </a>
                    <a href="cadastro.php" class="header-btn header-btn-secondary">
                        <i class="fas fa-user-plus"></i>
                        Criar Conta
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section class="beneficios">
        <div class="container">
            <h2>Por que escolher nosso sistema?</h2>
            <div class="grid">
                <div class="item">
                    <i class="fas fa-clock"></i>
                    <h3>Registros Precisos</h3>
                    <p>Acompanhe suas horas com um sistema confiável e eficiente, garantindo a precisão dos seus
                        registros.</p>
                </div>
                <div class="item">
                    <i class="fas fa-camera"></i>
                    <h3>Registro com Fotos</h3>
                    <p>Comprove sua presença adicionando imagens aos registros, aumentando a segurança e transparência.
                    </p>
                </div>
                <div class="item">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Segurança Total</h3>
                    <p>Seus dados são protegidos com criptografia avançada e acessíveis apenas para você e sua empresa.
                    </p>
                </div>
                <div class="item">
                    <i class="fas fa-file-pdf"></i>
                    <h3>Relatórios e PDF</h3>
                    <p>Exportação fácil para controle de jornada e auditorias, com relatórios detalhados e
                        personalizados.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="preco">
        <div class="container">
            <h2>🔹 Menos de R$1 por dia para ter controle total! 🔹</h2>
            <p>💰 Apenas <strong>R$19,90/mês</strong>, para ter controle total do seu ponto! Invista na sua organização
                e evite dores de cabeça. Sem taxas ocultas ou surpresas.</p>
            <a href="https://wa.me/5538998100827" class="btn" target="_blank">
                <i class="fas fa-credit-card"></i>
                Pague por PIX
            </a>
        </div>
    </section>

    <section class="recursos">
        <div class="container">
            <h2>Recursos Exclusivos</h2>
            <div class="grid">
                <div class="item">
                    <i class="fas fa-bell"></i>
                    <h3>Controle de Horas Inteligente</h3>
        <p>Registro preciso e automático do seu tempo de trabalho, com visualização clara e organizada dos seus dados.</p>
    </div>
                <div class="item">
                    <i class="fas fa-chart-line"></i>
                    <h3>Controle Prático de Horas</h3>
    <p>Registro fácil e organizado do seu tempo de trabalho, com visualização clara do seu histórico diário/semanal.</p>
</div>
                <div class="item">
                    <i class="fas fa-mobile-alt"></i>
                    <h3>Acesso Mobile</h3>
                    <p>Registre seu ponto de qualquer lugar, a qualquer hora, com nosso aplicativo otimizado.</p>
                </div>
                <div class="item">
                    <i class="fas fa-lock"></i>
                    <h3>Conformidade LGPD</h3>
                    <p>Seus dados protegidos de acordo com a legislação, garantindo sua privacidade e segurança.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Contato</h3>
                    <p><i class="fas fa-envelope"></i> lucasrodrigues4@live.com</p>
                    <p><i class="fas fa-phone"></i> (38)9 9810-0827</p>
                </div>
                <div class="footer-section">
                    <h3>Controle de Ponto Inteligente</h3>
                    <p>Solução integrada para gestão de ponto eletrônico, desenvolvida com tecnologia de ponta para
                        precisão e conformidade regulatória.</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Controle de Ponto Inteligente - Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <div class="scroll-indicator-container">
        <div class="scroll-text">
            <i class="fas fa-hand-point-down"></i>
            Deslize para ver mais
        </div>
        <div class="scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>

    <script>
        // Atualizar o controle do indicador de scroll
        const scrollIndicator = document.querySelector('.scroll-indicator-container');
        
        window.addEventListener('scroll', () => {
            // Ocultar o indicador após rolar 100px
            if (window.scrollY > 100) {
                scrollIndicator.classList.add('hidden');
            } else {
                scrollIndicator.classList.remove('hidden');
            }
        });

        // Ocultar o indicador se a página não tiver scroll
        window.addEventListener('load', () => {
            if (document.documentElement.scrollHeight <= window.innerHeight) {
                scrollIndicator.style.display = 'none';
            }
        });
    </script>
</body>

</html>
