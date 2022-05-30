import { reactive } from "vue";

export const clone = (object) => {
    if (object) {
        // return reactive(structuredClone(object));
        const copy = JSON.parse(JSON.stringify(object));
        return reactive(copy);
    }
    return {};
    console.log(object);
    return object;
};

export const isEmpty = (object) => {
    const parsed = JSON.stringify(object);
    if (parsed.localeCompare("{}") === 0) {
        return true;
    }
    return false;
};
