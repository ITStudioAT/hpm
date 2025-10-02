<template>
    <!-- EDIT MENU -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col cols="12" sm="4" md="3">
            <v-card flat tile color="primary">
                <v-card-title class="d-flex flex-row align-center justify-space-between">
                    <div>Menü ändern</div>
                    <v-btn flat tile variant="tonal" @click="$emit('abort')"><v-icon icon="mdi-close" /></v-btn>
                </v-card-title>
                <v-card-text class="pt-4">
                    <v-treeview
                        v-model:activated="activated"
                        :items="items"
                        item-key="id"
                        item-value="id"
                        activatable
                        open-all></v-treeview>
                </v-card-text>

                <v-card-actions class="d-flex flex-row align-center justify-space-between">
                    <v-btn flat tile variant="flat" color="warning" @click="$emit('abort')">Abbruch</v-btn>
                    <v-btn flat tile variant="flat" color="success" type="submit">Speichern</v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
</template>
<script>
import { shallowRef } from 'vue'
export default {
    props: ['active_menu'],
    emits: ['abort'],

    components: {},

    async beforeMount() {
        this.items = shallowRef([...this.root.children])
    },

    unmounted() {},

    data() {
        return {
            root: {
                id: 0,
                children: [
                    {
                        id: 1,
                        title: 'Office Tools',
                        children: [
                            { id: 2, title: 'Calendar' },
                            { id: 3, title: 'Notepad' },
                        ],
                    },
                    {
                        id: 4,
                        title: 'Dev Tools',
                        children: [
                            { id: 5, title: 'VS Code' },
                            { id: 6, title: 'Figma' },
                            { id: 7, title: 'Webstorm' },
                        ],
                    },
                ],
            },
            items: [],
            activated: null,
        }
    },

    computed: {},

    methods: {},
}
</script>
