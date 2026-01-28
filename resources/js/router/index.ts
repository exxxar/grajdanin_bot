import {createRouter, createWebHashHistory} from 'vue-router'

import { defineAsyncComponent } from 'vue'

const MenuPage = defineAsyncComponent(() => import('../Pages/MenuPage.vue'))

const HelpPage = defineAsyncComponent(() => import('../Pages/HelpPage.vue'))
const UserPage = defineAsyncComponent(() => import('../Pages/AdminPages/UserPage.vue'))
const ExcelExportPage = defineAsyncComponent(() => import('../Pages/AdminPages/ExcelExportPage.vue'))

const BlockedPage = defineAsyncComponent(() => import('../Pages/BlockedPage.vue'))


const IssuesPage = defineAsyncComponent(() => import('../Pages/AdminPages/IssuesPage.vue'))
const MunicipalityPage = defineAsyncComponent(() => import('../Pages/AdminPages/MunicipalityPage.vue'))
const ReportPage = defineAsyncComponent(() => import('../Pages/AdminPages/ReportPage.vue'))
const VolunteerPage = defineAsyncComponent(() => import('../Pages/AdminPages/VolunteerPage.vue'))
const HelpsPage = defineAsyncComponent(() => import('../Pages/AdminPages/HelpsPage.vue'))

const routes = [
    {
        path: '/helps',
        name: 'HelpsPage',
        component: HelpsPage,
    },
    {
        path: '/issues',
        name: 'IssuesPage',
        component: IssuesPage,
    },
    {
        path: '/municipalities',
        name: 'MunicipalityPage',
        component: MunicipalityPage,
    },
    {
        path: '/reports',
        name: 'ReportPage',
        component: ReportPage,
    },
    {
        path: '/volunteers',
        name: 'VolunteerPage',
        component: VolunteerPage,
    },
    {
        path: '/blocked',
        name: 'BlockedPage',
        component: BlockedPage,
    },
    {
        path: '/',
        name: 'MenuPage',
        component: MenuPage,
    },
    {
        path: '/help',
        name: 'HelpPage',
        component: HelpPage,
    },
    {
        path: '/users',
        name: 'UserPage',
        component: UserPage,
    },
    {
        path: '/excel-export',
        name: 'ExcelExportPage',
        component: ExcelExportPage,
    },

]


const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

export default router
