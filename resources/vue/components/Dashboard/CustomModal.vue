<template>
    <CModal :visible="visible" @close="$emit('closeForm')">
        <CModalHeader>
            <CModalTitle>{{ title }}</CModalTitle>
        </CModalHeader>
        <CModalBody>
            <slot />
        </CModalBody>
        <CModalFooter>
            <CButton color="danger" @click="$emit('closeModal')">
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
export default {
    components: {
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
    emits: ["closeModal"],
};
</script>

<style></style>
