import {router} from "@inertiajs/vue3";
import {HttpStatus} from "http-status-ts";
import {type Reactive, type Ref} from "vue";

import api from "@/axios/api";

const refreshProducts = (products: Ref, filterData: Reactive<object>) => {
    const routeName = filterData.only_trashed ? 'api.v1.products.trashed.index' : null;
    getProducts(products, routeName);
};
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
const destroyProduct = (id: number, products: Ref, filerData: Reactive<object>) => {
    api.delete(route('api.v1.products.destroy', id))
        .then(() => {
            refreshProducts(products, filerData);
        });
}
const restoreProduct = (id: number, products: Ref, filerData: Reactive<object>) => {
    api.post(route('api.v1.products.restore', id))
        .then(() => {
            refreshProducts(products, filerData);
        });
}
const forceDestroyProduct = (id: number, products: Ref, filerData: Reactive<object>) => {
    api.delete(route('api.v1.products.force-destroy', id))
        .then(() => {
            refreshProducts(products, filerData);
        });
}
const updateProduct = (product: Ref, errorValidation: Ref) => {
    errorValidation.value = [];
    api.put(route('api.v1.products.update', product.value.id), product.value)
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
        .then(() => {
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
const filterRequest = (filterData: Reactive<object>, url: URL) => {
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
    refreshProducts,
    getProducts,
    getProduct,
    forceDestroyProduct,
    destroyProduct,
    restoreProduct,
    filter,
    storeProduct,
    updateProduct,
}
