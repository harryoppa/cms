<template>
    <marketplace-layout v-slot:default="props">
        <marketplace-card-theme v-for="theme in props.list.data" :key="theme.id" :theme="theme"></marketplace-card-theme>
    </marketplace-layout>
</template>

<script>

import Layout from '@base.marketplace/components/Layout.vue';

export default {
    components: {
        'marketplace-layout': Layout,
    },
    methods: {
    }
}
</script>
