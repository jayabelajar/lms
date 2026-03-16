<x-app-layout>
    <div x-data="{ 
        showAddDrawer: {{ session('open_drawer') ? 'true' : 'false' }},
        mode: 'create',
        actionUrl: '{{ route('instructor.materials.quick-store') }}',
        type: 'text',
        selectedCourseId: '{{ session('selected_course_id', '') }}',
        materialTitle: '',
        materialContent: '',
        materialVideoUrl: '',
        materialOrder: 0,
        selectedSectionId: '',
        showAddSectionDrawer: false,
        sectionCourseId: '',
        sectionTitle: '',
        sectionOrder: 0,
        coursesData: {{ Js::from($courses) }},
        get availableSections() {
            if (!this.selectedCourseId) return [];
            let course = this.coursesData.find(c => c.id == this.selectedCourseId);
            return course && course.sections ? course.sections : [];
        },
        createSection() {
            this.sectionCourseId = this.selectedCourseId || '';
            this.sectionTitle = '';
            this.sectionOrder = 0;
            this.showAddSectionDrawer = true;
        },
        createMaterial() {
            this.mode = 'create';
            this.actionUrl = '{{ route('instructor.materials.quick-store') }}';
            this.type = 'text';
            this.selectedCourseId = '{{ session('selected_course_id', '') }}';
            this.selectedSectionId = '';
            this.materialTitle = '';
            this.materialContent = '';
            this.materialVideoUrl = '';
            this.materialOrder = 0;
            this.showAddDrawer = true;
        },
        editMaterial(id, courseId, sectionId, title, type, content, videoUrl, sortOrder) {
            this.mode = 'edit';
            this.actionUrl = '/dosen/materials/' + id;
            this.selectedCourseId = courseId;
            this.selectedSectionId = sectionId || '';
            this.materialTitle = title;
            this.type = type;
            this.materialContent = content || '';
            this.materialVideoUrl = videoUrl || '';
            this.materialOrder = sortOrder;
            this.showAddDrawer = true;
        }
    }">

    @if (session('status'))
        <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl mb-6">
            {{ session('status') }}
        </div>
    @endif

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">All materials</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola dan lihat informasi detail tentang all materials.</p>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <x-button type="button" @click="createSection()" icon="folder-plus" variant="secondary" class="w-full sm:w-auto">Add Section</x-button>
                    <x-button type="button" @click="createMaterial()" icon="plus" class="w-full sm:w-auto">Add Material</x-button>
                </div>
            </div>
        </x-slot>

        <form method="GET" action="{{ route('instructor.materials.overview') }}" class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-4 lg:col-span-5 w-full">
                    <x-input label="Search Title" name="q" type="text" icon="search" placeholder="Search by title..." value="{{ request('q') }}" />
                </div>
                <div class="md:col-span-5 lg:col-span-4 w-full">
                    <x-select name="course" label="Filter Course" icon="book" placeholder="All Courses" onchange="this.form.submit()">
                        @foreach ($courses as $c)
                            <option value="{{ $c->id }}" @selected(request('course') == $c->id)>{{ $c->title }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="md:col-span-3 lg:col-span-3 w-full flex items-center gap-2 sm:gap-3">
                    <x-button type="submit" icon="filter" class="flex-1 justify-center">Filter</x-button>
                    <x-button href="{{ route('instructor.materials.overview') }}" variant="secondary" class="flex-1 justify-center">Reset</x-button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full text-sm">
                <thead class="text-left text-[11px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Courses</th>
                        <th class="px-4 py-3">Section</th>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($materials as $material)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $material->course?->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $material->section?->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $material->title }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-indigo-100 text-indigo-700">
                                    {{ $material->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $material->sort_order }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <x-button type="button" @click="editMaterial('{{ $material->id }}', '{{ $material->course_id }}', '{{ $material->course_section_id }}', '{{ escapeshellcmd($material->title) }}', '{{ $material->type }}', '{{ escapeshellcmd($material->content) }}', '{{ $material->video_url }}', '{{ $material->sort_order }}')" size="sm" variant="secondary" icon="edit">
                                        Edit
                                    </x-button>
                                    <form method="POST" action="{{ route('instructor.materials.destroy', $material) }}" onsubmit="return confirm('Delete this material?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" size="sm" variant="danger" icon="trash">
                                            Delete
                                        </x-button>
                                    </form>
                                    <x-button href="{{ route('instructor.courses.show', $material->course) }}" data-drawer="true" size="sm" variant="secondary" icon="eye">
                                        Course
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500" colspan="6">No materials.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div>
        {{ $materials->links() }}
    </div>

    <!-- Add Drawer -->
    <div x-show="showAddDrawer" style="display: none;" class="relative z-[100]">
        <div x-transition.opacity class="fixed inset-0 bg-gray-900/80" @click="showAddDrawer = false"></div>
        <div class="fixed inset-y-0 right-0 flex w-full sm:w-[500px] pointer-events-none">
            <div x-transition.transform="" class="w-full h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="mode === 'create' ? 'Add Material' : 'Edit Material'"></h3>
                    <button type="button" @click="showAddDrawer = false" class="text-gray-400 hover:text-gray-500">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto w-full flex-1">
                    <form method="POST" :action="actionUrl" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <template x-if="mode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <x-select name="course_id" label="Course" icon="book" x-model="selectedCourseId" required>
                            <option value="">Select course...</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </x-select>

                        <x-select name="course_section_id" x-model="selectedSectionId" label="Section (optional)" icon="list">
                            <option value="">No section (Umum)</option>
                            <template x-for="section in availableSections" :key="section.id">
                                <option :value="section.id" x-text="section.title" :selected="section.id == selectedSectionId"></option>
                            </template>
                        </x-select>

                        <x-input name="title" x-model="materialTitle" label="Material Title" icon="book" required />

                        <x-select name="type" label="Type" icon="category" x-model="type" required>
                            <option value="text">Text / Konten</option>
                            <option value="file">File Berkas</option>
                            <option value="video">Video URL</option>
                        </x-select>

                        <div x-show="type === 'text'">
                            <x-textarea name="content" x-model="materialContent" label="Text Content" icon="notes" rows="4"></x-textarea>
                        </div>

                        <div x-show="type === 'video'">
                            <x-input name="video_url" x-model="materialVideoUrl" label="Video URL" icon="link" placeholder="https://..." />
                        </div>

                        <div x-show="type === 'file'" class="space-y-2">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase ml-1 tracking-widest" x-text="mode === 'create' ? 'Upload File' : 'Upload File Baru (Opsional)'"></label>
                            <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-500/10 dark:file:text-indigo-400 dark:hover:file:bg-indigo-500/20">
                        </div>

                        <x-input name="sort_order" x-model="materialOrder" label="Sort Order" icon="arrows-sort" placeholder="0" />

                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100 dark:border-gray-800">
                            <x-button type="submit" name="save_action" value="save" icon="check" class="w-full sm:flex-1 justify-center">Save</x-button>
                            <template x-if="mode === 'create'">
                                <x-button type="submit" name="save_action" value="save_and_add" icon="plus" class="w-full sm:flex-1 justify-center whitespace-nowrap">Save & Add Another</x-button>
                            </template>
                            <x-button type="button" @click="showAddDrawer = false" variant="secondary" class="w-full sm:w-auto justify-center">Cancel</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Section Drawer -->
    <div x-show="showAddSectionDrawer" style="display: none;" class="relative z-[100]">
        <div x-transition.opacity class="fixed inset-0 bg-gray-900/80" @click="showAddSectionDrawer = false"></div>
        <div class="fixed inset-y-0 right-0 flex w-full sm:w-[500px] pointer-events-none">
            <div x-transition.transform="" class="w-full h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Section</h3>
                    <button type="button" @click="showAddSectionDrawer = false" class="text-gray-400 hover:text-gray-500">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto w-full flex-1">
                    <form method="POST" :action="'/dosen/courses/' + sectionCourseId + '/sections'" class="space-y-4">
                        @csrf
                        <x-select name="course_id" label="Course" icon="book" x-model="sectionCourseId" required>
                            <option value="">Select course...</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </x-select>

                        <x-input name="title" x-model="sectionTitle" label="Section Title" icon="list" required />
                        
                        <x-input name="sort_order" x-model="sectionOrder" type="number" label="Sort Order" icon="arrows-sort" placeholder="0" />

                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100 dark:border-gray-800">
                            <x-button type="submit" icon="check" class="w-full sm:w-auto justify-center">Save Section</x-button>
                            <x-button type="button" @click="showAddSectionDrawer = false" variant="secondary" class="w-full sm:w-auto justify-center">Cancel</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    </div>
</x-app-layout>


