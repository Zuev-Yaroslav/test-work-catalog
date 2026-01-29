import api from "@/axios/api";
import {type Ref} from "vue";

const getCategories = (categories: Ref) => {
    api.get(route('api.v1.categories.index')).then((response) => {
        categories.value = response.data.data;
    })
}

export {
    getCategories,
}
