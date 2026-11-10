
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reha Management')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800">

    <header class="bg-slate-900 text-white px-6 py-4">
        <h1 class="text-xl font-bold">Reha Management</h1>
        <p class="text-sm text-slate-300">リハビリ管理システム</p>
    </header>

    <div class="flex min-h-screen">

        <!-- サイドメニュー -->
        <aside class="w-56 bg-white border-r border-slate-200 p-5">
            <nav class="space-y-2">

                <a href="{{ route('patients.index') }}"
                   class="block rounded-lg px-4 py-3 font-semibold
                   {{ request()->routeIs('patients.*')
                    ? 'bg-blue-100 text-blue-700'
                   : 'text-slate-700 hover:bg-blue-50 hover:text-blue-600' }}">
                   患者管理
                </a>

                <a href="{{ route('patients.index') }}"
                   class="block rounded-lg px-4 py-3 font-semibold
                   {{ request()->routeIs('rehabilitation_records.*')
                   ? 'bg-blue-100 text-blue-700'
                   : 'text-slate-700 hover:bg-blue-50 hover:text-blue-600' }}">
                   リハビリ実施記録
                </a>

                <a href="{{ route('patients.index') }}"
                   class="block rounded-lg px-4 py-3 font-semibold
                   {{ request()->routeIs('evaluations.*')
                   ? 'bg-blue-100 text-blue-700'
                   : 'text-slate-700 hover:bg-blue-50 hover:text-blue-600' }}">
                   機能評価
                 </a>

            </nav>
        </aside>

        <!-- メインコンテンツ -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>

    </div>

</body>
</html>
