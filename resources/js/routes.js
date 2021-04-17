import AllScrip from './components/AllScrip.vue';
import Setup from './components/Setup.vue'
// import CreateProduct from './components/CreateProduct.vue';
// import EditProduct from './components/EditProduct.vue';
 
export const routes = [
    {
        name: 'home',
        path: '/',
        component: AllScrip
    },
    {
        name: 'setup',
        path: '/setup',
        component: Setup
    },
    // {
    //     name: 'create',
    //     path: '/create',
    //     component: CreateProduct
    // },
    // {
    //     name: 'edit',
    //     path: '/edit/:id',
    //     component: EditProduct
    // }
];