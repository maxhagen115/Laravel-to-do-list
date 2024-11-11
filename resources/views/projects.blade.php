<x-app-layout>
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6 flex-1">Your Projects</h1>
            <a href="{{ route('project.add-project') }}" class="btn-primary float-right">Add Project</a>

            <form action="{{ route('projects') }}" method="GET" class="mb-8">
                <input type="text" id="search" name="search" placeholder="Search projects..." value="{{ request('search') }}" class="w-64 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out">
            </form>

            <div class="bg-white shadow-lg rounded-lg p-4">
                @if (count($projects) > 0)
                    <div id="project-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @foreach ($projects as $project)
                            <div class="relative bg-white shadow-lg rounded-lg overflow-hidden">
                                <img src="{{ url('/images/project_img/' . $project->image) }}" alt="{{ $project->title }}" class="w-full h-64 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <h2>
                                        <a href="{{ route('project.show', $project->id) }}" class="text-white text-xl font-bold text-center hover:underline">
                                            {{ Str::ucfirst($project->title) }}
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No projects created yet</p>
                @endif
            </div>
        </div>

    <script>
        document.getElementById('search').addEventListener('input', function() {
            const query = this.value;
            const baseUrl = '{{ url('/images/project_img') }}';
            axios.get('{{ route('projects') }}', {
                    params: {
                        search: query
                    }
                })
                .then(response => {
                    const projects = response.data;
                    const projectList = document.getElementById('project-list');
                    projectList.innerHTML = '';

                    if(projects.length === 0){
                    projectList.innerHTML = '<p class="text-gray-500 mf-2">No results found</p>';
                    }else {
                    projects.forEach(project => {
                        const projectCard = `
                        <div class="relative bg-white shadow-lg rounded-lg overflow-hidden">
                            <img src="${baseUrl}/${project.image}" alt="${project.title}" class="w-full h-64 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                <h2><a href="/project/${project.id}" class="text-white text-xl font-bold text-center hover:underline">${project.title}</a></h2>
                            </div>
                        </div>
                    `;
                        projectList.innerHTML += projectCard;
                    });
                }
                })
                .catch(error => {
                    console.error('Error fetching projects:', error);
                });
        });
    </script>
</x-app-layout>
