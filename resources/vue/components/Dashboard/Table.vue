<template>
    <table-lite
        :is-static-mode="true"
        :columns="columns"
        :rows="rows"
        :total="totalCount"
        :sortable="sortable"
        :page-options="quantity"
        @row-clicked="show"
        @is-finished="emitLoadingFinish"
    ></table-lite>
</template>

<script>
export default {
    props: {
        columns: Array,
        rows: {
            type: Array,
            default: [],
        },
        quantityShow: {
            type: Number,
            default: 10,
        },
        total: {
            default: null,
        },
    },
    computed: {
        sortable() {
            return {
                order: "id",
                sort: "asc",
            };
        },
        totalCount() {
            if (this.total === null) {
                return this.rows.length;
            }
            return this.total;
        },
        quantity() {
            return [
                {
                    value: this.quantityShow,
                    text: this.quantityShow,
                },
            ];
        },
    },
    methods: {
        show(selected) {
            this.$emit("row-selected", selected);
        },
        changePage(offset) {
            console.log("cambio");
            console.log(offset);
            // this.$emit('doSearch', offset)
        },
        emitLoadingFinish(elements) {
            this.$emit("is-finished", elements);
        },
    },
    emits: ["row-selected", "do-search", "is-finished"],
};
</script>

<style scoped>
* {
    text-align: center;
}
</style>
