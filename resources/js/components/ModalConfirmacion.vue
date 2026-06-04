<script setup>
defineProps({
    mostrar: Boolean,
    titulo: String,
    mensaje: String
});

defineEmits(['confirmar', 'cancelar']);
</script>

<template>
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="mostrar" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            
            <!-- Contenedor del Modal (Shadcn Alert Dialog Style) -->
            <div class="w-full max-w-md p-6 bg-neutral-900 border border-neutral-800 rounded-xl shadow-2xl scale-100 transition-all">
                
                <h3 class="text-lg font-semibold tracking-tight text-neutral-100">
                    {{ titulo || '¿Estás seguro?' }}
                </h3>
                
                <p class="mt-2 text-sm text-neutral-400">
                    {{ mensaje || 'Esta acción no se puede deshacer de forma sencilla.' }}
                </p>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button 
                        @click="$emit('cancelar')"
                        type="button"
                        class="px-3 py-1.5 text-xs font-medium text-neutral-400 hover:text-neutral-200 transition rounded-lg"
                    >
                        Cancelar
                    </button>
                    <button 
                        @click="$emit('confirmar')"
                        type="button"
                        class="px-4 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-500 transition active:scale-95"
                    >
                        Confirmar
                    </button>
                </div>

            </div>
        </div>
    </Transition>
</template>