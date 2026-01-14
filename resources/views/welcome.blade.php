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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 20px;
            border-radius: 15px;
            cursor: pointer;
        }

        .feature:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .feature:hover .feature-icon {
            transform: rotate(360deg) scale(1.2);
            transition: transform 0.6s ease;
        }

        .feature:hover .feature-title {
            color: white;
        }

        .feature-icon {
            font-size: 3rem;
            color: #4caf50;
            transition: transform 0.6s ease;
        }

        .feature-title {
            font-size: 1.2rem;
            margin-top: 10px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        /* Image under the features section */
        .how-it-works img {
            width: 100%;
            height: auto;
            object-fit: cover;
            margin-top: 30px;
            /* Space between the features and the image */
        }

        /* Drag indicator styles */
        .drag-handle {
            opacity: 0;
            font-size: 1.5rem;
            cursor: grab;
            transition: opacity 0.2s, color 0.2s;
            color: #4b5563;
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
            background: rgba(255, 255, 255, 0.9);
            padding: 8px 12px;
            border-radius: 6px;
            pointer-events: none;
        }

        .drag-handle:hover {
            color: #374151;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .task-item {
            display: flex;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
            min-height: 80px;
            margin-bottom: 10px;
            position: relative;
            padding: 24px 28px;
        }

        .task-item.bg-blue-200 {
            border-left-color: #3b82f6;
            background-color: #dbeafe !important;
        }

        .task-item.bg-orange-200 {
            border-left-color: #f97316;
            background-color: #fed7aa !important;
        }

        .task-item.bg-green-200 {
            border-left-color: #10b981;
            background-color: #d1fae5 !important;
        }

        .task-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            border-color: rgba(0, 0, 0, 0.2);
        }

        .task-item:hover .drag-handle {
            opacity: 0.9;
            background: rgba(255, 255, 255, 0.95);
            pointer-events: auto;
        }

        @keyframes taskPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }

        .task-created {
            animation: taskPulse 0.5s ease-in-out;
        }

        .drag-hint {
            font-size: 0.7rem;
            color: #6b7280;
            margin-top: 20px;
            opacity: 0;
            transition: opacity 0.3s;
            text-align: center;
            width: 100%;
            z-index: 15;
            padding-top: 12px;
            padding-bottom: 4px;
        }

        .task-item:hover .drag-hint {
            opacity: 1;
        }

        /* Subtle text effect with typing cursor */
        #typing-text {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .typing-cursor {
            display: inline-block;
            width: 3px;
            height: 1em;
            background-color: #4a5568;
            margin-left: 2px;
            animation: blink 1s infinite;
            vertical-align: baseline;
        }

        @keyframes blink {
            0%, 50% {
                opacity: 1;
            }
            51%, 100% {
                opacity: 0;
            }
        }

        /* Button animations */
        .content-section a {
            transition: all 0.3s ease;
        }

        .content-section a:hover {
            transform: scale(1.05);
        }

        /* Stats section */
        .stats-section {
            background: rgba(255, 255, 255, 0.1);
            padding: 60px 20px;
            backdrop-filter: blur(10px);
        }

        .stat-item {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .stat-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .stat-number {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        /* Demo board animation */
        .demo-board {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }

        .demo-board.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Particles canvas */
        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        body > * {
            position: relative;
            z-index: 2;
        }

        /* Fade-in animation classes */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Initially hide elements that should fade in */
        nav, .content-section > div, .arrow, .stats-section, .how-it-works, .feature {
            opacity: 0;
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

    <!-- Statistics Section -->
    <div class="stats-section">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="stat-item">
                <div class="stat-number text-5xl font-bold text-white mb-2" data-target="1000">0</div>
                <div class="stat-label text-xl text-white">Tasks Completed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number text-5xl font-bold text-white mb-2" data-target="500">0</div>
                <div class="stat-label text-xl text-white">Active Projects</div>
            </div>
            <div class="stat-item">
                <div class="stat-number text-5xl font-bold text-white mb-2" data-target="95">0</div>
                <div class="stat-label text-xl text-white">Success Rate</div>
            </div>
        </div>
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
        <div class="demo-board mt-24 mb-36 bg-white rounded-xl shadow-xl p-8 max-w-7xl mx-auto border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-1">Demo Task Board: <span class="text-blue-500">My Awesome Project</span></h2>
                    <p class="text-sm text-gray-500">Quickly create tasks and organize them by status to visualize your workflow.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button id="finishProjectBtn" onclick="finishProject()" 
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded shadow hidden">
                        Finish Project
                    </button>
                    <button onclick="openDummyModal()" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded shadow">
                        + Create Task
                    </button>
                </div>
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

    <!-- Create Task Modal -->
    <div id="dummyModal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-80 shadow-lg">
            <h2 class="text-lg font-bold mb-4">Create a Task</h2>
            <input type="text" id="dummyTaskTitle" placeholder="E.g. Setup environment" class="w-full mb-3 px-3 py-2 border rounded" />
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

    <!-- Finish Project Confirmation Modal -->
    <div id="finishProjectModal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50" onclick="if(event.target === this) closeFinishProjectModal()">
        <div class="bg-white rounded-lg p-6 w-80 shadow-lg">
            <h2 class="text-lg font-bold mb-4">Finish Project</h2>
            <p class="text-gray-700 mb-6">Are you sure you want to finish this project? All tasks will be cleared.</p>
            <div class="flex justify-end space-x-2">
                <button onclick="closeFinishProjectModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                <button onclick="confirmFinishProject()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">OK</button>
            </div>
        </div>
    </div>

    <!-- Particles Canvas -->
    <canvas id="particles-canvas"></canvas>

    <!-- Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        const dummyModal = document.getElementById("dummyModal");
        const dummyTaskTitle = document.getElementById("dummyTaskTitle");
        const dummyTaskStatus = document.getElementById("dummyTaskStatus");
        const finishProjectBtn = document.getElementById("finishProjectBtn");
        const finishProjectModal = document.getElementById("finishProjectModal");

        // Function to check if there are any dummy tasks and show/hide the Finish Project button
        function checkForDummyTasks() {
            const planningTasks = document.getElementById("demo-planning").children.length;
            const doingTasks = document.getElementById("demo-doing").children.length;
            const doneTasks = document.getElementById("demo-done").children.length;
            
            if (planningTasks > 0 || doingTasks > 0 || doneTasks > 0) {
                finishProjectBtn.classList.remove("hidden");
            } else {
                finishProjectBtn.classList.add("hidden");
            }
        }

        // Function to open finish project confirmation modal
        function finishProject() {
            finishProjectModal.classList.remove("hidden");
        }

        // Function to close finish project confirmation modal
        function closeFinishProjectModal() {
            finishProjectModal.classList.add("hidden");
        }

        // Function to confirm and finish the project
        function confirmFinishProject() {
            // Close the modal
            closeFinishProjectModal();
            
            // Disable button and show loading state
            finishProjectBtn.disabled = true;
            finishProjectBtn.innerText = "Finishing...";

            // Clear all tasks from all sections
            document.getElementById("demo-planning").innerHTML = "";
            document.getElementById("demo-doing").innerHTML = "";
            document.getElementById("demo-done").innerHTML = "";

            // Show confetti animation
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });

            // Show success toastr message
            toastr.success("Project successfully ended");

            // Hide the button after clearing
            finishProjectBtn.classList.add("hidden");
            finishProjectBtn.disabled = false;
            finishProjectBtn.innerText = "Finish Project";
        }

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
            task.className = "task-item text-gray-800 rounded-lg shadow-md text-base font-semibold cursor-move bg-white";
            
            // Create drag handle
            const dragHandle = document.createElement("span");
            dragHandle.className = "drag-handle";
            dragHandle.innerHTML = "⋮⋮";
            dragHandle.title = "Drag to move";
            
            // Create task content wrapper
            const taskContent = document.createElement("div");
            taskContent.style.width = "100%";
            taskContent.style.textAlign = "center";
            taskContent.style.padding = "8px 60px";
            taskContent.style.position = "relative";
            taskContent.style.zIndex = "10";
            taskContent.style.pointerEvents = "auto";
            taskContent.style.flexShrink = "0";
            taskContent.style.marginTop = "-12px";
            taskContent.style.marginBottom = "16px";
            taskContent.style.paddingBottom = "8px";
            taskContent.textContent = title;
            
            // Create drag hint
            const dragHint = document.createElement("div");
            dragHint.className = "drag-hint";
            dragHint.textContent = "💡 Drag to move between sections";
            
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

            // Add pulse animation class
            task.classList.add("task-created");
            
            // Assemble task structure
            const taskWrapper = document.createElement("div");
            taskWrapper.style.width = "100%";
            taskWrapper.style.display = "flex";
            taskWrapper.style.flexDirection = "column";
            taskWrapper.style.alignItems = "center";
            taskWrapper.style.justifyContent = "flex-start";
            taskWrapper.style.position = "relative";
            taskWrapper.style.minHeight = "100%";
            taskWrapper.style.paddingTop = "8px";
            taskWrapper.style.paddingBottom = "8px";
            taskWrapper.appendChild(taskContent);
            taskWrapper.appendChild(dragHint);
            
            task.appendChild(dragHandle);
            task.appendChild(taskWrapper);
            
            document.getElementById(`demo-${status}`).appendChild(task);
            
            // Remove animation class after animation completes
            setTimeout(() => {
                task.classList.remove("task-created");
            }, 500);

            toastr.success("Task created successfully");
            closeDummyModal();
            
            // Check if Finish Project button should be shown
            checkForDummyTasks();
        }


        // Enable drag-and-drop between columns
        ['planning', 'doing', 'done'].forEach(id => {
            new Sortable(document.getElementById(`demo-${id}`), {
                group: 'shared',
                animation: 150,
                handle: '.drag-handle', // Only allow dragging from the handle
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
                    
                    // Ensure task has the proper structure if it doesn't already
                    if (!task.querySelector('.drag-handle')) {
                        const dragHandle = document.createElement("span");
                        dragHandle.className = "drag-handle";
                        dragHandle.innerHTML = "⋮⋮";
                        dragHandle.title = "Drag to move";
                        
                        const taskContent = task.textContent || task.innerText;
                        task.innerHTML = '';
                        task.className = "task-item text-gray-800 px-5 py-4 rounded-lg shadow-md text-base font-semibold cursor-move bg-white " + task.className;
                        
                        const taskWrapper = document.createElement("div");
                        taskWrapper.style.width = "100%";
                        const contentDiv = document.createElement("div");
                        contentDiv.style.flex = "1";
                        contentDiv.textContent = taskContent;
                        taskWrapper.appendChild(contentDiv);
                        
                        const dragHint = document.createElement("div");
                        dragHint.className = "drag-hint";
                        dragHint.textContent = "💡 Drag to move between sections";
                        taskWrapper.appendChild(dragHint);
                        
                        task.appendChild(dragHandle);
                        task.appendChild(taskWrapper);
                    }
                    
                    // Check if Finish Project button should be shown after drag
                    checkForDummyTasks();
                }
            });
        });

        // Check for dummy tasks on page load
        document.addEventListener("DOMContentLoaded", () => {
            checkForDummyTasks();
        });

        // Floating particles animation
        const canvas = document.getElementById('particles-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            
            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);
            
            const particles = [];
            const particleCount = 50;
            
            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 3 + 1;
                    this.speedX = Math.random() * 2 - 1;
                    this.speedY = Math.random() * 2 - 1;
                    this.opacity = Math.random() * 0.5 + 0.2;
                }
                
                update() {
                    this.x += this.speedX;
                    this.y += this.speedY;
                    
                    if (this.x > canvas.width) this.x = 0;
                    if (this.x < 0) this.x = canvas.width;
                    if (this.y > canvas.height) this.y = 0;
                    if (this.y < 0) this.y = canvas.height;
                }
                
                draw() {
                    ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }
            
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
            
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach(particle => {
                    particle.update();
                    particle.draw();
                });
                requestAnimationFrame(animate);
            }
            animate();
        }

        // Animated counter
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + (target === 95 ? '%' : '+');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + (target === 95 ? '%' : '+');
                }
            }, 30);
        }
        
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    const target = parseInt(entry.target.querySelector('.stat-number').dataset.target);
                    animateCounter(entry.target.querySelector('.stat-number'), target);
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        document.querySelectorAll('.stat-item').forEach(stat => {
            statsObserver.observe(stat);
        });

        // Demo board scroll animation with fade-in
        const boardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Also apply smooth fade-in
                    entry.target.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(30px)';
                    requestAnimationFrame(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    });
                    boardObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });
        
        document.querySelectorAll('.demo-board').forEach(board => {
            boardObserver.observe(board);
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
        // Smooth fade-in effect for all page elements
        document.addEventListener("DOMContentLoaded", () => {
            // Function to fade in elements
            function fadeInElement(element, delay = 0) {
                setTimeout(() => {
                    element.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(20px)';
                    
                    // Force reflow
                    element.offsetHeight;
                    
                    // Fade in
                    requestAnimationFrame(() => {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    });
                }, delay);
            }

            // Fade in navbar first
            const nav = document.querySelector('nav');
            fadeInElement(nav, 100);

            // Fade in content section elements
            const contentSection = document.querySelector('.content-section > div');
            fadeInElement(contentSection, 200);

            // Fade in arrow
            const arrow = document.querySelector('.arrow');
            fadeInElement(arrow, 600);

            // Fade in stats section when it comes into view
            const statsSection = document.querySelector('.stats-section');
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        fadeInElement(entry.target, 0);
                        statsObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            if (statsSection) statsObserver.observe(statsSection);

            // Fade in how-it-works section
            const howItWorks = document.getElementById('how-it-works');
            const howItWorksObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        fadeInElement(entry.target, 0);
                        howItWorksObserver.unobserve(entry.target);
                        
                        // Fade in features with slight delay
                        const features = entry.target.querySelectorAll('.feature');
                        features.forEach((feature, index) => {
                            fadeInElement(feature, index * 100);
                        });
                    }
                });
            }, { threshold: 0.1 });
            if (howItWorks) howItWorksObserver.observe(howItWorks);

            // Typing effect
            const textElement = document.getElementById("typing-text");
            const text = "Welcome to Your To-Do List Manager";
            let index = 0;

            // Create cursor element
            const cursor = document.createElement("span");
            cursor.className = "typing-cursor";
            textElement.appendChild(cursor);

            function typeEffect() {
                if (index < text.length) {
                    // Remove cursor, add character, then add cursor back
                    cursor.remove();
                    textElement.textContent += text[index];
                    textElement.appendChild(cursor);
                    index++;
                    const typingSpeed = Math.random() * (80 - 50) + 50;
                    setTimeout(typeEffect, typingSpeed);
                } else {
                    // Keep cursor blinking after typing is complete
                    cursor.style.animation = "blink 1s infinite";
                }
            }

            setTimeout(() => {
                typeEffect();
            }, 800);
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