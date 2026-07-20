{{-- resources/views/auth/login.blade.php
     Override halaman login bawaan Laravel Breeze agar sesuai brand ARB Motor --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin — ARB Motor</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem;}
.login-card{background:#fff;border-radius:1.5rem;box-shadow:0 20px 60px rgba(15,23,42,.1);width:100%;max-width:420px;overflow:hidden;}
.login-header{background:linear-gradient(135deg,#0F172A,#1E293B);padding:2.5rem 2rem;text-align:center;}
.login-body{padding:2rem;}
.f-label{display:block;font-size:.78rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem;}
.f-input{width:100%;border:1.5px solid #E2E8F0;border-radius:.875rem;padding:.7rem 1rem;font-size:.9rem;outline:none;transition:border-color .2s;background:#fff;font-family:inherit;}
.f-input:focus{border-color:#F97316;box-shadow:0 0 0 3px rgba(249,115,22,.1);}
.f-error{color:#EF4444;font-size:.75rem;margin-top:.3rem;}
.btn-login{width:100%;background:#F97316;color:#fff;font-weight:700;font-size:.95rem;border:none;border-radius:.875rem;padding:.875rem;cursor:pointer;transition:all .2s;font-family:inherit;}
.btn-login:hover{background:#EA6C0A;transform:translateY(-1px);box-shadow:0 8px 20px rgba(249,115,22,.35);}
</style>
</head>
<body>

<div class="login-card">
    {{-- Header --}}
    <div class="login-header">
        <div style="width:52px;height:52px;background:#F97316;border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1rem;margin:0 auto 1rem;">ARB</div>
        <h1 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.5rem;color:#fff;margin-bottom:.25rem;">ARB Motor</h1>
        <p style="font-size:.82rem;color:#64748B;">Admin Panel — Masuk untuk melanjutkan</p>
    </div>

    {{-- Body --}}
    <div class="login-body">

        {{-- Session Error --}}
        @if (session('status'))
        <div style="background:#DCFCE7;border:1px solid #86EFAC;border-radius:.75rem;padding:.875rem;margin-bottom:1.25rem;font-size:.845rem;color:#15803D;">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="display:flex;flex-direction:column;gap:1rem;">
                {{-- Email --}}
                <div>
                    <label class="f-label" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email','admin@arb.com') }}"
                           class="f-input @error('email') border-red-400 @enderror"
                           placeholder="admin@arb.com" required autofocus autocomplete="username">
                    @error('email')<p class="f-error">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="f-label" for="password">Password</label>
                    <div style="position:relative;">
                        <input id="password" type="password" name="password"
                               class="f-input @error('password') border-red-400 @enderror"
                               placeholder="••••••••" required autocomplete="current-password"
                               style="padding-right:3rem;">
                        <button type="button" onclick="togglePass()" style="position:absolute;right:.875rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94A3B8;font-size:.9rem;" id="toggle-pass">👁</button>
                    </div>
                    @error('password')<p class="f-error">{{ $message }}</p>@enderror
                </div>

                {{-- Remember --}}
                <label style="display:flex;align-items:center;gap:.625rem;cursor:pointer;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:#F97316;width:16px;height:16px;border-radius:4px;">
                    <span style="font-size:.845rem;color:#64748B;">Ingat saya</span>
                </label>

                {{-- Submit --}}
                <button type="submit" class="btn-login" style="margin-top:.5rem;">
                    Masuk ke Dashboard
                </button>
            </div>
        </form>

        {{-- Footer link --}}
        {{-- Footer link --}}
        <div style="text-align:center;margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid #F1F5F9;">
            <a href="{{ route('register') }}" style="font-size:.845rem;color:#F97316;font-weight:600;text-decoration:none;">
                Register Admin Baru
            </a>
            
        </div>
    </div>
</div>

<script>
function togglePass(){
    const inp=document.getElementById('password');
    const btn=document.getElementById('toggle-pass');
    if(inp.type==='password'){inp.type='text';btn.textContent='🙈';}
    else{inp.type='password';btn.textContent='👁';}
}
</script>
</body>
</html>
