<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AppSidebar from '@/components/AppSidebar.vue';
import ModalConfirmacion from '@/components/ModalConfirmacion.vue'; // <-- Importamos el nuevo modal
import { toast } from 'vue-sonner'; // <-- Importamos los toasts

defineProps({
    habitaciones: Array,
    tipos: Array
});

const editando = ref(false);
const idSeleccionado = ref(null);
const urlPrevisualizacion = ref(null);

// Variables de estado para controlar el modal
const mostrarModalEliminar = ref(false);
const idParaEliminar = ref(null);

const form = useForm({
    tipo_habitacion_id: '',
    numero: '',
    precio: '',
    imagen: null,
    _method: 'POST'
});

const manejarImagen = (e) => {
    const archivo = e.target.files[0];
    if (archivo) {
        form.imagen = archivo;
        urlPrevisualizacion.value = URL.createObjectURL(archivo);
    }
};

const guardarHabitacion = () => {
    if (editando.value) {
        form.formData = true; 
        form.post(`/habitaciones/${idSeleccionado.value}`, {
            onSuccess: () => {
                limpiarFormulario();
                toast.success('Habitación actualizada con éxito.');
            },
            onError: () => {
                toast.error('Error al actualizar la habitación. Verifica los datos.');
            }
        });
    } else {
        form.post('/habitaciones', {
            onSuccess: () => {
                limpiarFormulario();
                toast.success('Habitación guardada con éxito.');
            },
            onError: () => {
                toast.error('Error al guardar la habitación. Verifica los datos.');
            }
        });
    }
};

const prepararEditar = (habitacion) => {
    editando.value = true;
    idSeleccionado.value = habitacion.id;
    form.tipo_habitacion_id = habitacion.tipo_habitacion_id;
    form.numero = habitacion.numero;
    form.precio = habitacion.precio;
    form._method = 'PUT';
    
    if (habitacion.imagen) {
        urlPrevisualizacion.value = `/storage/${habitacion.imagen}`;
    } else {
        urlPrevisualizacion.value = null;
    }
};

// Abre nuestro modal personalizado en vez del confirm nativo
const abrirModalEliminar = (id) => {
    idParaEliminar.value = id;
    mostrarModalEliminar.value = true;
};

// Se ejecuta cuando el usuario presiona "Confirmar" en el modal
const confirmarEliminacion = () => {
    if (idParaEliminar.value) {
        form.delete(`/habitaciones/${idParaEliminar.value}`, {
            onSuccess: () => {
                mostrarModalEliminar.value = false;
                idParaEliminar.value = null;
                toast.success('Habitación eliminada del catálogo.');
            },
            onError: () => {
                mostrarModalEliminar.value = false;
                idParaEliminar.value = null;
                toast.error('No se pudo eliminar la habitación.');
            }
        });
    }
};

const limpiarFormulario = () => {
    form.reset();
    form._method = 'POST';
    editando.value = false;
    idSeleccionado.value = null;
    urlPrevisualizacion.value = null;
    const fileInput = document.getElementById('input-imagen');
    if (fileInput) fileInput.value = '';
};
</script>

<template>
    <Head title="Habitaciones" />

    <AppSidebar>
        <div class="p-6 space-y-6 text-white bg-neutral-950 min-h-screen">
            <div class="flex items-center justify-between border-b border-neutral-800 pb-4">
                <h2 class="text-xl font-semibold tracking-tight">Catálogo de Habitaciones</h2>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                
                <!-- FORMULARIO IZQUIERDO -->
                <div class="p-6 bg-neutral-900 border border-neutral-800 rounded-xl h-fit sticky top-6">
                    <h3 class="mb-4 text-base font-medium text-neutral-200">
                        {{ editando ? 'Editar Detalles' : 'Añadir Nueva Habitación' }}
                    </h3>

                    <form @submit.prevent="guardarHabitacion" class="space-y-4" enctype="multipart/form-data">
                        <div>
                            <label class="block text-sm font-medium text-neutral-400">Tipo de Habitación</label>
                            <select 
                                v-model="form.tipo_habitacion_id"
                                class="w-full mt-1 bg-neutral-950 border-neutral-800 rounded-lg text-sm text-neutral-200 focus:border-neutral-700 focus:ring-0 transition"
                                required
                            >
                                <option value="" disabled>Seleccione un tipo...</option>
                                <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">
                                    {{ tipo.nombre }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-400">Número de Habitación</label>
                            <input 
                                v-model="form.numero" 
                                type="text" 
                                class="w-full mt-1 bg-neutral-950 border-neutral-800 rounded-lg text-sm text-neutral-200 focus:border-neutral-700 focus:ring-0 transition"
                                required
                                placeholder="Ej: 104"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-400">Precio por Noche ($)</label>
                            <input 
                                v-model="form.precio" 
                                type="number" 
                                step="0.01"
                                class="w-full mt-1 bg-neutral-950 border-neutral-800 rounded-lg text-sm text-neutral-200 focus:border-neutral-700 focus:ring-0 transition"
                                required
                                placeholder="Ej: 85.00"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-neutral-400">Foto de la Habitación</label>
                            <input 
                                id="input-imagen"
                                type="file" 
                                accept="image/*"
                                @change="manejarImagen"
                                class="w-full mt-1 text-xs text-neutral-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-neutral-800 file:text-neutral-200 hover:file:bg-neutral-700 cursor-pointer transition"
                            />
                            
                            <div v-if="urlPrevisualizacion" class="mt-3 overflow-hidden rounded-lg border border-neutral-800">
                                <img :src="urlPrevisualizacion" class="object-cover w-full h-28" />
                            </div>
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
                                {{ editando ? 'Actualizar' : 'Guardar Habitación' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- DERECHA: GRID DE TARJETAS -->
                <div class="lg:col-span-2">
                    <h3 class="mb-4 text-base font-medium text-neutral-200">Habitaciones en el Catálogo</h3>
                    
                    <div v-if="habitaciones.length === 0" class="p-12 text-sm text-center text-neutral-500 bg-neutral-900 border border-neutral-800 rounded-xl">
                        No hay habitaciones registradas. ¡Usa el formulario para añadir la primera!
                    </div>

                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div 
                            v-for="hab in habitaciones" 
                            :key="hab.id"
                            class="group relative overflow-hidden bg-neutral-900 border border-neutral-800 rounded-xl transition-all duration-300 hover:border-neutral-700 hover:shadow-lg hover:-translate-y-1"
                        >
                            <div class="relative h-44 overflow-hidden bg-neutral-950">
                                <img 
                                    :src="hab.imagen ? `/storage/${hab.imagen}` : 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=600&auto=format&fit=crop'" 
                                    class="object-cover w-full h-full transition-transform duration-500 ease-out group-hover:scale-105"
                                    alt="Habitación"
                                />
                                <div class="absolute bottom-3 right-3 px-2.5 py-1 text-xs font-bold bg-neutral-950/80 backdrop-blur-md rounded-lg border border-neutral-800 text-white">
                                    ${{ hab.precio }} / noche
                                </div>
                            </div>

                            <div class="p-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-lg font-semibold tracking-tight text-neutral-100">
                                        Habitación {{ hab.numero }}
                                    </h4>
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-neutral-800 text-neutral-300 border border-neutral-700">
                                        {{ hab.tipo_habitacion?.nombre || 'Sin tipo' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-end space-x-3 pt-2 border-t border-neutral-800 text-xs">
                                    <button 
                                        @click="prepararEditar(hab)" 
                                        class="text-neutral-400 hover:text-white transition hover:underline"
                                    >
                                        Editar
                                    </button>
                                    <!-- Cambiamos para llamar a nuestro nuevo modal -->
                                    <button 
                                        @click="abrirModalEliminar(hab.id)" 
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
        </div>

        <!-- PASO FINAL: AGREGAMOS EL COMPONENTE MODAL AL FINAL DEL TEMPLATE -->
        <ModalConfirmacion 
            :mostrar="mostrarModalEliminar"
            titulo="¿Retirar del catálogo?"
            mensaje="¿Estás completamente seguro de eliminar esta habitación? Los clientes ya no podrán verla ni reservarla."
            @cancelar="mostrarModalEliminar = false"
            @confirmar="confirmarEliminacion"
        />
    </AppSidebar>
</template>