import { reactive } from "vue";

export const clone = (object) => {
    if (object) {
        return reactive(structuredClone(object));
    }
    return {};
};

export const isEmpty = (object) => {
    const parsed = JSON.stringify(object);
    if(parsed.localeCompare('{}') === 0) {
        return true;
    }
    return false;
}
