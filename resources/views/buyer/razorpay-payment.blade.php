<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment - {{ config('app.name') }}</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .payment-container {
            max-width: 450px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
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
        
        .payment-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .payment-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #3b82f6, #8b5cf6);
        }
        
        .lock-icon {
            font-size: 50px;
            margin-bottom: 15px;
            display: block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .payment-header h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .order-number {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .payment-body {
            padding: 30px;
        }
        
        .amount-display {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
            border: 2px solid #e0f2fe;
        }
        
        .amount-label {
            display: block;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .amount-value {
            font-size: 36px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .currency {
            font-size: 20px;
            vertical-align: top;
        }
        
        .payment-methods {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }
        
        .payment-method-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .payment-method-item:hover {
            border-color: #4f46e5;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }
        
        .payment-method-icon {
            width: 40px;
            height: 40px;
            background: #4f46e5;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
        }
        
        .payment-method-text h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }
        
        .payment-method-text p {
            font-size: 12px;
            color: #64748b;
        }
        
        #pay-button {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        #pay-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }
        
        #pay-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 5px;
        }
        
        .powered-by {
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            margin-bottom: 20px;
        }
        
        .powered-by span {
            font-weight: 600;
            color: #4f46e5;
        }
        
        #error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            display: none;
            border-left: 4px solid #dc2626;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .cancel-section {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .cancel-link {
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s ease;
        }
        
        .cancel-link:hover {
            color: #ef4444;
        }
        
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .payment-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .info-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        
        .info-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 5px;
            display: block;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
        }
        
        .razorpay-logo {
            height: 20px;
            vertical-align: middle;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <!-- Header -->
        <div class="payment-header">
            <i class="fas fa-lock lock-icon"></i>
            <h2>Complete Secure Payment</h2>
            <p class="order-number">Order #{{ $order->order_number }}</p>
        </div>
        
        <!-- Body -->
        <div class="payment-body">
            <!-- Amount Display -->
            <div class="amount-display">
                <span class="amount-label">Total Amount to Pay</span>
                <div class="amount-value">
                    <span class="currency">₹</span>{{ number_format($order->total_amount, 2) }}
                </div>
                <small class="text-muted">Inclusive of all taxes</small>
            </div>
            
            <!-- Order Information -->
            <div class="payment-info-grid">
                <div class="info-item">
                    <span class="info-label">Order Date</span>
                    <span class="info-value">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Payment Method</span>
                    <span class="info-value">Online Payment</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Shipping To</span>
                    <span class="info-value">{{ $order->shipping_city }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Items</span>
                    <span class="info-value">{{ $order->items->count() }} items</span>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="payment-methods">
                <div class="payment-method-item">
                    <div class="payment-method-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="payment-method-text">
                        <h4>Credit & Debit Cards</h4>
                        <p>Visa, Mastercard, RuPay, and more</p>
                    </div>
                </div>
                <div class="payment-method-item">
                    <div class="payment-method-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="payment-method-text">
                        <h4>UPI & Mobile Wallets</h4>
                        <p>Google Pay, PhonePe, Paytm, and more</p>
                    </div>
                </div>
                <div class="payment-method-item">
                    <div class="payment-method-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="payment-method-text">
                        <h4>Net Banking</h4>
                        <p>All major Indian banks</p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Form -->
            <form id="razorpay-form" action="{{ route('buyer.razorpay.callback') }}" method="POST">
                @csrf
                <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="{{ $razorpayOrderId }}">
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                
                <button type="button" id="pay-button">
                    <i class="fas fa-lock"></i>
                    Pay Securely ₹{{ number_format($order->total_amount, 2) }}
                </button>
            </form>
            
            <!-- Security Info -->
            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <span>100% Secure | SSL Encrypted</span>
            </div>
            
            <div class="powered-by">
                Secured by <span>Razorpay</span>
                <img src="https://razorpay.com/assets/razorpay-glyph.svg" alt="Razorpay" class="razorpay-logo">
            </div>
            
            <!-- Error Message -->
            <div id="error-message"></div>
            
            <!-- Cancel Section -->
            <div class="cancel-section">
                <a href="{{ route('buyer.checkout.cancel') }}" class="cancel-link">
                    <i class="fas fa-times"></i>
                    Cancel Payment
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            const errorMessage = document.getElementById('error-message');
            const razorpayForm = document.getElementById('razorpay-form');
            
            // Initialize payment
            initPayment();
            
            // Add click handler for manual retry
            payButton.addEventListener('click', initPayment);
            
            function initPayment() {
                // Reset and disable button
                payButton.disabled = true;
                payButton.innerHTML = '<i class="fas fa-spinner loading-spinner"></i> Initializing Secure Payment...';
                errorMessage.style.display = 'none';
                
                const options = {
                    key: "{{ $razorpayKey }}",
                    amount: {{ $amount }}, // Amount in paise
                    currency: "INR",
                    name: "{{ config('app.name') }}",
                    description: "Payment for Order #{{ $order->order_number }}",
                    order_id: "{{ $razorpayOrderId }}",
                    handler: function(response) {
                        // Set payment details in form
                        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                        document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                        document.getElementById('razorpay_signature').value = response.razorpay_signature;
                        
                        // Update button text
                        payButton.innerHTML = '<i class="fas fa-spinner loading-spinner"></i> Verifying Payment...';
                        
                        // Add processing animation to container
                        document.querySelector('.payment-container').style.opacity = '0.8';
                        
                        // Submit form for verification
                        setTimeout(() => {
                            razorpayForm.submit();
                        }, 1000);
                    },
                    prefill: {
                        name: "{{ $user->name }}",
                        email: "{{ $user->email }}",
                        contact: "{{ $user->phone }}"
                    },
                    notes: {
                        order_id: "{{ $order->id }}",
                        order_number: "{{ $order->order_number }}",
                        user_id: "{{ $user->id }}"
                    },
                    theme: {
                        color: "#4f46e5"
                    },
                    modal: {
                        ondismiss: function() {
                            // User closed the modal
                            payButton.disabled = false;
                            payButton.innerHTML = `
                                <i class="fas fa-lock"></i>
                                Try Again ₹{{ number_format($order->total_amount, 2) }}
                            `;
                            
                            // Show error message
                            errorMessage.innerHTML = `
                                <strong><i class="fas fa-exclamation-triangle"></i> Payment Cancelled</strong>
                                <p style="margin-top: 8px; margin-bottom: 0; font-size: 13px;">
                                    You closed the payment window. Click the button above to try again.
                                </p>
                            `;
                            errorMessage.style.display = 'block';
                            
                            // Scroll to error message
                            errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                };
                
                try {
                    const rzp = new Razorpay(options);
                    
                    // Handle payment failure
                    rzp.on('payment.failed', function(response) {
                        payButton.disabled = false;
                        payButton.innerHTML = `
                            <i class="fas fa-redo"></i>
                            Try Again ₹{{ number_format($order->total_amount, 2) }}
                        `;
                        
                        let errorDescription = response.error.description || 'Payment could not be processed.';
                        
                        errorMessage.innerHTML = `
                            <strong><i class="fas fa-exclamation-circle"></i> Payment Failed</strong>
                            <p style="margin-top: 8px; margin-bottom: 5px; font-size: 13px;">
                                ${errorDescription}
                            </p>
                            <small style="font-size: 12px; opacity: 0.8;">
                                Error Code: ${response.error.code}
                            </small>
                        `;
                        errorMessage.style.display = 'block';
                        
                        // Scroll to error message
                        errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                    
                    // Open Razorpay modal
                    rzp.open();
                    
                } catch (error) {
                    payButton.disabled = false;
                    payButton.innerHTML = `
                        <i class="fas fa-lock"></i>
                        Pay Securely ₹{{ number_format($order->total_amount, 2) }}
                    `;
                    
                    errorMessage.innerHTML = `
                        <strong><i class="fas fa-exclamation-circle"></i> Payment Error</strong>
                        <p style="margin-top: 8px; margin-bottom: 0; font-size: 13px;">
                            Unable to initialize payment. Please refresh the page and try again.
                        </p>
                        <small style="font-size: 12px; opacity: 0.8; margin-top: 5px; display: block;">
                            ${error.message}
                        </small>
                    `;
                    errorMessage.style.display = 'block';
                    
                    console.error('Razorpay Initialization Error:', error);
                }
            }
            
            // Add smooth animations
            const container = document.querySelector('.payment-container');
            container.style.opacity = '0';
            
            setTimeout(() => {
                container.style.transition = 'opacity 0.6s ease';
                container.style.opacity = '1';
            }, 100);
            
            // Add floating animation to amount display
            const amountDisplay = document.querySelector('.amount-display');
            setInterval(() => {
                amountDisplay.style.transform = 'translateY(-2px)';
                setTimeout(() => {
                    amountDisplay.style.transform = 'translateY(0)';
                }, 300);
            }, 3000);
        });
    </script>
</body>
</html>