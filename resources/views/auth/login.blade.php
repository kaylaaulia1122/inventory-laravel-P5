<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - INVENTORYPRO Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-800/80 backdrop-blur-xl border border-slate-700/80 p-8 rounded-3xl shadow-2xl space-y-6">
        <!-- Logo -->
        <div class="text-center">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-500/30 mb-3">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">INVENTORYPRO</h1>
            <p class="text-xs text-slate-400 mt-1">Sistem Manajemen Persediaan Barang &bull; Laravel P5</p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs uppercase font-bold text-slate-300 mb-1.5">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-user text-xs"></i>
                    </span>
                    <input type="text" name="username" id="username" value="{{ old('username', 'admin') }}" required class="w-full pl-9 pr-4 py-2.5 bg-slate-900/60 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-slate-300 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </span>
                    <input type="password" name="password" id="password" value="admin123" required class="w-full pl-9 pr-4 py-2.5 bg-slate-900/60 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Sistem
            </button>
        </form>

        <!-- Quick Demo Credentials Box -->
        <div class="p-4 rounded-2xl bg-slate-900/50 border border-slate-700/50 text-xs space-y-2">
            <span class="font-bold text-slate-400 block uppercase tracking-wider text-[10px]">Pilih Akun Demo:</span>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="setCreds('admin', 'admin123')" class="p-2 rounded-xl bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 font-semibold text-left transition">
                    <span class="block font-bold">Admin Utama</span>
                    <span class="text-[10px] text-slate-400">admin / admin123</span>
                </button>
                <button type="button" onclick="setCreds('staff', 'staff123')" class="p-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 font-semibold text-left transition">
                    <span class="block font-bold">Staff Gudang</span>
                    <span class="text-[10px] text-slate-400">staff / staff123</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        function setCreds(user, pass) {
            document.getElementById('username').value = user;
            document.getElementById('password').value = pass;
        }
    </script>
</body>
</html>
