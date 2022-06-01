<template>
    <form>
        <div class="mb-3">
            <label class="form-label">Petición limitada</label>
            <input
                :readonly="true"
                v-model="properties.request"
                type="text"
                class="form-control"
            />
        </div>
        <div class="mb-3">
            <label class="form-label">Limite</label>
            <input
                :readonly="false"
                v-model="properties.limit"
                type="number"
                class="form-control"
            />
        </div>
        <button
            @click="$emit('save-clicked', properties)"
            type="button"
            class="btn btn-primary"
        >
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
    emits: ["save-clicked"],
    computed: {
        properties() {
            return clone(this.object);
        },
    },
};
</script>

<style></style>
