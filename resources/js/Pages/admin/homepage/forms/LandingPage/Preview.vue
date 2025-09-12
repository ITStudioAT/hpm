<template>

    <!-- VORSCHAU -->

    <v-row>
        <v-col>
            <its-menu-button title="Vorschau" subtitle="Neues Fenster" icon="mdi-eye" color="primary"
                @click="openPreview" v-if="is_preview_intern" />

            <its-menu-button title="Vorschau" subtitle="schließen" icon="mdi-close" color="warning"
                @click="closePreview" v-if="!is_preview_intern" />

        </v-col>

    </v-row>



    <v-row v-if="is_preview_intern">
        <v-col cols="12" md="8">
            <div class="mb-2 font-weight-medium">Desktop</div>
            <iframe :key="reloadKey" :src="preview_src" title="Example website" loading="lazy"
                style="width:1200px; height:600px; border:1;" referrerpolicy="no-referrer-when-downgrade"
                allow="fullscreen; clipboard-read; clipboard-write" />
        </v-col>

        <v-col cols="12" md="4">
            <div class="mb-2 font-weight-medium">Handy</div>
            <iframe :key="reloadKey" :src="preview_src" title="Example website" loading="lazy"
                style="width:390px; height:600px; border:1;" referrerpolicy="no-referrer-when-downgrade"
                allow="fullscreen; clipboard-read; clipboard-write" />
        </v-col>
    </v-row>
    <v-row v-if="is_preview_intern">
        <v-col cols="12" md="8">
            <div class="mb-2 font-weight-medium">Tablet</div>
            <iframe :key="reloadKey" :src="preview_src" title="Example website" loading="lazy"
                style="width:960px; height:500px; border:1;" referrerpolicy="no-referrer-when-downgrade"
                allow="fullscreen; clipboard-read; clipboard-write" />
        </v-col>
    </v-row>

</template>
<script>
import ItsMenuButton from "@/pages/components/ItsMenuButton.vue";


export default {
    emits: [],
    props: ['index', 'reloadKey'],
    components: { ItsMenuButton },

    async beforeMount() {
        // ---------- Preview ----------
        this.preview_src =
            `/homepage/example/header_and_footer?homepage_id=${this.index.homepage_id}&record_id=${this.index.id}`
    },

    data() {
        return {


            is_preview_intern: true,
            preview_src: null,
            blank_window: null,
        }
    },

    methods: {
        openPreview() {

            if (this.blank_window && !this.blank_window.closed) {
                this.blank_window.close();
            }

            const src =
                "/admin/homepage/landing_page_preview?id=" +
                this.index.homepage_id;

            this.blank_window = window.open(src, "_blank");
            this.is_preview_intern = false;

        },

        closePreview() {

            if (this.blank_window && !this.blank_window.closed) {
                this.blank_window.close();
            }

            this.is_preview_intern = true;

        },
    }
}
</script>