<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <!-- Toastr for popup messages -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            background: linear-gradient(-45deg, #ff9a9e, #fad0c4, #fbc2eb, #a18cd1);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .arrow {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2rem;
            color: #fff;
            animation: float 2s infinite;
            cursor: pointer;
        }

        .arrow:hover {
            transform: translateX(-50%) scale(1.1);
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(-50%, 0);
            }

            50% {
                transform: translate(-50%, 10px);
            }
        }

        /* Fullscreen content section */
        .content-section {
            height: 92vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .how-it-works {
            padding: 50px 20px;
            background-color: #fff;
            text-align: center;
            position: relative;
        }

        .how-it-works h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .how-it-works p {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #555;
            max-width: 800px;
            margin: 0 auto;
        }

        .feature {
            margin-top: 30px;
            display: inline-block;
            text-align: center;
            width: 200px;
        }

        .feature-icon {
            font-size: 3rem;
            color: #4caf50;
        }

        .feature-title {
            font-size: 1.2rem;
            margin-top: 10px;
            font-weight: bold;
        }

        /* Image under the features section */
        .how-it-works img {
            width: 100%;
            height: auto;
            object-fit: cover;
            margin-top: 30px;
            /* Space between the features and the image */
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="bg-white-500 p-4 flex justify-between items-center">
        <div class="flex items-center pl-10">
            <x-application-logo />
            <span class="text-gray text-lg font-semibold text-gray-500 w-11">Project To Do List</span>
        </div>
    </nav>

    <!-- Content Section -->
    <div class="content-section">
        <div class="text-center animate-fadeIn">
            <!-- Typing effect title -->
            <h1 id="typing-text" class="text-5xl font-extrabold mb-6 text-gray-800"></h1>
            <!-- Subheading -->
            <p class="text-lg text-gray-600 mb-8">Plan. Track. Achieve. Simplify your life.</p>
            <div class="space-x-4">
                <a href="{{ route('login') }}"
                    class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition focus:outline-none no-underline"
                    style="text-decoration: none;">Log In</a>
                <a href="{{ route('register') }}"
                    class="px-6 py-3 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition focus:outline-none no-underline"
                    style="text-decoration: none;">Get Started</a>
            </div>
        </div>
        <div class="arrow" onclick="scrollToHowItWorks()">⬇</div>
    </div>

    <!-- How It Works Section -->
    <div id="how-it-works" class="how-it-works">
        <h2>How It Works</h2>
        <p>Our To-Do List Manager is simple to use and highly effective. Here's how you can get started:</p>

        <!-- Feature List -->
        <div class="feature">
            <div class="feature-icon">🗂️</div>
            <div class="feature-title">Make a Project</div>
        </div>
        <div class="feature">
            <div class="feature-icon">📝</div>
            <div class="feature-title">Add Tasks</div>
        </div>
        <div class="feature">
            <div class="feature-icon">✅</div>
            <div class="feature-title">Track Progress</div>
        </div>
        <div class="feature">
            <div class="feature-icon">🏆</div>
            <div class="feature-title">Achieve Goals</div>
        </div>

        <!-- Demo Task Board Section -->
        <div class="mt-24 bg-white rounded-xl shadow-xl p-8 max-w-7xl mx-auto border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-1">Demo Task Board: <span class="text-blue-500">My Awesome Project</span></h2>
                    <p class="text-sm text-gray-500">Quickly create tasks and organize them by status to visualize your workflow.</p>
                </div>
                <button onclick="openDummyModal()" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded shadow">
                    + Create Task
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Column -->
                <div class="bg-gray-50 border border-gray-200 p-4 rounded">
                    <h3 class="text-xl font-semibold mb-3 text-gray-700">Planning</h3>
                    <div id="demo-planning" class="min-h-[120px] space-y-2"></div>
                </div>
                <div class="bg-gray-50 border border-gray-200 p-4 rounded">
                    <h3 class="text-xl font-semibold mb-3 text-gray-700">Doing</h3>
                    <div id="demo-doing" class="min-h-[120px] space-y-2"></div>
                </div>
                <div class="bg-gray-50 border border-gray-200 p-4 rounded">
                    <h3 class="text-xl font-semibold mb-3 text-gray-700">Done</h3>
                    <div id="demo-done" class="min-h-[120px] space-y-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="dummyModal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-80 shadow-lg">
            <h2 class="text-lg font-bold mb-4">Create a Task</h2>
            <input type="text" id="dummyTaskTitle" placeholder="Task title" class="w-full mb-3 px-3 py-2 border rounded" />
            <select id="dummyTaskStatus" class="w-full mb-4 px-3 py-2 border rounded">
                <option value="planning">Planning</option>
                <option value="doing">Doing</option>
                <option value="done">Done</option>
            </select>
            <div class="flex justify-end space-x-2">
                <button onclick="closeDummyModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                <button onclick="createDummyTask()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add</button>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        const dummyModal = document.getElementById("dummyModal");
        const dummyTaskTitle = document.getElementById("dummyTaskTitle");
        const dummyTaskStatus = document.getElementById("dummyTaskStatus");

        function openDummyModal() {
            dummyModal.classList.remove("hidden");
            dummyTaskTitle.value = "";
            dummyTaskStatus.value = "planning";
        }

        function closeDummyModal() {
            dummyModal.classList.add("hidden");
        }

        function createDummyTask() {
            const title = dummyTaskTitle.value.trim();
            const status = dummyTaskStatus.value;

            if (title === "") {
                alert("Please enter a task title");
                return;
            }

            const task = document.createElement("div");
            task.className = "text-gray-800 px-4 py-2 rounded shadow-sm text-sm font-medium cursor-pointer";

            // Color based on status
            switch (status) {
                case "planning":
                    task.classList.add("bg-blue-200");
                    break;
                case "doing":
                    task.classList.add("bg-orange-200");
                    break;
                case "done":
                    task.classList.add("bg-green-200");
                    break;
                default:
                    task.classList.add("bg-gray-100");
            }

            task.innerText = title;
            document.getElementById(`demo-${status}`).appendChild(task);

            toastr.success("Task created successfully");
            closeDummyModal();
        }


        // Enable drag-and-drop between columns
        ['planning', 'doing', 'done'].forEach(id => {
            new Sortable(document.getElementById(`demo-${id}`), {
                group: 'shared',
                animation: 150,
                onAdd: function(evt) {
                    const task = evt.item;

                    // Remove old background classes
                    task.classList.remove("bg-blue-200", "bg-orange-200", "bg-green-200");

                    // Add new color based on the new column
                    if (evt.to.id === "demo-planning") {
                        task.classList.add("bg-blue-200");
                    } else if (evt.to.id === "demo-doing") {
                        task.classList.add("bg-orange-200");
                    } else if (evt.to.id === "demo-done") {
                        task.classList.add("bg-green-200");
                    }
                }
            });
        });
    </script>


    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
        }
        @if(Session::has('success'))
        toastr.success("{{ session('success') }}")
        @endif
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const textElement = document.getElementById("typing-text");
            const text = "Welcome to Your To-Do List Manager";
            let index = 0;

            function typeEffect() {
                if (index < text.length) {
                    textElement.textContent += text[index];
                    index++;
                    const typingSpeed = Math.random() * (80 - 50) + 50;
                    setTimeout(typeEffect, typingSpeed);
                }
            }

            setTimeout(() => {
                typeEffect();
            }, 500);
        });

        function scrollToHowItWorks() {
            const howItWorksSection = document.getElementById("how-it-works");
            howItWorksSection.scrollIntoView({
                behavior: "smooth"
            });
        }
    </script>
</body>

</html>