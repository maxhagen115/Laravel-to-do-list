<x-app-layout>
    <div class="py-12">
        <div class="container mx-auto p-8">
            <div class="container mx-auto p-4 flex items-center">
                <h2 class="text-3xl font-bold mb-6 flex-1">Your Projects</h2>
                <a href="{{ route('project.add-project') }}" class="btn-primary">Add Project</a>
            </div>
            <form action="{{ route('projects') }}" method="GET" class="mb-8">
                <input type="text" id="search" name="search" placeholder="Search projects..." value="{{ request('search') }}" class="p-2 border rounded">
            </form>


            @if (count($projects) > 0)
                <div id="project-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($projects as $project)
                        <div class="relative bg-white shadow-lg rounded-lg overflow-hidden">
                            <img src="{{ url('/images/project_img/' . $project->image) }}" alt="{{ $project->title }}"
                                class="w-full h-64 object-cover">
                            <div class="absolute inset-0 bg-white-100 bg-black bg-opacity-50 flex items-center justify-center">
                                <h2><a href="/project/{{ $project->id }}"
                                        class="text-white text-xl font-bold">{{ $project->title }}</a>
                                </h2>
                            </div>
                            <div class="p-4 bg-white">
                                <!-- Additional content can go here -->
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

                    projects.forEach(project => {
                        const projectCard = `
                        <div class="relative bg-white shadow-lg rounded-lg overflow-hidden">
                            <img src="${baseUrl}/${project.image}" alt="${project.title}" class="w-full h-64 object-cover rounded mb-8">
                            <a href="/projects/${project.id}" class="absolute inset-0 flex items-center justify-center text-xl font-semibold text-white bg-black bg-opacity-50 rounded">${project.title}</a>
                        </div>
                    `;
                        projectList.innerHTML += projectCard;
                    });
                })
                .catch(error => {
                    console.error('Error fetching projects:', error);
                });
        });
    </script>
</x-app-layout>
