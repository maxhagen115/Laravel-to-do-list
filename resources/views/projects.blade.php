<x-app-layout>
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6 flex-1">Your Ongoing Projects</h1>
            <a href="{{ route('project.add-project') }}" class="btn-primary float-right">Add Project</a>

            <form action="{{ route('projects') }}" method="GET" class="mb-8">
                <input type="text" id="search" name="search" placeholder="Search projects..." value="{{ request('search') }}" class="w-64 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 ease-in-out">
            </form>

            <div class="bg-white shadow-lg rounded-lg p-4">
                @if (count($projects) > 0)
                <div id="project-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($projects as $project)
                    <div class="relative group bg-white shadow-lg rounded-lg overflow-hidden">
                        <!-- Project Image -->
                        <a href="{{ route('project.show', $project->id) }}">
                            <img src="{{ url('/images/project_img/' . $project->image) }}" alt="{{ $project->title }}" class="w-full h-64 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                <h2 class="text-white text-xl font-bold text-center">
                                    {{ Str::ucfirst($project->title) }}
                                </h2>
                            </div>
                        </a>

                        <!-- Action Buttons -->
                        <div class="absolute top-2 right-2 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <!-- Edit Icon -->
                            <button onclick="openEditModal({{ $project->id }}, '{{ addslashes($project->title) }}')" class="bg-white p-2 rounded-full hover:bg-gray-200" title="Edit">
                                ✏️
                            </button>

                            <!-- Delete Icon -->
                            <form action="{{ route('project.softDelete', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-white p-2 rounded-full hover:bg-red-100" title="Delete">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
                </div>
                @else
                    <p>No projects created yet</p>
                @endif
            </div>
        </div>

        <!-- modal -->
        <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-4">Edit Project Title</h2>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" name="title" id="editTitle" class="w-full p-2 border border-gray-300 rounded mb-4" required>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
            function openEditModal(projectId, title) {
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editTitle').value = title;
                document.getElementById('editForm').action = `/project/${projectId}/update-title`;
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }
        </script>

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

                        if (projects.length === 0) {
                            projectList.innerHTML = '<p class="text-gray-500 mf-2">No results found</p>';
                        } else {
                            projects.forEach(project => {
                                const capitalizedTitle = project.title.charAt(0).toUpperCase() + project.title.slice(1);

                                const projectCard = `
                                    <a href="/project/${project.id}" class="block">
                                        <div class="relative bg-white shadow-lg rounded-lg overflow-hidden">
                                            <img src="${baseUrl}/${project.image}" alt="${capitalizedTitle}" class="w-full h-64 object-cover">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                                <h2 class="text-white text-xl font-bold text-center">${capitalizedTitle}</h2>
                                            </div>
                                        </div>
                                    </a>
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
