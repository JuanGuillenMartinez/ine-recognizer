<template>
    <div class="container-title-tab content rounded-3 p-3">
        <h1 class="fs-3 title-tab"><slot/></h1>
    </div>
</template>

<script>
export default {};
</script>

<style scoped>
.title-tab {
    padding-right: 24px;
    margin-bottom: 0px;
    color: white;
}
.container-title-tab.content.rounded-3.p-3 {
    background-color: #2c6095;
    text-align: end;
}
.title-tab {
    font-size: 2.4rem !important;
}
</style>
