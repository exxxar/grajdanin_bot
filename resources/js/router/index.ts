import {createRouter, createWebHistory} from 'vue-router'

import { defineAsyncComponent } from 'vue'

const MenuPage = defineAsyncComponent(() => import('../Pages/MenuPage.vue'))

const HelpPage = defineAsyncComponent(() => import('../Pages/HelpPage.vue'))
const UserPage = defineAsyncComponent(() => import('../Pages/AdminPages/UserPage.vue'))
const ExcelExportPage = defineAsyncComponent(() => import('../Pages/AdminPages/ExcelExportPage.vue'))
const DialogsPage = defineAsyncComponent(() => import('../Pages/DialogsPage.vue'))
const ChatPage = defineAsyncComponent(() => import('../Pages/ChatPage.vue'))
const AuthPage = defineAsyncComponent(() => import('../Pages/AuthPage.vue'))
const ProfilePage = defineAsyncComponent(() => import('../Pages/ProfilePage.vue'))
const ReportsPage = defineAsyncComponent(() => import('../Pages/ReportsPage.vue'))

const BlockedPage = defineAsyncComponent(() => import('../Pages/BlockedPage.vue'))


const IssuesPage = defineAsyncComponent(() => import('../Pages/AdminPages/IssuesPage.vue'))
const MunicipalityPage = defineAsyncComponent(() => import('../Pages/AdminPages/MunicipalityPage.vue'))
const ReportPage = defineAsyncComponent(() => import('../Pages/AdminPages/ReportPage.vue'))
const HelpsPage = defineAsyncComponent(() => import('../Pages/AdminPages/HelpsPage.vue'))
const VolunteerPage = defineAsyncComponent(() => import('../Pages/VolunteerPage.vue'))
const IncomingReportPage = defineAsyncComponent(() => import('../Pages/IncomingReportPage.vue'))
const EventRequestPage = defineAsyncComponent(() => import('../Pages/EventRequestPage.vue'))


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
        path: '/volunteer',
        name: 'VolunteerPage',
        component: VolunteerPage,
    },
    {
        path: '/incoming',
        name: 'IncomingReportPage',
        component: IncomingReportPage,
    },
    {
        path: '/event',
        name: 'EventRequestPage',
        component: EventRequestPage,
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
        path: '/chats',
        name: 'DialogsPage',
        component: DialogsPage,
        meta: {
            hide_menu: true,
            hide_footer: true,
        }
    },
    {
        path: '/chats/:id',
        name: 'ChatPage',
        component: ChatPage,
        meta: {
            hide_menu: true,
            hide_footer: true,
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
