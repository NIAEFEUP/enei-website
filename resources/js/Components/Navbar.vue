<script setup lang="ts">
import NavLink from "@/Components/NavLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownTrigger from "@/Components/DropdownTrigger.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
//import HamburgerMenu from "@/Components/HamburgerMenu.vue";
import route, {
    type QueryParams,
    type RouteParamsWithQueryOverload,
} from "ziggy-js";
//import { usePage } from "@inertiajs/vue3";
//import { OhVueIcon } from "oh-vue-icons";
//import { isAdmin as checkIsAdmin } from "@/Types/User";

interface Route {
    label: string;
    _query?: QueryParams;
}
type Routes = Record<string, Route>;

const homeSections: Routes = {
    /*aboutus: {
        label: "Sobre nós",
    },
    speakers: { label: "Oradores" },
    sponsors: { label: "Patrocínios" },*/
};

const pageRoutes: Routes = {
    /*program: { label: "Programa" },
    "shop.show": { label: "Loja" },
    team: { label: "Equipa" },*/
};

// const editionRoutes = [2022, 2021, 2020, 2019, 2018];

// const { props } = usePage();

// const options = {
//     pages: pageRoutes,
//     competitions: props.competitions,
//     editions: editionRoutes,
// };

// const isAdmin = checkIsAdmin(props.auth.user);
</script>

<template>
    <nav class="flex bg-transparent py-4">
        <Dropdown align="center" width="32" class="ml-10 max-md:hidden">
            <template #trigger>
                <DropdownTrigger class="group">
                    <a :href="route('home')">
                        <img
                            class="w-48 max-md:w-24"
                            src="/images/logo-white.svg"
                            alt="Stylized SINF logo"
                        />
                    </a>
                </DropdownTrigger>
            </template>
            <template #content>
                <template v-for="({ label }, page) in homeSections" :key="page">
                    <DropdownLink
                        :href="page !== 'home' ? `/#${page}` : route(page)"
                    >
                        {{ label }}
                    </DropdownLink>
                </template>
            </template>
        </Dropdown>
        <NavLink :href="route('home')" class="ml-5 md:hidden">
            <img
                class="w-24 max-md:w-24"
                src="/images/logo-white.svg"
                alt="Stylized SINF logo"
            />
        </NavLink>
        <div class="ml-4 hidden w-full min-w-fit md:flex lg:gap-4">
            <template
                v-for="({ label, _query }, page) in pageRoutes"
                :key="page"
            >
                <NavLink
                    :href="
                        route(route().has(page) ? page : 'home', {
                            _query,
                        } as RouteParamsWithQueryOverload)
                    "
                    :active="page === route().current()"
                >
                    {{ label }}
                </NavLink>
            </template>
            <!-- COMPETITIONS DROPDOWN -->
            <!--<Dropdown
                v-if="props.competitions.length > 0"
                align="center"
                width="32"
            >
                <template #trigger>
                    <DropdownTrigger>Competições</DropdownTrigger>
                </template>
                <template #content>
                    <template
                        v-for="competition in props.competitions"
                        :key="competition.id"
                    >
                        <DropdownLink
                            :href="route('competition.show', { competition })"
                        >
                            {{ competition.name }}
                        </DropdownLink>
                    </template>
                </template>
            </Dropdown>-->
        </div>

        <div class="mr-4 flex w-full justify-end">
            <div class="ml-2 flex items-center lg:mx-4">
                <!-- <template v-if="$page.props.auth.user">
                    <Dropdown align="right" :width="isAdmin ? '32' : '20'">
                        <template #trigger>
                            <img
                                class="h-10 w-10 cursor-pointer rounded-full object-cover"
                                :src="$page.props.auth.user.profile_photo_url"
                                :alt="$page.props.auth.user.name"
                            />
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.show')">
                                Perfil
                            </DropdownLink>
                            <DropdownLink
                                v-if="isAdmin"
                                :href="route('admin.index')"
                            >
                                Administração
                            </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post">
                                Logout
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </template>
                <template v-else>
                    <a
                        :href="route('login')"
                        class="bg-enei-blue px-2 py-2 font-space-grotesk font-bold text-enei-beige md:px-4"
                    >
                        Login
                        <OhVueIcon name="io-person" scale="1.7" fill="#025259">
                        </OhVueIcon>
                    </a>
                </template> -->
            </div>

            <!-- <HamburgerMenu :options="options" /> -->
        </div>
    </nav>
</template>
