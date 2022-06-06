<template>
    <form>
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input
                :readonly="readonly"
                v-model="properties.email"
                type="text"
                class="form-control"
            />
        </div>
        <div>
            <label class="form-label">Contraseña asignada</label>
            <input
                :readonly="readonly"
                v-model="properties.password"
                type="text"
                class="form-control"
            />
        </div>
        <div class="form-text mb-3">
            La contraseña mostrada es la utilizada al dar de alta al usuario.
        </div>
        <div class="container-token mb-3">
            <div>
                <label class="form-label">Token</label>
                <input
                    :readonly="readonly"
                    v-model="properties.token"
                    type="text"
                    class="form-control"
                />
            </div>
            <button
                @click="$emit('generateToken', properties)"
                type="button"
                class="btn btn-primary"
            >
                <i class="fa-solid fa-arrows-rotate btn-token"></i>
            </button>
        </div>
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
    emits: ["generateToken"],
    computed: {
        properties() {
            return clone(this.object);
        },
    },
};
</script>

<style scoped>
.container-token {
    display: grid;
    grid-template-columns: 4fr 1fr;
    column-gap: 4px;
}
.btn-token {
    margin-right: 0px; 
    font-size: 32px;
}
</style>
