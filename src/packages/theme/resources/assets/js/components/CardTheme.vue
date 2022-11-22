<template>

    <marketplace-card :data="theme">
    </marketplace-card>

</template>

<script>

import Card from '@base.marketplace/components/Card/Card.vue';

export default {
    name: "marketplace-card-theme",
    props: {
        theme: []
    },
    components: {
        'marketplace-card': Card
    },
}
</script>
