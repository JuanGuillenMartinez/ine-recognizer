<template>
    <form>
        <div class="mb-3">
            <label class="form-label">Nombre del comercio</label>
            <input
                :readonly="false"
                v-model="properties.name"
                required
                type="text"
                class="form-control"
            />
        </div>
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input
                :readonly="false"
                v-model="properties.email"
                required
                type="email"
                class="form-control"
            />
        </div>
        <div>
            <label class="form-label">Contraseña</label>
            <input
                :readonly="false"
                v-model="properties.password"
                required
                type="password"
                class="form-control"
            />
        </div>
        <div class="form-text mb-3">
            Las credenciales serán enviadas al correo electrónico registrado.
        </div>
        <button @click="emitSaveEvent" type="button" class="btn btn-primary">
            Guardar
        </button>
    </form>
</template>

<script>
import { clone } from "../../helpers/Object";
export default {
    props: {
        object: {
            type: Object,
            default: {},
        },
        readonly: Boolean,
    },
    methods: {
        emitSaveEvent() {
            const isValid = this.validateInputs();
            console.log(isValid);
            // this.$emit("save-clicked", this.properties);
        },
        validateInputs() {
            for (var propiedad in this.properties) {
                console.log(this.properties[propiedad].length);
                if(this.properties[propiedad].length === 0) {
                    return false;
                }
            }
            return true;
        },
    },
    emits: ["save-clicked"],
    computed: {
        properties() {
            return clone(this.object);
        },
    },
};
</script>

<style></style>
