<template>
    <v-row class="w-100 mb-2">
        <v-col cols="12" md="6" lg="4" xl="3">
            <v-card>
                <v-card-title>Kopf- und Fußzeile</v-card-title>
                <v-card-text>
                    <!-- HEADER -->
                    <div>
                        <div>
                            <v-autocomplete v-model="actual_header" :items="headers" item-title="name" item-value="id"
                                label="Wähle einen Header" />
                        </div>
                        <div class="d-flex flex-row align-center justify-space-between">
                            <v-checkbox label="Kopzeile anzeigen" v-model="index.structure.header.is_visible"
                                color="success" hide-details @update:model-value="$emit('update')" width="100%" />


                            <v-btn flat color="primary" :disabled="!index.structure.header.is_visible"
                                @click="$emit('clickAction', 'header')">
                                Bearbeiten
                            </v-btn>
                        </div>

                    </div>

                    <v-divider />
                    <!-- FOOTER -->
                    <div>
                        <div>
                            <v-autocomplete v-model="actual_header" :items="headers" item-title="name" item-value="id"
                                label="Wähle einen Header" />
                        </div>

                        <div class="d-flex flex-row align-center justify-space-between">
                            <v-checkbox v-model="index.structure.footer.is_visible" color="success"
                                label="Fußzeile anzeigen" hide-details @update:model-value="$emit('update')" />
                            <v-btn flat color="primary" :disabled="!index.structure.footer.is_visible"
                                @click="$emit('clickAction', 'footer')">
                                Bearbeiten
                            </v-btn>
                        </div>
                    </div>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
    <v-row>
        {{ headers }}
    </v-row>
</template>
<script>
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/admin/HomepageStore";
export default {

    props: ['index', 'header'],
    emits: ['clickAction', 'update'],

    async beforeMount() {
        this.homepageStore = useHomepageStore();

        await this.homepageStore.loadHeaders(this.index.homepage_id);
    },

    data() {
        return {
            homepageStore: null,
            actual_header: this.header
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["headers"]),
    },


}

</script>