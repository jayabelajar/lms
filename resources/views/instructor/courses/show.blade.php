<x-app-layout>
    <div x-data="{
        showSectionDrawer: false,
        sectionMode: 'create',
        sectionAction: '{{ route('instructor.sections.store', $course) }}',
        sectionMethod: 'POST',
        sectionTitle: '',
        sectionOrder: 0,
        
        showMaterialDrawer: false,
        materialMode: 'create',
        materialAction: '{{ route('instructor.materials.store', $course) }}',
        materialMethod: 'POST',
        materialSectionId: '',
        materialTitle: '',
        materialType: 'text',
        materialContent: '',
        materialVideoUrl: '',
        materialOrder: 0,

        createSection() {
            this.sectionMode = 'create';
            this.sectionAction = '{{ route('instructor.sections.store', $course) }}';
            this.sectionTitle = '';
            this.sectionOrder = 0;
            this.showSectionDrawer = true;
        },
        editSection(id, title, sort_order) {
            this.sectionMode = 'edit';
            this.sectionAction = '/dosen/sections/' + id;
            this.sectionTitle = title;
            this.sectionOrder = sort_order;
            this.showSectionDrawer = true;
        },
        createMaterial() {
            this.materialMode = 'create';
            this.materialAction = '{{ route('instructor.materials.store', $course) }}';
            this.materialSectionId = '';
            this.materialTitle = '';
            this.materialType = 'text';
            this.materialContent = '';
            this.materialVideoUrl = '';
            this.materialOrder = 0;
            this.showMaterialDrawer = true;
        },
        editMaterial(id, sectionId, title, type, content, videoUrl, sort_order) {
            this.materialMode = 'edit';
            this.materialAction = '/dosen/materials/' + id;
            this.materialSectionId = sectionId || '';
            this.materialTitle = title;
            this.materialType = type;
            this.materialContent = content || '';
            this.materialVideoUrl = videoUrl || '';
            this.materialOrder = sort_order;
            this.showMaterialDrawer = true;
        }
    }">

    @if (session('status'))
        <div class="p-4 mb-6 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        <x-card class="xl:col-span-2">
            <x-slot name="header">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                    <div class="flex flex-col gap-1">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $course->title }}</h3>
                        <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Course details dan informasi lengkap.</p>
                    </div>
                </div>
            </x-slot>

            <div class="p-4 sm:p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-5 rounded-3xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800 flex flex-col justify-center">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status</p>
                        <div>
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                {{ $course->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $course->status }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5 rounded-3xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800 flex flex-col justify-center">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Students</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $course->students->count() }}</p>
                    </div>
                </div>
                <div class="p-5 sm:p-6 rounded-3xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800 text-sm font-medium text-gray-700 dark:text-gray-200 leading-relaxed">
                    {{ $course->description ?: 'No description provided.' }}
                </div>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                    <div class="flex flex-col gap-1">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Enrolled Students</h3>
                        <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Daftar siswa di course ini.</p>
                    </div>
                    <div class="flex sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                        <x-button href="{{ route('instructor.assignments.index', ['course' => $course->id]) }}" variant="secondary" icon="list-check" class="w-full sm:w-auto">
                            Assignments
                        </x-button>
                    </div>
                </div>
            </x-slot>

            <div class="p-4 sm:p-6">
                <ul class="space-y-3">
                    @forelse ($course->students as $student)
                        <li class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                                    {{ substr($student->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $student->name }}</p>
                                    <p class="text-xs font-medium text-gray-500 line-clamp-1">{{ $student->email }}</p>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="p-4 border border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-center">
                            <p class="text-sm font-medium text-gray-500">No enrollments yet.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </x-card>
    </div>

    <x-card>
        <x-slot name="header">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sections & Materials</h3>
                    <p class="text-[13px] font-medium text-gray-500 dark:text-gray-400">Kelola struktur kurikulum dan materi pembelajaran.</p>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                    <x-button @click="createSection()" type="button" icon="folder-plus" variant="secondary" class="w-full sm:w-auto">Add Section</x-button>
                    <x-button @click="createMaterial()" type="button" icon="plus" class="w-full sm:w-auto">Add Material</x-button>
                </div>
            </div>
        </x-slot>

        <div class="p-4 sm:p-6">
            <div class="space-y-6">
                @forelse ($course->sections as $section)
                    <div class="p-4 sm:p-5 rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/20">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between w-full pb-4 border-b border-gray-100 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="min-w-10 w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500">
                                    <i class="ti ti-folder text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-[15px] font-bold text-gray-900 dark:text-white leading-tight">{{ $section->title }}</h4>
                                    <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase mt-0.5">Order: {{ $section->sort_order }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-button type="button" @click="editSection('{{ $section->id }}', '{{ escapeshellcmd($section->title) }}', '{{ $section->sort_order }}')" size="sm" variant="secondary" icon="edit">Edit</x-button>
                                <form method="POST" action="{{ route('instructor.sections.destroy', $section) }}" onsubmit="return confirm('Hapus section ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" size="sm" variant="danger" icon="trash" class="!px-3"><span class="sr-only">Delete</span></x-button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse ($section->materials as $material)
                                <div class="p-3 sm:p-4 rounded-xl bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/50 dark:hover:bg-gray-800 border border-transparent hover:border-gray-200 dark:hover:border-gray-700 transition-colors flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between group">
                                    <div class="flex items-center gap-4">
                                        <div class="min-w-9 w-9 h-9 rounded-lg bg-white dark:bg-gray-700 shadow-sm flex items-center justify-center text-indigo-500">
                                            @if($material->type === 'video')
                                                <i class="ti ti-video text-lg"></i>
                                            @elseif($material->type === 'file')
                                                <i class="ti ti-file-download text-lg"></i>
                                            @else
                                                <i class="ti ti-file-text text-lg"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">{{ $material->title }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                        <x-button type="button" @click="editMaterial('{{ $material->id }}', '{{ $material->course_section_id }}', '{{ escapeshellcmd($material->title) }}', '{{ $material->type }}', '{{ escapeshellcmd($material->content) }}', '{{ $material->video_url }}', '{{ $material->sort_order }}')" size="sm" variant="secondary" icon="edit">Edit</x-button>
                                        <form method="POST" action="{{ route('instructor.materials.destroy', $material) }}" onsubmit="return confirm('Hapus material ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" size="sm" variant="danger" icon="trash" class="!px-3"><span class="sr-only">Delete</span></x-button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 border border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-center">
                                    <p class="text-[13px] font-medium text-gray-500">Belum ada material di section ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-3xl text-center">
                        <div class="w-16 h-16 rounded-3xl bg-gray-50 dark:bg-gray-800/80 flex items-center justify-center text-gray-400 mx-auto mb-4">
                            <i class="ti ti-folder-off text-3xl"></i>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1">Belum ada section</h4>
                        <p class="text-[13px] font-medium text-gray-500">Silakan tambahkan section pertama untuk menyusun material.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </x-card>

    <!-- Section Drawer -->
    <div x-show="showSectionDrawer" style="display: none;" class="relative z-[100]">
        <div x-transition.opacity class="fixed inset-0 bg-gray-900/80" @click="showSectionDrawer = false"></div>
        <div class="fixed inset-y-0 right-0 flex w-full sm:w-[500px] pointer-events-none">
            <div x-transition.transform="" class="w-full h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="sectionMode === 'create' ? 'Add Section' : 'Edit Section'"></h3>
                    <button type="button" @click="showSectionDrawer = false" class="text-gray-400 hover:text-gray-500">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto w-full flex-1">
                    <form :action="sectionAction" method="POST" class="space-y-4">
                        @csrf
                        <template x-if="sectionMode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <x-input x-model="sectionTitle" name="title" label="Section Title" icon="list" required />
                        <x-input x-model="sectionOrder" name="sort_order" label="Order" type="number" icon="arrows-sort" />
                        <x-button type="submit" icon="check" class="w-full">Save Section</x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Material Drawer -->
    <div x-show="showMaterialDrawer" style="display: none;" class="relative z-[100]">
        <div x-transition.opacity class="fixed inset-0 bg-gray-900/80" @click="showMaterialDrawer = false"></div>
        <div class="fixed inset-y-0 right-0 flex w-full sm:w-[500px] pointer-events-none">
            <div x-transition.transform="" class="w-full h-full flex flex-col bg-white dark:bg-gray-900 shadow-2xl pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="materialMode === 'create' ? 'Add Material' : 'Edit Material'"></h3>
                    <button type="button" @click="showMaterialDrawer = false" class="text-gray-400 hover:text-gray-500">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto w-full flex-1">
                    <form :action="materialAction" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <template x-if="materialMode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <x-select x-model="materialSectionId" name="course_section_id" label="Section (optional)" icon="list">
                            <option value="">Tanpa section (Umum)</option>
                            @foreach ($course->sections as $section)
                                <option value="{{ $section->id }}">{{ $section->title }}</option>
                            @endforeach
                        </x-select>
                        <x-input x-model="materialTitle" name="title" label="Material Title" icon="book" required />
                        <x-select x-model="materialType" name="type" label="Type" icon="category" required>
                            <option value="text">Text</option>
                            <option value="file">File</option>
                            <option value="video">Video</option>
                        </x-select>
                        
                        <div x-show="materialType === 'text'">
                            <x-textarea x-model="materialContent" name="content" label="Text Content" icon="notes" rows="4"></x-textarea>
                        </div>
                        <div x-show="materialType === 'video'">
                            <x-input x-model="materialVideoUrl" name="video_url" label="Video URL" icon="link" />
                        </div>
                        <div x-show="materialType === 'file'">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Upload File (Optional on Edit)</label>
                            <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700">
                        </div>
                        
                        <x-input x-model="materialOrder" name="sort_order" label="Order" type="number" icon="arrows-sort" />
                        <x-button type="submit" icon="check" class="w-full">Save Material</x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>
</x-app-layout>
