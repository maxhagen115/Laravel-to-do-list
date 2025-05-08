<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">My Collection</h1>

        <div class="space-y-8">

            <!-- Collection of Projects Section -->
            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4">All projects</h2>
                <p class="text-small font-semibold mb-4">All projects that are finished or deleted</p>
                @if($userProjects->isEmpty())
                    <p>You have no projects.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @foreach($userProjects as $project)
                            <div class="relative bg-white shadow-lg rounded-lg overflow-hidden">
                                <img src="{{ url('/images/project_img/' . $project->image) }}" alt="{{ $project->title }}" class="w-full h-64 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <h2 class="text-white text-xl font-bold text-center">
                                        <a href="{{ route('project.show', $project->id) }}" class="no-underline hover:no-underline focus:no-underline">
                                            {{ Str::ucfirst($project->title) }}
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
