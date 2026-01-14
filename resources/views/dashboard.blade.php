<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Dashboard</h1>
            @if($hasAwesomeProjectTasks)
            <button id="finishProjectBtn"
                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold shadow-md">
                Finish Project
            </button>
            @endif
        </div>
        <div class="space-y-8">
            <!-- Ongoing Projects Section -->
            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4">Ongoing Projects</h2>

                @if($ongoingProjects->isEmpty())
                <p>No ongoing projects.</p>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($ongoingProjects as $project)
                    <a href="{{ route('project.show', $project->id) }}"
                        class="relative bg-white shadow-lg rounded-lg overflow-hidden block">
                        <img src="{{ url('/images/project_img/' . $project->image) }}"
                            alt="{{ $project->title }}"
                            class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <h2 class="text-white text-xl font-bold text-center">
                                {{ Str::ucfirst($project->title) }}
                            </h2>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Only show when there are more than 4 --}}
                @if($totalOngoingProjects > 4)
                <div class="text-center mt-4">
                    <a href="{{ route('projects') }}"
                        class="text-blue-600 hover:underline font-semibold no-underline focus:no-underline">
                        View More
                    </a>
                </div>
                @endif
                @endif
            </div>

            <!-- Doing Tasks Section -->
            <div class="bg-white shadow-lg rounded-lg p-4" id="doing-tasks-section">
                <h2 class="text-xl font-semibold mb-4">Doing Tasks</h2>

                @if($doingTasks->isEmpty())
                <p>No tasks currently being done.</p>
                @else
                <div id="doingTasksGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($doingTasks as $task)
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-600">{{ $task->title }}</h3>
                        <p class="text-gray-600">{{ Str::limit($task->description, 100) }}</p>
                        <a href="{{ route('project.show', $task->project_id) }}"
                            class="text-blue-600 font-semibold no-underline focus:no-underline">
                            View in project
                        </a>
                    </div>
                    @endforeach
                </div>

                @if($totalDoingTasks > 8)
                <div class="mt-4 text-center">
                    <button id="loadMoreDoingTasks"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                        data-loading="Loading..."
                        data-default="View More Tasks">
                        View More Tasks
                    </button>
                </div>
                @endif
                @endif
            </div>


            <!-- Collection of Projects Section -->
            <div class="bg-white shadow-lg rounded-lg p-4 mt-6">
                <h2 class="text-xl font-semibold mb-4">My Collection</h2>

                @if($userProjects->isEmpty())
                <p>You have no archived or deleted projects.</p>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($userProjects as $project)
                    <a href="{{ route('project.show', $project->id) }}"
                        class="relative bg-white shadow-lg rounded-lg overflow-hidden block">
                        <img src="{{ url('/images/project_img/' . $project->image) }}"
                            alt="{{ $project->title }}"
                            class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <h2 class="text-white text-xl font-bold text-center">
                                {{ Str::ucfirst($project->title) }}
                            </h2>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Only show when there are more than 4 --}}
                @if($totalUserProjects > 4)
                <div class="text-center mt-4">
                    <a href="{{ route('show.collection') }}"
                        class="text-blue-600 font-semibold no-underline focus:no-underline">
                        View More
                    </a>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Finish Project Button Handler
            const finishProjectBtn = document.getElementById('finishProjectBtn');
            if (finishProjectBtn) {
                finishProjectBtn.addEventListener('click', () => {
                    // Show confirmation popup
                    if (confirm('Are you sure you want to finish this project?')) {
                        // User clicked OK
                        finishProjectBtn.disabled = true;
                        finishProjectBtn.innerText = 'Finishing...';

                        // Set up CSRF token for axios
                        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        axios.post("{{ route('dashboard.finishAwesomeProject') }}")
                            .then(response => {
                                // Show confetti animation
                                confetti({
                                    particleCount: 100,
                                    spread: 70,
                                    origin: { y: 0.6 }
                                });

                                // Show success toastr message
                                toastr.success('Project successfully ended');

                                // Reload the page after a short delay to reflect changes
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            })
                            .catch(error => {
                                toastr.error('Failed to finish project.');
                                console.error('Finish project error:', error);
                                finishProjectBtn.disabled = false;
                                finishProjectBtn.innerText = 'Finish Project';
                            });
                    }
                    // If user clicked Cancel, do nothing
                });
            }

            const button = document.getElementById('loadMoreDoingTasks');
            const grid = document.getElementById('doingTasksGrid');
            let offset = 8;

            if (button) {
                button.addEventListener('click', () => {
                    // Toon laadindicator
                    button.disabled = true;
                    const originalText = button.dataset.default || 'View More Tasks';
                    const loadingText = button.dataset.loading || 'Loading...';
                    button.innerText = loadingText;

                    axios.get("{{ route('tasks.loadMoreDoing') }}", {
                        params: {
                            offset: offset
                        }
                    }).then(response => {
                        const tasks = response.data;

                        if (!Array.isArray(tasks) || tasks.length === 0) {
                            button.style.display = 'none';
                            return;
                        }

                        tasks.forEach(task => {
                            const div = document.createElement('div');
                            div.className = 'bg-white shadow-md rounded-lg p-4';
                            div.innerHTML = `
                        <h3 class="text-lg font-semibold text-blue-600">${task.title}</h3>
                        <p class="text-gray-600">${task.description?.substring(0, 100) ?? ''}</p>
                        <a href="/projects/${task.project_id}" class="text-blue-600 font-semibold no-underline focus:no-underline">
                            View More
                        </a>
                    `;
                            grid.appendChild(div);
                        });

                        offset += tasks.length;

                        if (tasks.length < 8) {
                            button.style.display = 'none';
                        }
                    }).catch((error) => {
                        toastr.error('Failed to load more tasks.');
                        console.error('Load error:', error);
                    }).finally(() => {
                        // Herstel knop
                        button.innerText = originalText;
                        button.disabled = false;
                    });
                });
            }
        });
    </script>

</x-app-layout>