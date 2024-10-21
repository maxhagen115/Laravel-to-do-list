<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white-500 p-4 flex justify-between items-center">
        <div class="flex items-center pl-10">
            <x-application-logo/>
            <span class="text-gray text-lg font-semibold text-gray-500 w-11">Project To Do List</span>
        </div>
        <div class="flex items-center space-x-4 pr-10">
            @auth
                <a href="{{ route('dashboard') }}"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
            @else
                <a href="{{ route('login') }}"
                    class="px-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                    in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endif
            @endauth
        </div>
    </nav>

    <!-- Content Section -->
    <div class="bg-gray-100 flex-1 flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Welcome To Your Project To Do List</h1>
            <p class="text-gray-700">Start managing your tasks efficiently!</p>
        </div>
    </div>

    <div
        class="bg-gray-200 flex items-end justify-center text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
    </div>

</body>

</html>
