<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

        <div class="space-y-8">

            <!-- Ongoing Projects Section -->
            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4">Ongoing Projects</h2>
                @if($ongoingProjects->isEmpty())
                <p>No ongoing projects.</p>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($ongoingProjects as $project)
                    <div class="relative bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="{{ url('/images/project_img/' . $project->image) }}" alt="{{ $project->title }}" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <h2 class="text-white text-xl font-bold text-center">
                                <a href="{{ route('project.show', $project->id) }}" class="hover:underline">
                                    {{ Str::ucfirst($project->title) }}
                                </a>
                            </h2>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- View More Button -->
                <div class="text-center mt-4">
                    <a href="{{ route('projects') }}" class="text-blue-600 hover:underline font-semibold">
                        View More
                    </a>
                </div>
                @endif
            </div>


            <!-- Doing Tasks Section -->
            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4">Doing Tasks</h2>
                @if($doingTasks->isEmpty())
                <p>No tasks currently being done.</p>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($doingTasks as $task)
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-600">{{ $task->title }}</h3>
                        <p class="text-gray-600">{{ Str::limit($task->description, 100) }}</p>
                        <a href="{{ route('project.show', $task->project_id) }}" class="text-blue-600 hover:underline font-semibold">
                        View More
                    </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Collection of Projects Section -->
            <div class="bg-white shadow-lg rounded-lg p-4 mt-6">
                <h2 class="text-xl font-semibold mb-4">My Collection</h2>
                @if($userProjects->isEmpty())
                <p>You have no projects.</p>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($userProjects as $project)
                    <div class="relative bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="{{ url('/images/project_img/' . $project->image) }}" alt="{{ $project->title }}" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <h2 class="text-white text-xl font-bold text-center">
                                <a href="{{ route('project.show', $project->id) }}" class="hover:underline">
                                    {{ Str::ucfirst($project->title) }}
                                </a>
                            </h2>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- View More Button -->
                <div class="text-center mt-4">
                    <a href="{{ route('show.collection') }}" class="text-blue-600 hover:underline font-semibold">
                        View More
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>