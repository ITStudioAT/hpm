<template>

    <v-col cols="12" sm="6" md="4" lg="3">
        <v-card tile flat :color="color ?? 'primary'">
            <v-card-title>
                <v-icon :icon="icon" class="mr-2" size="x-small" v-if="icon" />
                {{ title }}
            </v-card-title>
            <v-card-text class="bg-secondary">
                <slot />
            </v-card-text>

        </v-card>

    </v-col>

</template>


<script>
export default {
    props: ['title', 'subtitle', 'icon', 'color'],

    data() {
        return {

        };
    },

    methods: {

    }

}
</script>
