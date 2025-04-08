<?php include 'header.php'; ?>
<?php include 'proteger.php'; 
include 'verificar-ativo.php';?>
<<<<<<< HEAD

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renovação de Mensalidade</title>
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
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .container::before {
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

        .container:hover::before {
            transform: scaleX(1);
        }

        /* Título da página */
        h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1rem;
            text-align: center;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Descrição */
        p {
            color: var(--light-text);
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        /* Formulário */
        form {
            max-width: 400px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            border: 1px solid var(--glass-border);
            background: var(--glass-bg);
            color: var(--text-color);
            font-size: 1.1rem;
            text-align: center;
            transition: var(--transition);
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 153, 255, 0.2);
        }

        input[readonly] {
            background: rgba(0, 153, 255, 0.1);
            border-color: var(--primary-color);
            font-weight: 500;
        }

        /* Botão */
        button {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 1rem;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
        }

        /* Responsividade */
        @media screen and (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 1.5rem;
            }

            h2 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
            }

            input {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>💳 Renovação de Mensalidade</h2>
        <p>Você pode efetuar sua mensalidade via Pix, cartão ou transferência.</p>
        <form method="POST" action="mensalidade.php">
            <div class="form-group">
                <label for="valor">Valor:</label>
                <input type="text" id="valor" name="valor" value="99.90" readonly>
            </div>
            <button type="submit">Pagar agora</button>
        </form>
    </div>
</body>
</html>
=======
<<div class="payment-container">
  <div class="payment-card">
    <h2 class="payment-title">Renovação de Mensalidade</h2>
    <p class="payment-subtitle">Escolha a forma de pagamento para renovar seu plano</p>
    
    <div class="value-display">
      <span class="value-label">Valor a pagar:</span>
      <span class="value-amount">R$ 19,90</span>
    </div>
    
    <form method="POST" action="mensalidade.php" class="payment-form">
      <input type="hidden" name="valor" value="19.90">
      
      <div class="payment-methods">
        <h4 class="methods-title">Método de Pagamento</h4>
        <div class="methods-grid">
          <div class="method-option active">
            <div class="method-icon">🧾</div>
            <span class="method-name">Pix</span>
          </div>
          <div class="method-option">
            <div class="method-icon">💳</div>
            <span class="method-name">Cartão</span>
          </div>
          <div class="method-option">
            <div class="method-icon">🏦</div>
            <span class="method-name">Transferência</span>
          </div>
        </div>
      </div>
      
      <button type="submit" class="btn-payment pulse">
        <span>Pagar agora</span>
      </button>
    </form>
    
    <div class="security-badge">
      <span class="security-icon">🔒</span>
      <span>Pagamento seguro e criptografado</span>
    </div>
  </div>
</div>
>>>>>>> 6a4371b505c2969eaa843912487b25d6dab79c77
