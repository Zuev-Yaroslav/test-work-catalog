import api from "@/axios/api";
import {router} from "@inertiajs/vue3";
import {type Reactive, type Ref} from "vue";
import {HttpStatus} from "http-status-ts";

const getProducts = (products: Ref, routeName?) => {
    routeName = routeName || 'api.v1.products.index'
    const params = route().params;
    api.get(route(routeName, params)).then((response) => {
        products.value = response.data;
    });
}
const getProduct = (product: Ref, id: number, errorObject) => {
    api.get(route('api.v1.products.show', id, ))
        .then((response) => {
            product.value = response.data;
        })
        .catch((error) => {
            errorObject.value = error;
        });
}
const destroyProduct = (id: number) => {
    api.delete(route('api.v1.products.destroy', id))
        .then((response) => {
            router.reload( {
                preserveState: true,
                preserveScroll: true,
            })
        });
}
const restoreProduct = (id: number) => {
    api.post(route('api.v1.products.trashed.show.restore', id))
        .then((response) => {
            router.reload( {
                preserveState: true,
                preserveScroll: true,
            })
        });
}
const forceDestroyProduct = (id: number) => {
    api.delete(route('api.v1.products.force-destroy', id))
        .then((response) => {
            router.reload( {
                preserveState: true,
                preserveScroll: true,
            })
        });
}
const updateProduct = (product: Ref, errorValidation: Ref) => {
    errorValidation.value = [];
    api.patch(route('api.v1.products.update', product.value.id), product.value)
        .then((response) => {
            product.value = response.data;
            router.visit(route('admin.products.index'));
        })
        .catch(error => {
            switch (error.status) {
                case HttpStatus.UNPROCESSABLE_ENTITY:
                    errorValidation.value = error.response.data;
                    break;
            }
        })
}

const storeProduct = (product: Reactive<object>, errorValidation: Ref) => {
    errorValidation.value = [];
    api.post(route('api.v1.products.store'), product)
        .then((response) => {
            router.visit(route('admin.products.index'));
        })
        .catch(error => {
            switch (error.status) {
                case HttpStatus.UNPROCESSABLE_ENTITY:
                    errorValidation.value = error.response.data;
                    break;
            }
        })
}
const filterRequest = (filterData: object, url: URL) => {
    Object.entries(filterData).forEach(([key, value]) => {
        if (value) {
            url.searchParams.set(key, value)
        }
    })

    router.visit(url.toString(), {
        preserveState: true,
        preserveScroll: true,
    })
}
let timeoutId: number = 0;
const filter = (filterData: object, event: Event) => {
    clearTimeout(timeoutId);
    filterData.page = null;
    const url = new URL(window.location.href);
    url.search = '';
    if (event.type === 'input') {
        timeoutId = setTimeout(() => filterRequest(filterData, url), 2000)
    } else {
        filterRequest(filterData, url);
    }
}

export {
    getProducts,
    getProduct,
    forceDestroyProduct,
    destroyProduct,
    restoreProduct,
    filter,
    storeProduct,
    updateProduct,
}
