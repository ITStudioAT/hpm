<!-- components/ItsNotification.vue -->
<template>
    <v-snackbar :timer="notification.type + '-lighten-4'" v-model="notification.show" :timeout="notification.timeout"
        :color="notification.type" min-height="70">

        <div class="d-flex flex-row align-center ga-2">
            <v-icon :icon="icon(notification.type)" />
            <div v-if="notification.status">
                {{ notification.message + ' (' + notification.status + ')' }}
            </div>
            <div v-else>
                {{ notification.message }}
            </div>
        </div>

    </v-snackbar>
</template>

<script>
export default {
    emits: [],
    props: ['notification'],

    data() {
        return {

        };
    },

    methods: {
        icon(type) {
            switch (type) {
                case 'error':
                    return 'mdi-alert-circle-outline';
                case 'success':
                    return 'mdi-check-bold';
                default:
                    return 'mdi-alert-circle-outline';
            }
        }

    }
}
</script>
