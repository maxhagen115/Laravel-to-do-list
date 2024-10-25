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
    </style>

    <body class="bg-gray-200">
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-4">Project Task Board for <span class="text-blue-500">{{ Str::ucfirst($project->title) }}</span></h1>


            
            <!-- Button to open the modal -->
            <button id="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                Create Task
            </button>
            <!-- <form action="{{ route('project.markAsDone', $project->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('PUT')
                <button type="submit" class="float-right bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded shadow" onclick="return confirm('Are you sure you want to mark this project as done?')">
                    Mark project as Done
                </button>
            </form> -->

            @if ($project->tasks->count() > 0)
                <form action="{{ route('project.markAsDone', $project->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit" id="markAsDoneButton" class="float-right bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded shadow" onclick="return confirm('Are you sure you want to mark this project as done?')">
                        Mark project as Done
                    </button>
                </form>
            @else
                <button id="markAsDoneButton" class="bg-gray-500 text-white px-4 py-2 rounded float-right cursor-not-allowed" disabled>
                    Mark project as Done
                </button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const projectId = {{ $project->id }};
            const loadingOverlay = document.getElementById('loadingOverlay');
            const taskColumns = document.getElementById('taskColumns');

            const openModalButton = document.getElementById('openModal');
            const closeModalButton = document.getElementById('closeModal');
            const createTaskBtn = document.getElementById('createTask');
            const modal = document.getElementById('modal');

            const dummyTasks = [
                "This is a dummy task. You can create real tasks!",
                "Add your first task to get started!",
                "Your tasks will appear here!",
                "Create a new task using the button above!",
                "This is a placeholder task. Add your tasks!"
            ];

            showLoadingSpinner();
            fetchTasks().then(() => {
                hideLoadingSpinner();
            }).catch(() => {
                hideLoadingSpinner();
            });

            // Function to fetch tasks and render them
            function fetchTasks() {
                return axios.get(`/projects/${projectId}/tasks`)
                    .then(response => {
                        const tasks = response.data;

                        // Clear existing tasks in all columns
                        document.getElementById('planning').innerHTML = '';
                        document.getElementById('doing').innerHTML = '';
                        document.getElementById('done').innerHTML = '';

                        if (tasks.length === 0) {
                            // If no tasks are found, add dummy tasks to each column
                            addDummyTasks('planning');
                            addDummyTasks('doing');
                            addDummyTasks('done');
                        } else {
                            // Render real tasks in the appropriate columns
                            tasks.forEach(task => {
                                addTaskToColumn(task);
                            });

                            // Remove dummy tasks in all columns if real tasks exist
                            removeDummyTasks('planning');
                            removeDummyTasks('doing');
                            removeDummyTasks('done');
                        }

                        // Show the task columns after rendering tasks
                        taskColumns.classList.remove('hidden');

                        // Reinitialize sortable after adding tasks
                        initializeSortable();
                    })
                    .catch(error => {
                        toastr.error('Failed to load tasks');
                    });
            }

            // Function to show the loading spinner
            function showLoadingSpinner() {
                loadingOverlay.style.display = 'flex';
            }

            // Function to hide the loading spinner
            function hideLoadingSpinner() {
                loadingOverlay.style.display = 'none';
            }

            // Function to open the modal
            function openModal() {
                modal.style.display = 'flex';
            }

            // Function to close the modal
            function closeModal() {
                modal.style.display = 'none';
                document.getElementById('taskForm').reset(); // Reset the form fields when modal is closed
            }

            // Event listener for the open modal button
            openModalButton.addEventListener('click', openModal);

            // Event listener for the close modal button
            closeModalButton.addEventListener('click', closeModal);

            // Create new task
            createTaskBtn.addEventListener('click', function() {
                const title = document.getElementById('title').value;
                const status = document.getElementById('status').value;

                showLoadingSpinner();
                axios.post(`/projects/${projectId}/tasks`, {
                        title,
                        status
                    })
                    .then(response => {
                        toastr.success('Task created successfully');
                        const task = response.data;

                        // Remove dummy tasks in all columns
                        removeDummyTasks('planning');
                        removeDummyTasks('doing');
                        removeDummyTasks('done');

                        // Add the new task to the specified column
                        addTaskToColumn(task);

                        // Update the button visibility based on the new task count
                        updateMarkAsDoneButton();

                        // Reset the form and close the modal
                        document.getElementById('taskForm').reset();
                        closeModal();
                    })
                    .catch(error => {
                        toastr.error('Failed to create task');
                    })
                    .finally(() => {
                        hideLoadingSpinner();
                    });
            });

            // Function to update the Mark as Done button visibility
            function updateMarkAsDoneButton() {
                const markAsDoneButton = document.getElementById('markAsDoneButton');
                
                // Get the project tasks count directly from the Blade data or through a new Axios call
                const projectTasksCount = {{ $project->tasks->count() }}; // Get the initial count directly from the server

                // Check if the button should be enabled or disabled
                if (projectTasksCount > 0) {
                    markAsDoneButton.classList.remove('bg-gray-500', 'cursor-not-allowed');
                    markAsDoneButton.classList.add('bg-green-500', 'hover:bg-green-600');
                    markAsDoneButton.disabled = false; // Enable the button
                    markAsDoneButton.innerText = "Mark project as Done"; // Optional: Update text if needed
                } else {
                    markAsDoneButton.classList.add('bg-gray-500', 'cursor-not-allowed');
                    markAsDoneButton.classList.remove('bg-green-500', 'hover:bg-green-600');
                    markAsDoneButton.disabled = true; // Disable the button
                    markAsDoneButton.innerText = "Mark project as Done"; // Optional: Reset text if needed
                }
            }



            // Function to render a new task in the appropriate column
            function addTaskToColumn(task) {
                const taskElement = document.createElement('div');
                taskElement.className = 'task p-2 mb-2 rounded shadow';
                taskElement.innerText = task.title;
                taskElement.dataset.id = task.id;
                taskElement.dataset.status = task.status; // Track the original status

                // Determine the background color based on the task status
                switch (task.status) {
                    case 'planning':
                        taskElement.classList.add('bg-blue-200'); // Subtle blue
                        break;
                    case 'doing':
                        taskElement.classList.add('bg-orange-200'); // Subtle yellow
                        break;
                    case 'done':
                        taskElement.classList.add('bg-green-200'); // Subtle green
                        break;
                    default:
                        taskElement.classList.add('bg-gray-100'); // Default background color
                        break;
                }

                document.getElementById(task.status).appendChild(taskElement);
            }


            // Function to add dummy tasks to a column
            function addDummyTasks(columnId) {
                const column = document.getElementById(columnId);
                dummyTasks.forEach((title, index) => {
                    const dummyTaskElement = document.createElement('div');
                    dummyTaskElement.className = 'dummy-task p-2 mb-2 rounded shadow';
                    dummyTaskElement.innerText = title;
                    dummyTaskElement.style.opacity = 1 - (index * 0.1); // Gradual fading effect
                    column.appendChild(dummyTaskElement);
                });
            }

            // Function to remove dummy tasks from a column
            function removeDummyTasks(columnId) {
                const column = document.getElementById(columnId);
                const dummyTasks = column.querySelectorAll('.dummy-task');
                dummyTasks.forEach(task => task.remove());
            }

            // Function to initialize sortable for each task column
            function initializeSortable() {
                ['planning', 'doing', 'done'].forEach(status => {
                    new Sortable(document.getElementById(status), {
                        group: 'tasks',
                        animation: 150,
                        onStart: function(evt) {
                            // Save the original status and initial order on drag start
                            evt.item.dataset.originalStatus = evt.item.dataset.status;

                            // Capture the initial order of tasks in the column
                            const initialOrder = Array.from(evt.to.children).map(task => task.dataset.id);
                            evt.to.dataset.initialOrder = JSON.stringify(initialOrder);
                        },
                        onEnd: function(evt) {
                            const newStatus = evt.to.id;
                            const originalStatus = evt.item.dataset.originalStatus;
                            const taskId = evt.item.dataset.id;
                            const tasks = Array.from(evt.to.children);

                            // Check if the task is a dummy task
                            const isDummyTask = evt.item.classList.contains('dummy-task');
                            if (isDummyTask) {
                                toastr.success('Dummy task moved');
                                return; // Exit early for dummy tasks
                            }

                            // Determine if the task's status has changed
                            const statusChanged = newStatus !== originalStatus;

                            // Compare the initial and final order of tasks in the same column
                            const initialOrder = JSON.parse(evt.to.dataset.initialOrder || "[]");
                            const finalOrder = tasks.map(task => task.dataset.id);
                            const orderChanged = JSON.stringify(initialOrder) !== JSON.stringify(finalOrder);

                            showLoadingSpinner();

                            // Handle status change and order change separately and clearly
                            if (statusChanged) {
                                // Update task status if it has changed
                                updateTaskStatus(taskId, newStatus)
                                    .then(() => {
                                        toastr.success('Task status updated successfully');
                                        // Update the task data in the DOM
                                        evt.item.dataset.status = newStatus;

                                        // Re-render the task to apply the correct background color
                                        addTaskToColumn({ title: evt.item.innerText, id: taskId, status: newStatus });
                                        evt.item.remove(); // Remove the old task element
                                    })
                                    .catch(error => {
                                        toastr.error('Failed to update task status');
                                    })
                                    .finally(() => {
                                        hideLoadingSpinner();
                                    });
                            } else if (orderChanged) {
                                // Update task order if it has changed
                                updateTaskOrder(newStatus, tasks)
                                    .then(() => {
                                        toastr.success('Task order updated successfully');
                                    })
                                    .catch(error => {
                                        toastr.error('Failed to update task order');
                                    })
                                    .finally(() => {
                                        hideLoadingSpinner();
                                    });
                            } else {
                                // No status or order change, just display success message
                                toastr.error('Task not moved');
                                hideLoadingSpinner(); // Ensure spinner is hidden
                            }
                        }
                    });
                });
            }

            // Function to update task status
            function updateTaskStatus(taskId, newStatus) {
                return axios.put(`/projects/${projectId}/tasks/${taskId}`, {
                    status: newStatus
                });
            }

            // Function to update task order
            function updateTaskOrder(newStatus, tasks) {
                let taskOrder = tasks.map((task, index) => ({
                    id: task.dataset.id,
                    order: index + 1, // Position in the new status column
                    status: newStatus // Ensure to pass the current status
                }));

                return axios.post(`/projects/${projectId}/tasks/update-order`, {
                    tasks: taskOrder
                });
            }
        });
    </script>

    </body>
</x-app-layout>
