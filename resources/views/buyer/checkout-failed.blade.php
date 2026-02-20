<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fef2f2 0%, #ffe4e6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .failed-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(220, 38, 38, 0.1);
            overflow: hidden;
            animation: slideUp 0.6s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .failed-header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .failed-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f87171, #ef4444, #dc2626);
        }
        
        .failed-icon {
            font-size: 60px;
            margin-bottom: 20px;
            display: block;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .failed-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .failed-header p {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .failed-body {
            padding: 40px 30px;
        }
        
        .error-message {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .error-message h3 {
            color: #dc2626;
            font-size: 18px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-message p {
            color: #57534e;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .suggestions {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        
        .suggestions h3 {
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .suggestions ul {
            list-style: none;
            padding-left: 0;
        }
        
        .suggestions li {
            padding: 8px 0;
            color: #475569;
            font-size: 14px;
            display: flex;
            align-items: start;
            gap: 10px;
        }
        
        .suggestions li i {
            color: #3b82f6;
            margin-top: 3px;
        }
        
        .action-buttons {
            display: grid;
            gap: 15px;
        }
        
        .btn {
            padding: 16px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #475569;
            border: 2px solid #e2e8f0;
        }
        
        .btn-secondary:hover {
            border-color: #3b82f6;
            color: #3b82f6;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }
        
        .btn-outline:hover {
            border-color: #94a3b8;
            transform: translateY(-2px);
        }
        
        .contact-info {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }
        
        .contact-info a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
        
        .order-details {
            background: #f0f9ff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #bae6fd;
        }
        
        .order-details h4 {
            color: #0369a1;
            font-size: 16px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #64748b;
            font-size: 14px;
        }
        
        .detail-value {
            color: #1e293b;
            font-weight: 500;
            font-size: 14px;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body>
    <div class="failed-container">
        <!-- Header -->
        <div class="failed-header">
            <i class="fas fa-times-circle failed-icon"></i>
            <h1>Payment Failed</h1>
            <p>We couldn't process your payment</p>
        </div>
        
        <!-- Body -->
        <div class="failed-body">
            <!-- Error Message -->
            @if(session('error'))
            <div class="error-message">
                <h3><i class="fas fa-exclamation-triangle"></i> Payment Error</h3>
                <p>{{ session('error') }}</p>
            </div>
            @endif
            
            <!-- Order Details (if available) -->
            @if(session('order'))
            <div class="order-details">
                <h4><i class="fas fa-receipt"></i> Order Details</h4>
                <div class="detail-item">
                    <span class="detail-label">Order Number:</span>
                    <span class="detail-value">{{ session('order')->order_number }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value">₹{{ number_format(session('order')->total_amount, 2) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ now()->format('d M Y, h:i A') }}</span>
                </div>
            </div>
            @endif
            
            <!-- Suggestions -->
            <div class="suggestions">
                <h3><i class="fas fa-lightbulb"></i> What could be wrong?</h3>
                <ul>
                    <li>
                        <i class="fas fa-credit-card"></i>
                        <span>Insufficient funds in your account</span>
                    </li>
                    <li>
                        <i class="fas fa-ban"></i>
                        <span>Bank or card declined the transaction</span>
                    </li>
                    <li>
                        <i class="fas fa-wifi"></i>
                        <span>Network connectivity issues</span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Payment gateway timeout</span>
                    </li>
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        <span>Security checks by your bank</span>
                    </li>
                </ul>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('buyer.checkout') }}" class="btn btn-primary">
                    <i class="fas fa-redo pulse"></i>
                    Try Payment Again
                </a>
                
                <a href="{{ route('buyer.cart') }}" class="btn btn-secondary">
                    <i class="fas fa-shopping-cart"></i>
                    Back to Cart
                </a>
                
                <a href="{{ route('home') }}" class="btn btn-outline">
                    <i class="fas fa-home"></i>
                    Continue Shopping
                </a>
            </div>
            
            <!-- Contact Information -->
            <div class="contact-info">
                <p>
                    <i class="fas fa-question-circle"></i>
                    Need help? 
                    <a href="{{ route('contact') }}">Contact Support</a> 
                    or call 1800-123-4567
                </p>
                <p style="margin-top: 10px; font-size: 12px;">
                    <i class="fas fa-lock"></i>
                    Your payment information is secure and encrypted
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add some interactive effects
            const container = document.querySelector('.failed-container');
            container.style.opacity = '0';
            
            // Fade in container
            setTimeout(() => {
                container.style.transition = 'opacity 0.6s ease';
                container.style.opacity = '1';
            }, 100);
            
            // Add click effect to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Add ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.7);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        width: ${size}px;
                        height: ${size}px;
                        top: ${y}px;
                        left: ${x}px;
                        pointer-events: none;
                    `;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Add ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Auto-retry suggestion after 30 seconds
            setTimeout(() => {
                const retryBtn = document.querySelector('.btn-primary');
                if (retryBtn) {
                    retryBtn.innerHTML = `
                        <i class="fas fa-bolt"></i>
                        Click here to retry payment
                    `;
                    retryBtn.classList.add('pulse');
                }
            }, 30000);
        });
    </script>
</body>
</html>