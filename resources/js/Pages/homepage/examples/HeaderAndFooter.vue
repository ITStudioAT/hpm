<template>
    <v-app-bar :color="header.structure.props.color" :border="header.structure.props.border"
        :density="!header.structure.props.height ? header.structure.props.density : undefined"
        :height="header.structure.props.height >= 24 ? header.structure.props.height : undefined"
        :elevation="header.structure.props.elevation ?? 0" :flat="header.structure.props.flat"
        :scroll-behavior="header.structure.props.scroll_behavior"
        v-if="index && index.structure.header.is_visible && header && header.structure">

        <div class="w-100 h-100 d-block">
            <!-- Zeile 1 -->
            <div class="w-100 d-block" :style="header.structure.rows.count == 2 ? 'height:50%;' : 'height:100%;'">

                <!-- Desktop - Zeile 1-->
                <div class="h-100 " v-if="display.mdAndUp">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_1.desktop.fluid"
                        :max-width="header.structure.rows.row_1.desktop.max_width">
                        <div class="h-100 d-flex flex-row align-center">
                            <div class="h-100 flex-grow-1 d-flex align-center"
                                :class="header.structure.rows.row_1.desktop.columns.col_1.justify">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.desktop.columns.col_1.has_menu" />
                                <!-- BILD -->
                                <div flat v-if="header.structure.rows.row_1.desktop.columns.col_1.has_image">BILD</div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.desktop.columns.col_1.has_text"
                                    :class="header.structure.rows.row_1.desktop.columns.col_1.text_variant">{{
                                        header.structure.rows.row_1.desktop.columns.col_1.text }}</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center"
                                v-if="header.structure.rows.row_1.desktop.columns.count >= 2">
                                <div>SPALTE2</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center "
                                v-if="header.structure.rows.row_1.desktop.columns.count >= 3">
                                <div>SPALTE3</div>
                            </div>
                        </div>
                    </v-container>
                </div>
                <!-- Tablet - Zeile 1-->
                <div class="h-100" v-if="display.sm">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_1.tablet.fluid"
                        :max-width="header.structure.rows.row_1.tablet.max_width">
                        <div class="h-100 d-flex flex-row align-center">
                            <div class="h-100 flex-grow-1 d-flex align-center ">
                                <div>SPALTE1</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center"
                                v-if="header.structure.rows.row_1.tablet.columns.count >= 2">
                                <div>SPALTE2</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center"
                                v-if="header.structure.rows.row_1.tablet.columns.count >= 3">
                                <div>SPALTE3</div>
                            </div>
                        </div>
                    </v-container>
                </div>
                <!-- Handy - Zeile 1-->
                <div class="h-100" v-if="display.xs">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_1.handy.fluid"
                        :max-width="header.structure.rows.row_1.handy.max_width">
                        <div class="h-100  d-flex flex-row align-center">
                            <div class="h-100 flex-grow-1 d-flex align-center">
                                <div>SPALTE1</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center "
                                v-if="header.structure.rows.row_1.handy.columns.count >= 2">
                                <div>SPALTE2</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center"
                                v-if="header.structure.rows.row_1.handy.columns.count >= 3">
                                <div>SPALTE3</div>
                            </div>
                        </div>
                    </v-container>
                </div>

            </div>

            <!-- Zeile 2 -->
            <div class="w-100 d-block" style="height:50%;" v-if="header.structure.rows.count == 2">
                <!-- Desktop - Zeile 2-->
                <div class="h-100 " v-if="display.mdAndUp">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_2.desktop.fluid"
                        :max-width="header.structure.rows.row_2.desktop.max_width">
                        <div class="h-100  d-flex flex-row align-center">
                            <div class="h-100 flex-grow-1 d-flex align-center ">
                                <div>SPALTE1</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center "
                                v-if="header.structure.rows.row_2.desktop.columns.count >= 2">
                                <div>SPALTE2</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center"
                                v-if="header.structure.rows.row_2.desktop.columns.count >= 3">
                                <div>SPALTE3</div>
                            </div>
                        </div>
                    </v-container>
                </div>
                <!-- Tablet - Zeile 2-->
                <div class="h-100" v-if="display.sm">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_2.tablet.fluid"
                        :max-width="header.structure.rows.row_2.tablet.max_width">
                        <div class="h-100  d-flex flex-row align-center">
                            <div class="h-100 flex-grow-1 d-flex align-center">
                                <div>SPALTE1</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center "
                                v-if="header.structure.rows.row_2.tablet.columns.count >= 2">
                                <div>SPALTE2</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center "
                                v-if="header.structure.rows.row_2.tablet.columns.count >= 3">
                                <div>SPALTE3</div>
                            </div>
                        </div>
                    </v-container>
                </div>
                <!-- Handy - Zeile 2-->
                <div class="h-100" v-if="display.xs">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_2.handy.fluid"
                        :max-width="header.structure.rows.row_2.handy.max_width">
                        <div class="h-100 d-flex flex-row align-center">
                            <div class="h-100 flex-grow-1 d-flex align-center">
                                <div>SPALTE1</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center"
                                v-if="header.structure.rows.row_2.handy.columns.count >= 2">
                                <div>SPALTE2</div>
                            </div>
                            <div class="h-100 flex-grow-1 d-flex align-center "
                                v-if="header.structure.rows.row_2.handy.columns.count >= 3">
                                <div>SPALTE3</div>
                            </div>
                        </div>
                    </v-container>
                </div>

            </div>

        </div>
    </v-app-bar>





    <v-main v-if="index" class="h-100 background">
        <v-container class="h-100 main d-flex flex-column align-center justify-center">
            <div class="text-h6 text-md-h4">Homepage</div>
            <div> DISPLAY.xs: {{ display.xs }}</div>
            <div> DISPLAY.sm: {{ display.sm }}</div>
            <div> DISPLAY.mdAndUp: {{ display.mdAndUp }}</div>

        </v-container>

    </v-main>

    <v-footer color="primary" density="comfortable" flat app v-if="index && index.structure.footer.is_visible">
        FOOTER {{ record_id }}
    </v-footer>

</template>
<script>
import { useDisplay } from 'vuetify'
import { mapWritableState } from "pinia";
import { useHomepageStore } from "@/stores/admin/HomepageStore";


export default {
    props: [],
    components: {},

    async beforeMount() {

        this.display = useDisplay();

        this.homepageStore = useHomepageStore();
        this.is_ready = true;

        this.homepage_id = this.$route.query.homepage_id;
        this.record_id = this.$route.query.record_id;

        await this.homepageStore.loadHomepage(this.homepage_id);
        await this.homepageStore.loadRecord(this.homepage_id, this.record_id);
        this.index = JSON.parse(JSON.stringify(this.record));
        const header_id = this.index.structure.header.id;
        const footer_id = this.index.structure.footer.id;

        await this.homepageStore.loadRecord(this.homepage_id, header_id);
        this.header = JSON.parse(JSON.stringify(this.record));

        await this.homepageStore.loadRecord(this.homepage_id, footer_id);
        this.footer = JSON.parse(JSON.stringify(this.record));

    },

    mounted() {

    },

    data() {
        return {
            homepageStore: null,
            is_ready: false,
            homepage_id: null,
            record_id: null,
            index: null,
            header: null,
            footer: null,
            display: null,
        };
    },

    computed: {
        ...mapWritableState(useHomepageStore, ["data", "record"]),

    },

    watch: {
    },

    methods: {


    },
};
</script>
