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
import { clone, isEmpty } from "../../helpers/Object";
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
            if (this.validateInputs()) {
                this.$emit("save-clicked", this.properties);
            }
        },
        validateInputs() {
            for (var propiedad in this.properties) {
                if (this.properties[propiedad].length === 0) {
                    this.$emit("empty-inputs");
                    return false;
                }
            }
            if (!this.validEmail(this.properties["email"])) {
                this.$emit("invalid-email");
                return false;
            }
            return true;
        },
        validEmail(e) {
            var filter =
                /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;
            return String(e).search(filter) != -1;
        },
    },
    emits: ["save-clicked", "empty-inputs", "invalid-email"],
    computed: {
        properties() {
            if (isEmpty(this.object)) {
                this.object.name = "";
                this.object.email = "";
                this.object.password = "";
            }
            return clone(this.object);
        },
    },
};
</script>

<style></style>
