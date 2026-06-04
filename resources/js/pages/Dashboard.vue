<script setup>
import { Head } from '@inertiajs/vue3';
import AppSidebar from '@/components/AppSidebar.vue';

// Recibimos los datos calculados por Laravel
defineProps({
    totalHabitaciones: {
        type: Number,
        default: 0
    },
    totalTipos: {
        type: Number,
        default: 0
    },
    precioPromedio: {
        type: Number,
        default: 0
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AppSidebar>
        <div class="p-6 space-y-6 text-white bg-neutral-950 min-h-screen">
            
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-neutral-800 pb-5 space-y-2 md:space-y-0">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-neutral-100">Panel de Control</h2>
                    <p class="text-sm text-neutral-400 mt-0.5">Vista general y rendimiento del catálogo de habitaciones.</p>
                </div>

                <div class="flex items-center space-x-2 bg-neutral-900 border border-neutral-800 rounded-full px-3 py-1 w-fit text-xs text-neutral-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sistema en línea</span>
                </div>
            </div>

            <!-- GRID DE INDICADORES MEJORADOS (SHADCN CARD STYLE) -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                
                
                <div class="p-6 bg-neutral-900 border border-neutral-800 rounded-xl relative overflow-hidden group hover:border-neutral-700 transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-neutral-400 tracking-tight">Total Habitaciones</span>
                        
                        <div class="p-2 bg-neutral-950 border border-neutral-800 rounded-lg text-neutral-400 group-hover:text-neutral-100 transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 space-y-1">
                        <span class="text-4xl font-extrabold tracking-tight text-white">{{ totalHabitaciones }}</span>
                        <p class="text-xs text-neutral-500 font-medium">Unidades activas en el inventario</p>
                    </div>
                </div>

                <!-- Tarjeta: Tipos de Habitación -->
                <div class="p-6 bg-neutral-900 border border-neutral-800 rounded-xl relative overflow-hidden group hover:border-neutral-700 transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-neutral-400 tracking-tight">Categorías</span>
                        
                        <div class="p-2 bg-neutral-950 border border-neutral-800 rounded-lg text-neutral-400 group-hover:text-neutral-100 transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="9" rx="1"/>
                                <rect x="14" y="3" width="7" height="5" rx="1"/>
                                <rect x="14" y="12" width="7" height="9" rx="1"/>
                                <rect x="3" y="16" width="7" height="5" rx="1"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 space-y-1">
                        <span class="text-4xl font-extrabold tracking-tight text-white">{{ totalTipos }}</span>
                        <p class="text-xs text-neutral-500 font-medium">Clasificaciones comerciales</p>
                    </div>
                </div>

                <!-- Tarjeta: Precio Promedio -->
                <div class="p-6 bg-neutral-900 border border-neutral-800 rounded-xl relative overflow-hidden group hover:border-neutral-700 transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-neutral-400 tracking-tight">Valor Promedio</span>
                        <!-- Icono SVG Elegante de Shadcn (💰 Reemplazado) -->
                        <div class="p-2 bg-neutral-950 border border-neutral-800 rounded-lg text-neutral-400 group-hover:text-neutral-100 transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"/>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 space-y-1">
                        <span class="text-4xl font-extrabold tracking-tight text-white">${{ precioPromedio }}</span>
                        <p class="text-xs text-neutral-500 font-medium">Tarifa media por noche</p>
                    </div>
                </div>

            </div>

            <!-- SECCIÓN INFERIOR ENRIQUECIDA (MÓDULOS INFORMATIVOS RECIENTES) -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-5">
                
                <!-- Estado del Hotel simulado (3/5 de ancho) -->
                <div class="p-6 bg-neutral-900 border border-neutral-800 rounded-xl md:col-span-3 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-neutral-200 tracking-tight">Distribución del Inventario</h3>
                        <span class="text-xs text-neutral-500 font-medium">Métricas de ocupación simuladas</span>
                    </div>
                    <div class="space-y-3 pt-2">
                        <!-- Barra 1: Disponibles -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-neutral-400">
                                <span>Habitaciones Operativas</span>
                                <span class="font-medium text-neutral-300">100%</span>
                            </div>
                            <div class="w-full bg-neutral-950 rounded-full h-2 border border-neutral-800 overflow-hidden">
                                <div class="bg-white h-full w-full rounded-full transition-all duration-500"></div>
                            </div>
                        </div>
                        <!-- Barra 2: Mantenimiento -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs text-neutral-400">
                                <span>Mantenimiento o Limpieza</span>
                                <span class="font-medium text-neutral-300">0%</span>
                            </div>
                            <div class="w-full bg-neutral-950 rounded-full h-2 border border-neutral-800 overflow-hidden">
                                <div class="bg-neutral-800 h-full w-0 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actividad Reciente simulada (2/5 de ancho) -->
                <div class="p-6 bg-neutral-900 border border-neutral-800 rounded-xl md:col-span-2 space-y-4">
                    <h3 class="text-base font-semibold text-neutral-200 tracking-tight">Actividad del Sistema</h3>
                    
                    <div class="space-y-3.5 text-xs">
                        <div class="flex items-start space-x-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-400 mt-1.5 shrink-0"></div>
                            <div>
                                <p class="text-neutral-300 font-medium">Base de datos conectada con éxito</p>
                                <p class="text-[10px] text-neutral-500 mt-0.5">Hace un momento</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-purple-400 mt-1.5 shrink-0"></div>
                            <div>
                                <p class="text-neutral-300 font-medium">Catálogo de habitaciones sincronizado</p>
                                <p class="text-[10px] text-neutral-500 mt-0.5">Hace 5 minutos</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </AppSidebar>
</template>