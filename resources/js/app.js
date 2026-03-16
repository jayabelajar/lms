/**
 * --------------------------------------------------------------------------
 * Laravael UI Dashboard
 * --------------------------------------------------------------------------
 * @package     Laravael UI
 * @author      Rafael Nuansa <email@anda.com>
 * @copyright   Copyright (c) 2026 Rafael Nuansa
 * @license     MIT License
 * @link        https://github.com/rafaelnuansa/laravael-ui-dashboard
 * --------------------------------------------------------------------------
 * Please do not remove this header. Respect the craft.
 */

/* ========================
   Bootstrap & Alpine
   ======================== */
import './bootstrap'
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm'
import collapse from '@alpinejs/collapse'

window.Alpine = Alpine
Alpine.plugin(collapse)

/* ========================
   Third-party Libraries
   ======================== */
import Swal from 'sweetalert2'
window.Swal = Swal

/* ========================
   Theme Helpers
   ======================== */
function applyTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }
}

/* ========================
   Alpine Stores
   ======================== */
document.addEventListener('alpine:init', () => {
   Alpine.store('sidebar', {
        isExpanded: localStorage.getItem('sidebar-expanded') !== 'false',
        isMobileOpen: false,
        isHovered: false,

        toggleExpanded() {
            this.isExpanded = !this.isExpanded;
            localStorage.setItem('sidebar-expanded', this.isExpanded);
        },
        toggleMobileOpen() {
            this.isMobileOpen = !this.isMobileOpen;
        },
        setHovered(val) {
            this.isHovered = val;
        }
    });

    Alpine.store('theme', {
        theme: localStorage.getItem('theme') || 'light',
        toggle() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            applyTheme(this.theme);
        }
    });
});

/* ========================
   Apply Theme on Load
   ======================== */
const savedTheme = localStorage.getItem('theme') || 'light'
applyTheme(savedTheme)

/* ========================
   Start Livewire and Alpine
   ======================== */
Livewire.start()

/* ========================
   Session Alert Handler
   ======================== */
document.addEventListener('DOMContentLoaded', () => {
    const alert = window.laravelAlert
    if (!alert || !alert.message) return
    const isDark = document.documentElement.classList.contains('dark')
    Swal.fire({
        title: alert.title ?? (alert.type === 'success' ? 'Berhasil' : 'Info'),
        text: alert.message ?? '',
        icon: alert.type ?? 'info',
        confirmButtonColor: '#4f46e5',
        background: isDark ? '#030712' : '#ffffff',
        color: isDark ? '#f3f4f6' : '#111827',
    })
})
