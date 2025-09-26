<template>
    <!-- NEUE HOMEPAGE -->
    <v-row class="d-flex flex-row ga-2 mb-2 mt-0 w-100" no-gutters>
        <v-col cols="12" sm="4" md="3">
            <v-form ref="form" @submit.prevent="doSave(data)" v-model="is_valid">
                <v-card flat tile color="primary">
                    <v-card-title class="d-flex flex-row align-center justify-space-between">
                        <div v-if="!data?.id">Neue Homepage</div>
                        <div v-if="data?.id">Homepage ändern</div>
                        <v-btn flat tile variant="tonal" @click="$emit('abort')"><v-icon icon="mdi-close" /></v-btn>
                    </v-card-title>
                    <v-card-text class="pt-4">
                        <v-text-field
                            autofocus
                            v-model="data.name"
                            label="Name der Homepage"
                            :rules="[required(), maxLength(255)]" />
                    </v-card-text>

                    <v-card-actions class="d-flex flex-row align-center justify-space-between">
                        <v-btn flat tile variant="flat" color="warning" @click="$emit('abort')">Abbruch</v-btn>
                        <v-btn flat tile variant="flat" color="success" @click="doSave(data)">Speichern</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-col>
    </v-row>
</template>
<script>
import { useValidationRulesSetup } from '@/helpers/rules'
import { useHomepageStore } from '@/stores/admin/HomepageStore'

export default {
    setup() {
        return useValidationRulesSetup()
    },
    props: ['data'],
    emits: ['save', 'abort'],

    components: {},

    async beforeMount() {
        this.homepageStore = useHomepageStore()
    },

    unmounted() {},

    data() {
        return {
            homepageStore: null,
            is_valid: false,
        }
    },

    computed: {},

    methods: {
        async doSave(data) {
            this.is_valid = false
            await this.$refs.form.validate()
            console.log('::', this.is_valid)
            if (!this.is_valid) return
            let answer = false
            if (data.id) answer = await this.homepageStore.update(data)
            else answer = await this.homepageStore.store(data)

            if (!answer) return

            await this.homepageStore.index()
            this.$emit('save')
        },
    },
}
</script>
