<template>

    <v-card class="mm-bg-folder mm-text-folder mm-border-folder" width="150" height="60">
        <v-card-text class="text-caption d-flex flex-column align-center justify-center h-100 w-100">
            <v-icon class="mm-icon-folder" icon="mdi-arrow-up-left-bold" size="large" />
            <div v-if="parent" :class="is_current ? 'font-weight-bold' : ''">{{ parent.name }}</div>
        </v-card-text>
    </v-card>

</template>


<script>
export default {
    props: ['parent', 'is_current'],

    data() {
        return {

        };
    },

    methods: {

    }

}
</script>
