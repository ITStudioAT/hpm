<template>
    <!-- NEUE HOMEPAGE -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col cols="12" sm="4" md="3">
            <v-form ref="form" @submit.prevent="doSave(homepage, data)" v-model="is_valid">
                <v-card flat tile color="primary">
                    <v-card-title class="d-flex flex-row align-center justify-space-between">
                        <div v-if="!data?.id">Neue Seite</div>
                        <div v-if="data?.id">Seite ändern</div>
                        <v-btn flat tile variant="tonal" @click="$emit('abort')"><v-icon icon="mdi-close" /></v-btn>
                    </v-card-title>
                    <v-card-text class="pt-4">
                        <v-text-field
                            autofocus
                            v-model="data.name"
                            label="Name der Seite"
                            :rules="[required(), maxLength(255)]" />

                        <v-text-field
                            v-model="data.path"
                            label="Pfad"
                            :rules="[required(), maxLength(255)]"
                            @update:modelValue="data.path = ($event || '').toLowerCase()" />
                    </v-card-text>

                    <v-card-actions class="d-flex flex-row align-center justify-space-between">
                        <v-btn flat tile variant="flat" color="warning" @click="$emit('abort')">Abbruch</v-btn>
                        <v-btn flat tile variant="flat" color="success" type="submit">Speichern</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-col>
    </v-row>
</template>
<script>
import { useValidationRulesSetup } from '@/helpers/rules'
import { usePageStore } from '@/stores/admin/PageStore'

export default {
    setup() {
        return useValidationRulesSetup()
    },
    props: ['data', 'homepage', 'folder'],
    emits: ['save', 'abort'],

    components: {},

    async beforeMount() {
        this.pageStore = usePageStore()
    },

    unmounted() {},

    data() {
        return {
            pageStore: null,
            is_valid: false,
        }
    },

    computed: {},

    methods: {
        async doSave(homepage, data) {
            this.is_valid = false
            await this.$refs.form.validate()
            if (!this.is_valid) return
            let answer = false
            if (data.id) answer = await this.pageStore.update(data)
            else answer = await this.pageStore.store(homepage, data, this.folder)

            if (!answer) return

            await this.pageStore.index(homepage.id)
            this.$emit('save')
        },
    },
}
</script>
