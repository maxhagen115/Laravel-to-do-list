<x-app-layout>
    <style>
        .task {
            cursor: pointer;
        }

        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
        }

        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3490dc;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .dummy-task {
            background-color: #e2e8f0;
            color: #4a5568;
            margin-bottom: 0.5rem;
            padding: 0.75rem;
            border-radius: 0.375rem;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .dummy-task:nth-child(2) {
            opacity: 0.9;
        }

        .dummy-task:nth-child(3) {
            opacity: 0.8;
        }

        .dummy-task:nth-child(4) {
            opacity: 0.7;
        }

        .dummy-task:nth-child(5) {
            opacity: 0.6;
        }

        #modal {
            display: none;
            position: fixed;
            z-index: 100;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .modal-content h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .modal-content label {
            display: block;
            margin-bottom: 8px;
        }

        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 4px;
            margin-bottom: 12px;
        }

        .modal-content button {
            padding: 10px 20px;
            background-color: #3490dc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #2779bd;
        }

        button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
    </style>

    <body class="bg-gray-200">
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-4">Project Task Board for <span class="text-blue-500">{{ Str::ucfirst($project->title) }}</span></h1>

            @if ($project->is_done == 'not_done')
                <button id="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                    Create Task
                </button>

                <form action="{{ route('project.markAsDone', $project->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <button id="markAsDoneButton" class="bg-gray-500 text-white px-4 py-2 rounded float-right cursor-not-allowed" disabled>
                        Mark Project as Done
                    </button>
                </form>
                <form id="markAsDoneForm" action="{{ route('project.markAsDone', $project->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                </form>
            @else
            <form action="{{ route('project.markAsDoing', $project->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('PUT')
                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="confirmReopen(this.form)">
                    Reopen Project
                </button>
            </form>
            @endif


            <!-- Modal -->
            <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center">
                <div class="bg-white p-5 rounded shadow-lg w-1/3">
                    <h2 class="text-xl mb-4">Create Task</h2>
                    <form id="taskForm">
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700">Title:</label>
                            <input type="text" id="title" class="w-full px-3 py-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700">Status:</label>
                            <select id="status" class="w-full px-3 py-2 border rounded">
                                <option value="planning">Planning</option>
                                <option value="doing">Doing</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" id="closeModal"
                                class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                            <button type="button" id="createTask"
                                class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Task Columns -->
            <div id="taskColumns" class="flex justify-between space-x-4 hidden">
                <!-- Planning Column -->
                <div class="w-1/3 bg-white p-4 rounded shadow-md">
                    <h2 class="text-2xl font-semibold mb-2">Planning</h2>
                    <div id="planning" class="task-column"></div>
                </div>

                <!-- Doing Column -->
                <div class="w-1/3 bg-white p-4 rounded shadow-md">
                    <h2 class="text-2xl font-semibold mb-2">Doing</h2>
                    <div id="doing" class="task-column"></div>
                </div>

                <!-- Done Column -->
                <div class="w-1/3 bg-white p-4 rounded shadow-md">
                    <h2 class="text-2xl font-semibold mb-2">Done</h2>
                    <div id="done" class="task-column"></div>
                </div>
            </div>
        </div>

        <!-- Loading Spinner Overlay -->
        <div id="loadingOverlay" class="spinner-overlay">
            <div class="loader"></div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.js"></script>
        <script>
        function confirmReopen(form) {
            const confirmation = confirm("Are you sure you want to reopen this project?");
            if (confirmation) {
                form.submit();
            }
        }
        </script>
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectId = {{ $project->id }};
        const loadingOverlay = document.getElementById('loadingOverlay');
        const taskColumns = document.getElementById('taskColumns');
        const openModalButton = document.getElementById('openModal');
        const closeModalButton = document.getElementById('closeModal');
        const createTaskBtn = document.getElementById('createTask');
        const modal = document.getElementById('modal');
        const markAsDoneButton = document.getElementById('markAsDoneButton');

        // Dummy tasks
        const dummyTasks = [
            "This is a dummy task. You can create real tasks!",
            "Add your first task to get started!",
            "Your tasks will appear here!",
            "Create a new task using the button above!",
            "This is a placeholder task. Add your tasks!"
        ];

        // Initial Setup
        showLoadingSpinner();
        fetchTasks().then(() => {
            hideLoadingSpinner();
            updateMarkAsDoneButton(); // Ensure button state is updated after fetching tasks
        }).catch(() => {
            hideLoadingSpinner();
        });

        // Function to fetch tasks and render them
        function fetchTasks() {
            return axios.get(`/projects/${projectId}/tasks`)
                .then(response => {
                    const tasks = response.data;
                    clearTaskColumns();
                    if (tasks.length === 0) {
                        addDummyTasks('planning');
                        addDummyTasks('doing');
                        addDummyTasks('done');
                    } else {
                        tasks.forEach(task => addTaskToColumn(task));
                        removeDummyTasks('planning');
                        removeDummyTasks('doing');
                        removeDummyTasks('done');
                    }
                    taskColumns.classList.remove('hidden');
                    initializeSortable();
                })
                .catch(() => toastr.error('Failed to load tasks'));
        }

        // Clear task columns
        function clearTaskColumns() {
            document.getElementById('planning').innerHTML = '';
            document.getElementById('doing').innerHTML = '';
            document.getElementById('done').innerHTML = '';
        }

        // Show and hide loading spinner
        function showLoadingSpinner() { loadingOverlay.style.display = 'flex'; }
        function hideLoadingSpinner() { loadingOverlay.style.display = 'none'; }

        // Open and close modal functions
        function openModal() { modal.style.display = 'flex'; }
        function closeModal() { modal.style.display = 'none'; document.getElementById('taskForm').reset(); }
        
        // Event listeners for modal
        openModalButton.addEventListener('click', openModal);
        closeModalButton.addEventListener('click', closeModal);

        // Create new task
        createTaskBtn.addEventListener('click', function() {
            const title = document.getElementById('title').value;
            const status = document.getElementById('status').value;
            showLoadingSpinner();
            axios.post(`/projects/${projectId}/tasks`, { title, status })
                .then(response => {
                    toastr.success('Task created successfully');
                    addTaskToColumn(response.data);
                    removeDummyTasks('planning');
                    removeDummyTasks('doing');
                    removeDummyTasks('done');
                    updateMarkAsDoneButton();
                    document.getElementById('taskForm').reset();
                    closeModal();
                })
                .catch(() => toastr.error('Failed to create task'))
                .finally(() => hideLoadingSpinner());
        });

        function updateMarkAsDoneButton() {
            const taskCount = document.querySelectorAll('.task').length; // Counts visible task elements

            if (taskCount > 0) {
                markAsDoneButton.classList.remove('bg-gray-500', 'cursor-not-allowed');
                markAsDoneButton.classList.add('bg-green-500', 'hover:bg-green-600');
                markAsDoneButton.disabled = false;
            } else {
                markAsDoneButton.classList.add('bg-gray-500', 'cursor-not-allowed');
                markAsDoneButton.classList.remove('bg-green-500', 'hover:bg-green-600');
            }
        }

        // Call this function on page load to set the initial button state
        document.addEventListener('DOMContentLoaded', () => {
            updateMarkAsDoneButton(); // Check and set the button state based on existing tasks
        });

        // Function to handle marking the project as done
            function handleMarkAsDone(event) {
                // Prevent the default action (if needed, such as in a form submission)
                event.preventDefault();

                // Show confirmation dialog
                const confirmed = confirm("Are you sure you want to mark this project as done?");
                
                if (confirmed) {
                    // Proceed with marking the project as done if confirmed
                    document.getElementById('markAsDoneForm').submit();
                }
            }

            // Add an event listener to the Mark As Done button
            markAsDoneButton.addEventListener('click', handleMarkAsDone);


        function markProjectAsDone() {
            axios.put(`/project/${projectId}/mark-as-done`)
                .then(response => {
                    if (response.data.success) {
                        toastr.success(response.data.message);
                        // Optionally, redirect or update the UI here if necessary
                        window.location.href = '/dashboard'; // Redirect to the dashboard if needed
                    } else {
                        toastr.error(response.data.message);
                    }
                })
                .catch(error => {
                    // Handle error response if needed
                    if (error.response && error.response.data) {
                        toastr.error(error.response.data.message || 'An error occurred while marking the project as done.');
                    } else {
                        toastr.error('An unexpected error occurred.');
                    }
                });
        }

        // Function to render a new task in the appropriate column
        function addTaskToColumn(task) {
            const taskElement = document.createElement('div');
            taskElement.className = 'task p-2 mb-2 rounded shadow';
            taskElement.innerText = task.title;
            taskElement.dataset.id = task.id;
            taskElement.dataset.status = task.status;

            switch (task.status) {
                case 'planning': taskElement.classList.add('bg-blue-200'); break;
                case 'doing': taskElement.classList.add('bg-orange-200'); break;
                case 'done': taskElement.classList.add('bg-green-200'); break;
                default: taskElement.classList.add('bg-gray-100'); break;
            }
            document.getElementById(task.status).appendChild(taskElement);
        }

        // Function to add and remove dummy tasks
        function addDummyTasks(columnId) {
            const column = document.getElementById(columnId);
            dummyTasks.forEach((title, index) => {
                const dummyTaskElement = document.createElement('div');
                dummyTaskElement.className = 'dummy-task p-2 mb-2 rounded shadow';
                dummyTaskElement.innerText = title;
                dummyTaskElement.style.opacity = 1 - (index * 0.1);
                column.appendChild(dummyTaskElement);
            });
        }

        function removeDummyTasks(columnId) {
            const column = document.getElementById(columnId);
            const dummyTasks = column.querySelectorAll('.dummy-task');
            dummyTasks.forEach(task => task.remove());
        }

        // Initialize sortable for task columns
        function initializeSortable() {
            ['planning', 'doing', 'done'].forEach(status => {
                new Sortable(document.getElementById(status), {
                    group: 'tasks',
                    animation: 150,
                    onEnd: function(evt) {
                        if (evt.item.classList.contains('dummy-task')) return;

                        const newStatus = evt.to.id;
                        const taskId = evt.item.dataset.id;

                        if (evt.item.dataset.status !== newStatus) {
                            updateTaskStatus(taskId, newStatus)
                                .then(() => {
                                    evt.item.dataset.status = newStatus;

                                    // Update the task's color based on the new status
                                    updateTaskColor(evt.item, newStatus);

                                    toastr.success('Task status updated successfully');
                                })
                                .catch(() => toastr.error('Failed to update task status'));
                        }
                    }
                });
            });
        }

        // Function to update the task's color
        function updateTaskColor(taskElement, status) {
            // Remove all status classes
            taskElement.classList.remove('bg-blue-200', 'bg-orange-200', 'bg-green-200');

            // Add the new status class
            switch (status) {
                case 'planning':
                    taskElement.classList.add('bg-blue-200');
                    break;
                case 'doing':
                    taskElement.classList.add('bg-orange-200');
                    break;
                case 'done':
                    taskElement.classList.add('bg-green-200');
                    break;
            }
        }

        function updateTaskStatus(taskId, newStatus) {
            return axios.put(`/projects/${projectId}/tasks/${taskId}`, { status: newStatus });
        }
    });
</script>

    </body>
</x-app-layout>
