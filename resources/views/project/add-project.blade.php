<x-app-layout>
    <div class="py-12">
        <div class="container mx-auto p-8">
            <div class="container mx-auto p-8">
                <h2 class="text-3xl font-bold mb-6 text-center">Add a project</h2>

                <body class="font-sans bg-gray-100 h-screen flex items-center">
                    <div class="container mx-auto p-4">
                        <form id="projectForm" class="max-w-md mx-auto bg-white rounded overflow-hidden shadow-lg p-6"
                            enctype="multipart/form-data" action="{{ url('save-project') }}" method="POST">
                            @csrf
                            <label class="text-center block font-bold text-lg mb-2">Title</label>
                            <input type="text" id="title" name="title" minlength="1" maxlength="250"
                                value="{{ old('title') }}" required
                                class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500 mb-4"
                                placeholder="Add title">
                            <label class="text-center block font-bold text-lg mb-2">Picture</label>
                            <input type="file" accept="image/png, image/jpeg" id="image" name="image"
                                value="{{ old('image') }}"
                                class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500 mb-4">
                            <button type="submit" class="btn-primary">Save</button>
                        </form>
                    </div>
                </body>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('projectForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const title = document.getElementById('title').value;

            axios.post('{{ route('project.validateTitle') }}', { title: title })
                .then(response => {
                    if (response.data.exists) {
                        toastr.error('Title already exists.');
                    } else {
                        axios.post('{{ route('save-project') }}', formData)
                            .then(response => {
                                const projectId = response.data.id;
                                window.location.href = `/project/${projectId}`;
                            })
                            .catch(error => {
                                toastr.error('Error submitting form.');
                                console.error('Error submitting form:', error);
                            });
                    }
                })
                .catch(error => {
                    toastr.error('Error checking title.');
                    console.error('Error checking title:', error);
                });
        });
    </script>
</x-app-layout>
