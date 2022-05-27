<template>
    <button @click="$emit('buttonClicked')" type="button" :class="getClass">
        <i :class="icon"></i>
    </button>
</template>

<script>
export default {
    props: {
        icon: String,
        float: {
            type: Boolean,
            default: true,
        },
        colorClass: {
            type: String,
            default: "btn-success"
        }
    },
    computed: {
        getClass() {
            return `btn ${this.colorClass} ${this.isFloating}`;
        },
        isFloating() {
            return this.float ? "float-button" : "";
        },
    },
    emits: ["buttonClicked"],
};
</script>

<style scoped>
.float-button {
    position: fixed;
    right: 2%;
    bottom: 6%;
}
button {
    border-radius: 50%;
    width: 65px;
    height: 65px;
    font-size: 24px;
}
</style>
