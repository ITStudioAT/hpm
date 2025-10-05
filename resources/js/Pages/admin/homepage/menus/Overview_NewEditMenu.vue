<template>
    <!-- NEUES/EDIT MENÜ -->

    <v-form ref="form" @submit.prevent="doSave(homepage, data)" v-model="is_valid">
        <v-card flat tile color="primary">
            <v-card-title class="d-flex flex-row align-center justify-space-between">
                <div v-if="!data?.id">Neues Menü</div>
                <div v-if="data?.id">Menü ändern</div>
                <v-btn flat tile variant="tonal" @click="$emit('abort')"><v-icon icon="mdi-close" /></v-btn>
            </v-card-title>
            <v-card-text class="pt-4">
                <v-text-field
                    autofocus
                    v-model="data.name"
                    label="Name des Menüs"
                    :rules="[required(), maxLength(255)]" />
            </v-card-text>

            <v-card-actions class="d-flex flex-row align-center justify-space-between">
                <v-btn flat tile variant="flat" color="warning" @click="$emit('abort')">Abbruch</v-btn>
                <v-btn flat tile variant="flat" color="success" type="submit">Speichern</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>
<script>
import { useValidationRulesSetup } from '@/helpers/rules'
import { useMenuStore } from '@/stores/admin/MenuStore'

export default {
    setup() {
        return useValidationRulesSetup()
    },
    props: ['data', 'homepage'],
    emits: ['save', 'abort'],

    components: {},

    async beforeMount() {
        this.menuStore = useMenuStore()
    },

    unmounted() {},

    data() {
        return {
            menuStore: null,
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
            if (data.id) answer = await this.menuStore.update(data)
            else answer = await this.menuStore.store(homepage, data)

            if (!answer) return

            await this.menuStore.index(homepage.id)
            this.$emit('save')
        },
    },
}
</script>
