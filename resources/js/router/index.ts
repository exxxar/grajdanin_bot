import {createRouter, createWebHistory} from 'vue-router'

import { defineAsyncComponent } from 'vue'

const MenuPage = defineAsyncComponent(() => import('../Pages/MenuPage.vue'))

const HelpPage = defineAsyncComponent(() => import('../Pages/HelpPage.vue'))
const UserPage = defineAsyncComponent(() => import('../Pages/AdminPages/UserPage.vue'))
const ExcelExportPage = defineAsyncComponent(() => import('../Pages/AdminPages/ExcelExportPage.vue'))
const ChatPage = defineAsyncComponent(() => import('../Pages/ChatPage.vue'))
const AuthPage = defineAsyncComponent(() => import('../Pages/AuthPage.vue'))
const ProfilePage = defineAsyncComponent(() => import('../Pages/ProfilePage.vue'))
const ReportsPage = defineAsyncComponent(() => import('../Pages/ReportsPage.vue'))

const BlockedPage = defineAsyncComponent(() => import('../Pages/BlockedPage.vue'))


const IssuesPage = defineAsyncComponent(() => import('../Pages/AdminPages/IssuesPage.vue'))
const MunicipalityPage = defineAsyncComponent(() => import('../Pages/AdminPages/MunicipalityPage.vue'))
const ReportPage = defineAsyncComponent(() => import('../Pages/AdminPages/ReportPage.vue'))
const VolunteerPage = defineAsyncComponent(() => import('../Pages/AdminPages/VolunteerPage.vue'))
const HelpsPage = defineAsyncComponent(() => import('../Pages/AdminPages/HelpsPage.vue'))


const routes = [
    {
        path: '/auth',
        name: 'AuthPage',
        component: AuthPage,
        meta: {
            hide_menu: true,
        }

    },
    {
        path: '/profile',
        name: 'ProfilePage',
        component: ProfilePage,
    },
    {
        path: '/helps',
        name: 'HelpsPage',
        component: HelpsPage,
    },
    {
        path: '/reports',
        name: 'ReportsPage',
        component: ReportsPage,
    },
    {
        path: '/chat',
        name: 'ChatPage',
        component: ChatPage,
        meta: {
            hide_menu: true,
        }
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
        path: '/admin/reports',
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
    history: createWebHistory('/app/'),
    routes,
})

export default router
