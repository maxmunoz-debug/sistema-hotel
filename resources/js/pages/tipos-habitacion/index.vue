<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AppSidebar from '@/components/AppSidebar.vue';
import ModalConfirmacion from '@/components/ModalConfirmacion.vue';
import { toast } from 'vue-sonner'; // <-- Importamos los toasts

defineProps({
    tipos: Array
});

const editando = ref(false);
const idSeleccionado = ref(null);

// Variables para controlar el modal de confirmación
const mostrarModalEliminar = ref(false);
const idParaEliminar = ref(null);

const form = useForm({
    nombre: '',
    descripcion: ''
});

const guardarTipo = () => {
    if (editando.value) {
        form.put(`/tipos-habitacion/${idSeleccionado.value}`, {
            onSuccess: () => {
                limpiarFormulario();
                toast.success('Categoría actualizada con éxito.');
            },
            onError: () => {
                toast.error('Error al actualizar la categoría.');
            }
        });
    } else {
        form.post('/tipos-habitacion', {
            onSuccess: () => {
                limpiarFormulario();
                toast.success('Categoría guardada con éxito.');
            },
            onError: () => {
                toast.error('Error al guardar la categoría.');
            }
        });
    }
};

const prepararEditar = (tipo) => {
    editando.value = true;
    idSeleccionado.value = tipo.id;
    form.nombre = tipo.nombre;
    form.descripcion = tipo.descripcion;
};

const abrirModalEliminar = (id) => {
    idParaEliminar.value = id;
    mostrarModalEliminar.value = true;
};

const confirmarEliminacion = () => {
    if (idParaEliminar.value) {
        form.delete(`/tipos-habitacion/${idParaEliminar.value}`, {
            onSuccess: () => {
                mostrarModalEliminar.value = false;
                idParaEliminar.value = null;
                toast.success('Categoría eliminada con éxito.');
            },
            onError: (errors) => {
                mostrarModalEliminar.value = false;
                idParaEliminar.value = null;
                if (errors.error) {
                    toast.error(errors.error);
                } else {
                    toast.error('No se pudo eliminar la categoría.');
                }
            }
        });
    }
};

const limpiarFormulario = () => {
    form.reset();
    editando.value = false;
    idSeleccionado.value = null;
};
</script>

<template>
    <Head title="Tipos de Habitación" />

    <AppSidebar>
        <div class="p-6 space-y-6 text-white bg-neutral-950 min-h-screen">
            <!-- Encabezado -->
            <div class="flex items-center justify-between border-b border-neutral-800 pb-4">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">Categorías de Habitación</h2>
                    <p class="text-xs text-neutral-400 mt-1">Configura las clasificaciones y descripciones para tu catálogo.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                
                <!-- FORMULARIO IZQUIERDO -->
                <div class="p-6 bg-neutral-900 border border-neutral-800 rounded-xl h-fit sticky top-6">
                    <h3 class="mb-4 text-base font-medium text-neutral-200">
                        {{ editando ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h3>

                    <form @submit.prevent="guardarTipo" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-400">Nombre de la Categoría</label>
                            <input 
                                v-model="form.nombre" 
                                type="text" 
                                class="w-full mt-1 bg-neutral-950 border-neutral-800 rounded-lg text-sm text-neutral-200 focus:border-neutral-700 focus:ring-0 transition"
                                required
                                placeholder="Ej: Suite Presidencial, Doble Estándar"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-400">Descripción o Beneficios</label>
                            <textarea 
                                v-model="form.descripcion" 
                                rows="4"
                                class="w-full mt-1 bg-neutral-950 border-neutral-800 rounded-lg text-sm text-neutral-200 focus:border-neutral-700 focus:ring-0 transition resize-none"
                                placeholder="Detalles de la habitación (ej: Cama King, Jacuzzi, Vista al mar...)"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-2 pt-2">
                            <button 
                                v-if="editando"
                                @click="limpiarFormulario" 
                                type="button"
                                class="px-3 py-1.5 text-xs font-medium text-neutral-400 hover:text-neutral-200 transition"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="px-4 py-1.5 text-xs font-medium text-black bg-white rounded-lg hover:bg-neutral-200 disabled:opacity-50 transition active:scale-95"
                            >
                                {{ editando ? 'Actualizar' : 'Guardar Categoría' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- DERECHA: GRID DE TARJETAS CON ICONOS ELEGANTES (SHADCN STYLE) -->
                <div class="lg:col-span-2">
                    <h3 class="mb-4 text-base font-medium text-neutral-200">Categorías Registradas</h3>
                    
                    <div v-if="tipos.length === 0" class="p-12 text-sm text-center text-neutral-500 bg-neutral-900 border border-neutral-800 rounded-xl">
                        No hay categorías registradas todavía. Crea una a la izquierda para empezar.
                    </div>

                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Tarjeta de Tipo de Habitación -->
                        <div 
                            v-for="tipo in tipos" 
                            :key="tipo.id"
                            class="group p-5 bg-neutral-900 border border-neutral-800 rounded-xl flex flex-col justify-between transition-all duration-300 hover:border-neutral-700 hover:shadow-lg hover:-translate-y-0.5"
                        >
                            <div class="space-y-4">
                                <!-- Icono Minimalista Elegante (Cama de Hotel en SVG Fino) -->
                                <div class="flex items-center justify-between">
                                    <div class="p-2.5 bg-neutral-950 border border-neutral-800 rounded-lg text-neutral-400 group-hover:text-white transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 4v16" />
                                            <path d="M2 11h20" />
                                            <path d="M2 17h20" />
                                            <path d="M22 4v16" />
                                            <path d="M16 8H8a2 2 0 0 0-2 2v1" />
                                            <circle cx="12" cy="14" r="2" />
                                        </svg>
                                    </div>
                                    <span class="text-neutral-600 text-xs font-mono">ID: #{{ tipo.id }}</span>
                                </div>

                                <!-- Información -->
                                <div class="space-y-1">
                                    <h4 class="text-base font-semibold tracking-tight text-neutral-100 group-hover:text-white transition">
                                        {{ tipo.nombre }}
                                    </h4>
                                    <p class="text-xs text-neutral-400 leading-relaxed line-clamp-3">
                                        {{ tipo.descripcion || 'Sin descripción detallada para esta categoría.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Botones Inferiores Estilizados -->
                            <div class="flex items-center justify-end space-x-3 pt-4 mt-4 border-t border-neutral-800 text-xs">
                                <button 
                                    @click="prepararEditar(tipo)" 
                                    class="text-neutral-400 hover:text-white transition hover:underline"
                                >
                                    Editar
                                </button>
                                <button 
                                    @click="abrirModalEliminar(tipo.id)" 
                                    class="text-red-400 hover:text-red-300 transition hover:underline"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- Componente Modal de Confirmación -->
        <ModalConfirmacion 
            :mostrar="mostrarModalEliminar"
            titulo="¿Eliminar esta categoría?"
            mensaje="Esta acción eliminará la categoría. Asegúrate de que no tenga habitaciones asociadas, ya que de lo contrario el sistema impedirá su borrado para proteger tus datos."
            @cancelar="mostrarModalEliminar = false"
            @confirmar="confirmarEliminacion"
        />
    </AppSidebar>
</template>