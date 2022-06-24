<template>
    <table-lite
        :columns="columns"
        :rows="rows"
        :total="totalCount"
        :sortable="sortable"
        :page-options="[
            { value: 10, text: 10 },
            { value: 50, text: 50 },
            { value: 100, text: 100 },
        ]"
        :is-loading="isLoading"
        @row-clicked="show"
        @is-finished="emitLoadingFinish"
        @do-search="searchRows"
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
        totalCount: {
            default: null,
        },
        isLoading: {
            type: Boolean,
            default: true,
        }
    },
    computed: {
        sortable() {
            return {
                order: "id",
                sort: "asc",
            };
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
        emitLoadingFinish(elements) {
            this.$emit("is-finished", elements);
        },
        searchRows(offset, limit, order, sort) {
            const totalPages = Math.ceil(this.totalCount / limit);
            const offsetValue = offset / 10;
            const page = offsetValue + 1;
            const perPage = limit;
            this.$emit("do-search", { page, totalPages, order, sort, perPage });
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
