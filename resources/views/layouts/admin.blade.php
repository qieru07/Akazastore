<!DOCTYPE html>
<html>
<head>
    <title>AkazaStore Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex">

    <!-- Sidebar -->
    <div class="w-64 min-h-screen bg-black text-white p-5">

        <h1 class="text-3xl font-bold mb-10">
            AkazaStore
        </h1>

        <ul class="space-y-4">

            <li>
                <a href="/dashboard"
                   class="block hover:text-yellow-400">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="/games"
                   class="block hover:text-yellow-400">
                    Games
                </a>
            </li>
            <li>
                <a href="/products"
                   class="block hover:text-yellow-400">
                    Products
                </a>
                    <li>
                        <a href="/transactions"
                        class="block hover:text-yellow-400">
                            Transactions
                        </a>
                    </li>
        </ul>
    </div>

   
    <div class="flex-1 p-10">

        @yield('content')

    </div>

</div>

</body>
</html>