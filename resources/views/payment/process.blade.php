<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Securing Connection... - MCC IGH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            margin: 0; 
            background: #f8fafc; 
            color: #1e293b; 
        }
        .loader-container { 
            text-align: center; 
            background: white;
            padding: 4rem;
            border-radius: 40px;
            box-shadow: 0 40px 100px -20px rgba(0,0,0,0.05);
            border: 1px solid #eef2f6;
            max-width: 400px;
            width: 80%;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .spinner { 
            width: 64px; 
            height: 64px; 
            border: 6px solid #f1f5f9; 
            border-top: 6px solid var(--primary-color); 
            border-radius: 50%; 
            animation: spin 1s cubic-bezier(0.5, 0, 0.5, 1) infinite; 
            margin: 0 auto 1.5rem; 
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        h2 { margin: 0; font-size: 1.5rem; font-weight: 800; letter-spacing: -1px; }
        p { color: #64748b; margin-top: 1rem; font-weight: 500; }
    </style>
    @include('partials.dynamic-styles')
    <script>
        let isSubmitted = false;
        function submitForm() {
            if (!isSubmitted) {
                isSubmitted = true;
                document.paymentForm.submit();
            }
        }
    </script>
</head>
<body onload="setTimeout(submitForm, 1000);">
    <div class="loader-container">
        <div class="spinner"></div>
        <h2>Establishing Secure Connection</h2>
        <p>Redirecting to PayU payment gateway...</p>
        <button type="button" onclick="submitForm()" style="margin-top: 1rem; padding: 8px 16px; background: #850f0f; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
            Click here if not redirected automatically
        </button>
        <div style="margin-top: 2rem; opacity: 0.6;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 76 26" fill="none" height="18">
                <path d="M10.8 25.1c-6 0-10.8-4.8-10.8-10.8V0h10.8v14.3c0 .5.4.9.9.9h7.3v9.9h-8.2z" fill="#A4C639"/>
                <path d="M40.2 25.1c-6 0-10.8-4.8-10.8-10.8V0h10.8v14.3c0 .5.4.9.9.9h7.3v9.9h-8.2z" fill="#A4C639"/>
                <path d="M72.3 17.5H62.4v7.6H51.6V0h20.7c6 0 10.8 4.8 10.8 10.8 0 6-4.8 10.8-10.8 10.8v-4.1zm-9.9-10v5.3h9.9c.5 0 .9-.4.9-.9V8.4c0-.5-.4-.9-.9-.9h-9.9z" fill="#A4C639"/>
            </svg>
        </div>
    </div>

    <form action="{{ $payu_url }}" method="POST" name="paymentForm" style="display: none;">
        <input type="hidden" name="key" value="{{ $key }}" />
        <input type="hidden" name="hash" value="{{ $hash }}" />
        <input type="hidden" name="txnid" value="{{ $params['txnid'] }}" />
        <input type="hidden" name="amount" value="{{ $params['amount'] }}" />
        <input type="hidden" name="firstname" value="{{ $params['firstname'] }}" />
        <input type="hidden" name="email" value="{{ $params['email'] }}" />
        <input type="hidden" name="phone" value="{{ $params['phone'] }}" />
        <input type="hidden" name="productinfo" value="{{ $params['productinfo'] }}" />
        <input type="hidden" name="surl" value="{{ $params['surl'] }}" />
        <input type="hidden" name="furl" value="{{ $params['furl'] }}" />
        <input type="hidden" name="udf1" value="{{ $params['udf1'] }}" />
        <input type="hidden" name="udf2" value="{{ $params['udf2'] }}" />
    </form>
</body>
</html>
