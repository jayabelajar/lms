<div x-data="{
    toasts: [],
    position: 'bottom-right',
    maxToasts: 5,

    init() {
        @if(session('success'))
            this.add({ detail: { type: 'success', message: '{{ session('success') }}' } });
        @endif
        @if(session('error'))
            this.add({ detail: { type: 'error', message: '{{ session('error') }}' } });
        @endif
    },

    add(e) {
        const id = Date.now();
        if (e.detail.position) this.position = e.detail.position;

        const newToast = {
            id: id,
            type: e.detail.type || 'info',
            message: e.detail.message || '',
            progress: 100,
            show: false
        };

        if (this.position.includes('top')) {
            this.toasts.unshift(newToast);
        } else {
            this.toasts.push(newToast);
        }

        if (this.toasts.length > this.maxToasts) {
            if (this.position.includes('top')) {
                this.toasts.pop();
            } else {
                this.toasts.shift();
            }
        }

        setTimeout(() => {
            const t = this.toasts.find(x => x.id === id);
            if (t) t.show = true;
        }, 10);

        const duration = 5000;
        const interval = 50;
        const step = (interval / duration) * 100;
        const timer = setInterval(() => {
            const t = this.toasts.find(x => x.id === id);
            if (!t) { clearInterval(timer); return; }
            t.progress -= step;
            if (t.progress <= 0) {
                this.remove(id);
                clearInterval(timer);
            }
        }, interval);
    },

    remove(id) {
        const t = this.toasts.find(x => x.id === id);
        if (t) t.show = false;
        setTimeout(() => {
            this.toasts = this.toasts.filter(x => x.id !== id);
        }, 300);
    }
}" @toast.window="add($event)"
    class="fixed z-[10000] flex flex-col gap-3 w-full max-w-sm p-5 pointer-events-none transition-all duration-500"
    :class="{
        'top-0 right-0 flex-col-reverse': position === 'top-right',
        'top-0 left-0 flex-col-reverse': position === 'top-left',
        'bottom-0 right-0 flex-col': position === 'bottom-right',
        'bottom-0 left-0 flex-col': position === 'bottom-left',
        'top-0 left-1/2 -translate-x-1/2 flex-col-reverse': position === 'top-center',
        'bottom-0 left-1/2 -translate-x-1/2 flex-col': position === 'bottom-center',
    }">

    <template x-for="(toast, index) in toasts" :key="toast.id">
        <div x-show="toast.show"
            x-transition:enter="transition duration-500 ease-[cubic-bezier(0.68,-0.55,0.265,1.55)]"
            x-transition:enter-start="opacity-0 scale-90 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition duration-300 ease-in"
            x-transition:leave-end="opacity-0 scale-90"

            class="pointer-events-auto relative overflow-hidden flex items-center gap-3 px-5 py-4 rounded-2xl border shadow-xl transition-all duration-300"
            :class="{
                'bg-emerald-600 border-emerald-500 text-white': toast.type === 'success',
                'bg-rose-600 border-rose-500 text-white': toast.type === 'error',
                'bg-amber-500 border-amber-400 text-white': toast.type === 'warning',
                'bg-indigo-600 border-indigo-500 text-white': toast.type === 'info',
                'opacity-60 scale-95': (position.includes('bottom') && index < toasts.length - 1) || (position.includes('top') && index > 0)
            }">

            <div class="flex-shrink-0">
                <i class="ti text-2xl animate-pulse"
                    :class="{
                        'ti-circle-check': toast.type === 'success',
                        'ti-alert-circle': toast.type === 'error',
                        'ti-alert-triangle': toast.type === 'warning',
                        'ti-info-circle': toast.type === 'info'
                    }"></i>
            </div>

            <div class="flex-1 pr-4">
                <p class="text-[10px] font-bold uppercase opacity-80 leading-none tracking-widest"
                    x-text="toast.type === 'error' ? 'Gagal' : (toast.type === 'success' ? 'Berhasil' : 'Pemberitahuan')">
                </p>
                <p class="text-xs font-bold mt-1.5" x-text="toast.message"></p>
            </div>

            <button @click="remove(toast.id)" class="opacity-50 hover:opacity-100 transition-opacity p-1">
                <i class="ti ti-x text-sm"></i>
            </button>

            <div class="absolute bottom-0 left-0 h-1 bg-white/20 transition-all ease-linear"
                :style="`width: ${toast.progress}%`" x-show="toast.show">
            </div>
        </div>
    </template>
</div>
