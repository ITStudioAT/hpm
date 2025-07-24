<template>
    <v-container fluid class="h-100 w-100 d-flex align-center justify-center">
        <v-card class="mx-auto" width="300">
            <v-img
                height="100px"
                :src="'/storage/images/' + config.logo"
            ></v-img>

            <v-card-title>
                {{ config.title }}
            </v-card-title>

            <v-card-subtitle>
                {{ config.company }}
            </v-card-subtitle>

            <v-card-actions>
                <v-btn
                    append-icon="mdi-arrow-right"
                    color="primary"
                    slim
                    flat
                    rounded="0"
                    variant="text"
                    text="Admin"
                    href="/admin"
                />

                <v-spacer></v-spacer>

                <v-btn
                    :icon="
                        is_more_content ? 'mdi-chevron-up' : 'mdi-chevron-down'
                    "
                    @click="is_more_content = !is_more_content"
                ></v-btn>
            </v-card-actions>

            <v-expand-transition>
                <div v-show="is_more_content">
                    <v-divider></v-divider>

                    <v-card-text class="text-text">
                        Hier entsteht ein HomepageMaker, sozusagen ein Content
                        Management System. Im Vordergrund steht dabei Effizienz.
                    </v-card-text>
                </div>
            </v-expand-transition>
        </v-card>
    </v-container>
</template>
<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/homepage/HomepageStore";
export default {
    components: {},

    async beforeMount() {
        this.homepageStore = useHomepageStore();
    },

    unmounted() {},

    data() {
        return {
            homepageStore: null,
            is_more_content: false,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, [
            "config",
            "is_loading",
            "error",
        ]),
    },

    methods: {},
};
</script>
