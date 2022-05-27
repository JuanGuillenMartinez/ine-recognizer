<template>
    <CModal :visible="visible" @close="$emit('closeForm')">
        <CModalHeader>
            <CModalTitle>{{ title }}</CModalTitle>
        </CModalHeader>
        <CModalBody>
            <slot />
        </CModalBody>
        <CModalFooter>
            <CButton color="danger" @click="$emit('closeForm')">
                {{titleClose}}
            </CButton>
        </CModalFooter>
    </CModal>
</template>

<script>
import { CModal } from "@coreui/vue";
import { CModalHeader } from "@coreui/vue";
import { CModalTitle } from "@coreui/vue";
import { CModalFooter } from "@coreui/vue";
import { CButton } from "@coreui/vue";
import { CModalBody } from "@coreui/vue";
import { defineAsyncComponent } from "@vue/runtime-core";
export default {
    components: {
        DocumentInformation: defineAsyncComponent(() =>
            import("@/components/folder/DocumentInformation.vue")
        ),
        CModal,
        CModalHeader,
        CModalTitle,
        CModalFooter,
        CModalBody,
        CButton,
    },
    props: {
        visible: false,
        title: String,
        titleClose: String,
    },
    emits: ["closeForm"],
};
</script>

<style></style>
