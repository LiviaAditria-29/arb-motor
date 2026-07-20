{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Admin — ARB Motor</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem;}
.card{background:#fff;border-radius:1.5rem;box-shadow:0 20px 60px rgba(15,23,42,.1);width:100%;max-width:420px;overflow:hidden;}
.card-header{background:linear-gradient(135deg,#0F172A,#1E293B);padding:2rem 2rem 1.75rem;text-align:center;}
.card-body{padding:2rem;}
.f-label{display:block;font-size:.78rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem;}
.f-input{width:100%;border:1.5px solid #E2E8F0;border-radius:.875rem;padding:.7rem 1rem;font-size:.9rem;outline:none;transition:border-color .2s;background:#fff;font-family:inherit;box-sizing:border-box;}
.f-input:focus{border-color:#F97316;box-shadow:0 0 0 3px rgba(249,115,22,.1);}
.f-error{color:#EF4444;font-size:.75rem;margin-top:.3rem;}
.btn{width:100%;background:#F97316;color:#fff;font-weight:700;font-size:.95rem;border:none;border-radius:.875rem;padding:.875rem;cursor:pointer;transition:all .2s;font-family:inherit;}
.btn:hover{background:#EA6C0A;transform:translateY(-1px);box-shadow:0 8px 20px rgba(249,115,22,.35);}
</style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <div style="width:52px;height:52px;background:#F97316;border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1rem;margin:0 auto 1rem;">ARB</div>
        <h1 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.5rem;color:#fff;margin-bottom:.25rem;">ARB Motor</h1>
        <p style="font-size:.82rem;color:#64748B;">Daftarkan akun admin baru</p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div style="display:flex;flex-direction:column;gap:1rem;">

                {{-- Name --}}
                <div>
                    <label class="f-label" for="name">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           class="f-input" placeholder="Admin ARB" required autofocus autocomplete="name">
                    @error('name')<p class="f-error">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="f-label" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="f-input" placeholder="admin@arb.com" required autocomplete="username">
                    @error('email')<p class="f-error">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="f-label" for="password">Password</label>
                    <div style="position:relative;">
                        <input id="password" type="password" name="password"
                               class="f-input" placeholder="Min. 8 karakter" required autocomplete="new-password"
                               style="padding-right:3rem;">
                        <button type="button" onclick="togglePass('password','t1')" id="t1"
                                style="position:absolute;right:.875rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94A3B8;">👁</button>
                    </div>
                    @error('password')<p class="f-error">{{ $message }}</p>@enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="f-label" for="password_confirmation">Konfirmasi Password</label>
                    <div style="position:relative;">
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="f-input" placeholder="Ulangi password" required autocomplete="new-password"
                               style="padding-right:3rem;">
                        <button type="button" onclick="togglePass('password_confirmation','t2')" id="t2"
                                style="position:absolute;right:.875rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94A3B8;">👁</button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn" style="margin-top:.5rem;">
                    Daftar sebagai Admin
                </button>

            </div>
        </form>

        <div style="text-align:center;margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid #F1F5F9;">
            <a href="{{ route('login') }}" style="font-size:.845rem;color:#F97316;font-weight:600;text-decoration:none;">
                ← Sudah punya akun? Masuk
            </a>
        </div>
    </div>
</div>

<script>
function togglePass(id, btnId) {
    const inp = document.getElementById(id);
    const btn = document.getElementById(btnId);
    if (inp.type === 'password') { inp.type = 'text'; btn.textContent = '🙈'; }
    else { inp.type = 'password'; btn.textContent = '👁'; }
}
</script>
</body>
</html>