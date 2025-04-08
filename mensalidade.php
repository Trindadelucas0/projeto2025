<?php include 'header.php'; ?>
<?php include 'proteger.php'; 
include 'verificar-ativo.php';?>

<div class="container">
    <div class="payment-card">
        <h2 class="payment-title">💳 Renovação de Mensalidade</h2>
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

    /* Container principal */
    .container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
    }

    /* Card de pagamento */
    .payment-card {
        background: var(--card-bg);
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--glass-border);
        backdrop-filter: blur(10px);
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .payment-card::before {
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

    .payment-card:hover::before {
        transform: scaleX(1);
    }

    /* Título e subtítulo */
    .payment-title {
        color: var(--primary-color);
        font-size: 2.2rem;
        margin-bottom: 1rem;
        text-align: center;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .payment-subtitle {
        color: var(--light-text);
        text-align: center;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    /* Exibição do valor */
    .value-display {
        background: rgba(0, 153, 255, 0.1);
        border: 1px solid var(--primary-color);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .value-label {
        display: block;
        color: var(--light-text);
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .value-amount {
        display: block;
        color: var(--primary-color);
        font-size: 2.5rem;
        font-weight: 700;
    }

    /* Formulário de pagamento */
    .payment-form {
        max-width: 500px;
        margin: 0 auto;
    }

    /* Métodos de pagamento */
    .payment-methods {
        margin-bottom: 2rem;
    }

    .methods-title {
        color: var(--text-color);
        font-size: 1.2rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    .methods-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .method-option {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 15px;
        padding: 1.2rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .method-option:hover {
        transform: translateY(-5px);
        border-color: var(--primary-color);
    }

    .method-option.active {
        background: rgba(0, 153, 255, 0.1);
        border-color: var(--primary-color);
    }

    .method-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .method-name {
        color: var(--text-color);
        font-weight: 500;
    }

    /* Botão de pagamento */
    .btn-payment {
        width: 100%;
        padding: 1.2rem;
        background: var(--gradient-primary);
        color: white;
        border: none;
        border-radius: 15px;
        font-size: 1.2rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 1rem;
        position: relative;
        overflow: hidden;
    }

    .btn-payment:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 153, 255, 0.4);
    }

    .btn-payment.pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 153, 255, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 153, 255, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 153, 255, 0);
        }
    }

    /* Badge de segurança */
    .security-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 2rem;
        color: var(--light-text);
        font-size: 0.9rem;
    }

    .security-icon {
        margin-right: 0.5rem;
        font-size: 1.2rem;
    }

    /* Responsividade */
    @media screen and (max-width: 768px) {
        .container {
            margin: 1rem;
            padding: 1rem;
        }

        .payment-card {
            padding: 1.5rem;
        }

        .payment-title {
            font-size: 1.8rem;
        }

        .methods-grid {
            grid-template-columns: 1fr;
        }

        .value-amount {
            font-size: 2rem;
        }
    }
</style>

<script>
    // Adicionar interatividade aos métodos de pagamento
    document.addEventListener('DOMContentLoaded', function() {
        const methodOptions = document.querySelectorAll('.method-option');
        
        methodOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remover classe active de todos os métodos
                methodOptions.forEach(opt => opt.classList.remove('active'));
                
                // Adicionar classe active ao método clicado
                this.classList.add('active');
            });
        });
    });
</script>
