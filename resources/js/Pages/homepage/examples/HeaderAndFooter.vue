<template>
    <v-app-bar :color="header.structure.props.color" :border="header.structure.props.border"
        :density="!header.structure.props.height ? header.structure.props.density : undefined"
        :height="header.structure.props.height >= 24 ? header.structure.props.height : undefined"
        :elevation="header.structure.props.elevation ?? 0" :flat="header.structure.props.flat"
        :scroll-behavior="header.structure.props.scroll_behavior"
        v-if="index && index.structure.header.is_visible && header && header.structure">

        <div class="w-100 h-100 d-block">
            <!-- Zeile 1 -->
            <div class="w-100 d-block" :style="headerStyle(header, 1)">

                <!-- Desktop - Zeile 1 -->
                <div class="h-100" v-if="display.mdAndUp" :class="lineClass(header, 'desktop', 1)">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_1.desktop.fluid"
                        :max-width="header.structure.rows.row_1.desktop.max_width">
                        <div class="h-100 d-flex flex-row align-center">
                            <!-- Spalte 1 - Desktop - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_1.desktop.columns.col_1.justify">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.desktop.columns.col_1.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_1.desktop.columns.col_1.has_image">

                                    <img :height="header.structure.rows.row_1.desktop.columns.col_1.image_height"
                                        :width="header.structure.rows.row_1.desktop.columns.col_1.image_width"
                                        :src="header.structure.rows.row_1.desktop.columns.col_1.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.desktop.columns.col_1.has_text"
                                    class="ml-2 text-pre"
                                    :class="header.structure.rows.row_1.desktop.columns.col_1.text_variant">{{
                                        header.structure.rows.row_1.desktop.columns.col_1.text }}</div>
                            </div>

                            <!-- Spalte 2 - Desktop - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_1.desktop.columns.col_2.justify"
                                v-if="header.structure.rows.row_1.desktop.columns.count >= 2">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.desktop.columns.col_2.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_1.desktop.columns.col_2.has_image">

                                    <img :height="header.structure.rows.row_1.desktop.columns.col_2.image_height"
                                        :width="header.structure.rows.row_1.desktop.columns.col_2.image_width"
                                        :src="header.structure.rows.row_1.desktop.columns.col_2.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.desktop.columns.col_2.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_1.desktop.columns.col_2.text_variant">{{
                                        header.structure.rows.row_1.desktop.columns.col_2.text }}</div>
                            </div>

                            <!-- Spalte 3 - Desktop - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_1.desktop.columns.col_3.justify"
                                v-if="header.structure.rows.row_1.desktop.columns.count >= 3">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.desktop.columns.col_3.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_1.desktop.columns.col_3.has_image">

                                    <img :height="header.structure.rows.row_1.desktop.columns.col_3.image_height"
                                        :width="header.structure.rows.row_1.desktop.columns.col_3.image_width"
                                        :src="header.structure.rows.row_1.desktop.columns.col_3.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.desktop.columns.col_3.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_1.desktop.columns.col_3.text_variant">{{
                                        header.structure.rows.row_1.desktop.columns.col_3.text }}</div>
                            </div>

                        </div>
                    </v-container>
                </div>

                <!-- Tablet - Zeile 1 -->
                <div class="h-100 " v-if="display.sm" :class="lineClass(header, 'tablet', 1)">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_1.tablet.fluid"
                        :max-width="header.structure.rows.row_1.tablet.max_width">
                        <div class="h-100 d-flex flex-row align-center">

                            <!-- Spalte 1 - tablet - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_1.tablet.columns.col_1.justify">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.tablet.columns.col_1.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_1.tablet.columns.col_1.has_image">

                                    <img :height="header.structure.rows.row_1.tablet.columns.col_1.image_height"
                                        :width="header.structure.rows.row_1.tablet.columns.col_1.image_width"
                                        :src="header.structure.rows.row_1.tablet.columns.col_1.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.tablet.columns.col_1.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_1.tablet.columns.col_1.text_variant">{{
                                        header.structure.rows.row_1.tablet.columns.col_1.text }}</div>
                            </div>

                            <!-- Spalte 2 - tablet - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_1.tablet.columns.col_2.justify"
                                v-if="header.structure.rows.row_1.tablet.columns.count >= 2">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.tablet.columns.col_2.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_1.tablet.columns.col_2.has_image">

                                    <img :height="header.structure.rows.row_1.tablet.columns.col_2.image_height"
                                        :width="header.structure.rows.row_1.tablet.columns.col_2.image_width"
                                        :src="header.structure.rows.row_1.tablet.columns.col_2.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.tablet.columns.col_2.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_1.tablet.columns.col_2.text_variant">{{
                                        header.structure.rows.row_1.tablet.columns.col_2.text }}</div>
                            </div>

                            <!-- Spalte 3 - tablet - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_1.tablet.columns.col_3.justify"
                                v-if="header.structure.rows.row_1.tablet.columns.count >= 3">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.tablet.columns.col_3.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_1.tablet.columns.col_3.has_image">

                                    <img :height="header.structure.rows.row_1.tablet.columns.col_3.image_height"
                                        :width="header.structure.rows.row_1.tablet.columns.col_3.image_width"
                                        :src="header.structure.rows.row_1.tablet.columns.col_3.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.tablet.columns.col_3.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_1.tablet.columns.col_3.text_variant">{{
                                        header.structure.rows.row_1.tablet.columns.col_3.text }}</div>
                            </div>

                        </div>
                    </v-container>
                </div>

                <!-- Handy - Zeile 1 -->
                <div class="h-100 " v-if="display.xs" :class="lineClass(header, 'handy', 1)">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_1.handy.fluid"
                        :max-width="header.structure.rows.row_1.handy.max_width">
                        <div class="h-100 d-flex flex-row align-center">
                            <!-- Spalte 1 - handy - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_1.handy.columns.col_1.justify">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.handy.columns.col_1.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_1.handy.columns.col_1.has_image">

                                    <img :height="header.structure.rows.row_1.handy.columns.col_1.image_height"
                                        :width="header.structure.rows.row_1.handy.columns.col_1.image_width"
                                        :src="header.structure.rows.row_1.handy.columns.col_1.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.handy.columns.col_1.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_1.handy.columns.col_1.text_variant">{{
                                        header.structure.rows.row_1.handy.columns.col_1.text }}</div>
                            </div>

                            <!-- Spalte 2 - handy - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_1.handy.columns.col_2.justify"
                                v-if="header.structure.rows.row_1.handy.columns.count >= 2">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.handy.columns.col_2.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_1.handy.columns.col_2.has_image">

                                    <img :height="header.structure.rows.row_1.handy.columns.col_2.image_height"
                                        :width="header.structure.rows.row_1.handy.columns.col_2.image_width"
                                        :src="header.structure.rows.row_1.handy.columns.col_2.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.handy.columns.col_2.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_1.handy.columns.col_2.text_variant">{{
                                        header.structure.rows.row_1.handy.columns.col_2.text }}</div>
                            </div>

                            <!-- Spalte 3 - handy - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_1.handy.columns.col_3.justify"
                                v-if="header.structure.rows.row_1.handy.columns.count >= 3">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_1.handy.columns.col_3.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_1.handy.columns.col_3.has_image">

                                    <img :height="header.structure.rows.row_1.handy.columns.col_3.image_height"
                                        :width="header.structure.rows.row_1.handy.columns.col_3.image_width"
                                        :src="header.structure.rows.row_1.handy.columns.col_3.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_1.handy.columns.col_3.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_1.handy.columns.col_3.text_variant">{{
                                        header.structure.rows.row_1.handy.columns.col_3.text }}</div>
                            </div>

                        </div>
                    </v-container>
                </div>

            </div>

            <!-- Zeile 2 -->
            <div class="w-100 d-block" :style="headerStyle(header, 2)" v-if="header.structure.rows.count == 2">

                <!-- Desktop - Zeile 2 -->
                <div class="h-100 " v-if="display.mdAndUp" :class="lineClass(header, 'desktop', 2)">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_2.desktop.fluid"
                        :max-width="header.structure.rows.row_2.desktop.max_width">
                        <div class="h-100 d-flex flex-row align-center">

                            <!-- Spalte 1 - Desktop - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_2.desktop.columns.col_1.justify">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_2.desktop.columns.col_1.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_2.desktop.columns.col_1.has_image">

                                    <img :height="header.structure.rows.row_2.desktop.columns.col_1.image_height"
                                        :width="header.structure.rows.row_2.desktop.columns.col_1.image_width"
                                        :src="header.structure.rows.row_2.desktop.columns.col_1.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_2.desktop.columns.col_1.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_2.desktop.columns.col_1.text_variant">{{
                                        header.structure.rows.row_2.desktop.columns.col_1.text }}</div>
                            </div>

                            <!-- Spalte 2 - Desktop - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_2.desktop.columns.col_2.justify"
                                v-if="header.structure.rows.row_2.desktop.columns.count >= 2">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_2.desktop.columns.col_2.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_2.desktop.columns.col_2.has_image">

                                    <img :height="header.structure.rows.row_2.desktop.columns.col_2.image_height"
                                        :width="header.structure.rows.row_2.desktop.columns.col_2.image_width"
                                        :src="header.structure.rows.row_2.desktop.columns.col_2.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_2.desktop.columns.col_2.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_2.desktop.columns.col_2.text_variant">{{
                                        header.structure.rows.row_2.desktop.columns.col_2.text }}</div>
                            </div>

                            <!-- Spalte 3 - Desktop - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_2.desktop.columns.col_3.justify"
                                v-if="header.structure.rows.row_2.desktop.columns.count >= 3">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_2.desktop.columns.col_3.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_2.desktop.columns.col_3.has_image">

                                    <img :height="header.structure.rows.row_2.desktop.columns.col_3.image_height"
                                        :width="header.structure.rows.row_2.desktop.columns.col_3.image_width"
                                        :src="header.structure.rows.row_2.desktop.columns.col_3.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_2.desktop.columns.col_3.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_2.desktop.columns.col_3.text_variant">{{
                                        header.structure.rows.row_2.desktop.columns.col_3.text }}</div>
                            </div>

                        </div>
                    </v-container>
                </div>

                <!-- Tablet - Zeile 2 -->
                <div class="h-100 " v-if="display.sm" :class="lineClass(header, 'tablet', 2)">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_2.tablet.fluid"
                        :max-width="header.structure.rows.row_2.tablet.max_width">
                        <div class="h-100 d-flex flex-row align-center">

                            <!-- Spalte 1 - tablet - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_2.tablet.columns.col_1.justify">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_2.tablet.columns.col_1.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_2.tablet.columns.col_1.has_image">

                                    <img :height="header.structure.rows.row_2.tablet.columns.col_1.image_height"
                                        :width="header.structure.rows.row_2.tablet.columns.col_1.image_width"
                                        :src="header.structure.rows.row_2.tablet.columns.col_1.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_2.tablet.columns.col_1.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_2.tablet.columns.col_1.text_variant">{{
                                        header.structure.rows.row_2.tablet.columns.col_1.text }}</div>
                            </div>

                            <!-- Spalte 2 - tablet - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_2.tablet.columns.col_2.justify"
                                v-if="header.structure.rows.row_2.tablet.columns.count >= 2">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_2.tablet.columns.col_2.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_2.tablet.columns.col_2.has_image">

                                    <img :height="header.structure.rows.row_2.tablet.columns.col_2.image_height"
                                        :width="header.structure.rows.row_2.tablet.columns.col_2.image_width"
                                        :src="header.structure.rows.row_2.tablet.columns.col_2.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_2.tablet.columns.col_2.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_2.tablet.columns.col_2.text_variant">{{
                                        header.structure.rows.row_2.tablet.columns.col_2.text }}</div>
                            </div>

                            <!-- Spalte 3 - tablet - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_2.tablet.columns.col_3.justify"
                                v-if="header.structure.rows.row_2.tablet.columns.count >= 3">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_2.tablet.columns.col_3.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_2.tablet.columns.col_3.has_image">

                                    <img :height="header.structure.rows.row_2.tablet.columns.col_3.image_height"
                                        :width="header.structure.rows.row_2.tablet.columns.col_3.image_width"
                                        :src="header.structure.rows.row_2.tablet.columns.col_3.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_2.tablet.columns.col_3.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_2.tablet.columns.col_3.text_variant">{{
                                        header.structure.rows.row_2.tablet.columns.col_3.text }}</div>
                            </div>

                        </div>
                    </v-container>
                </div>

                <!-- Handy - Zeile 2 -->
                <div class="h-100 " v-if="display.xs" :class="lineClass(header, 'handy', 2)">
                    <v-container class="h-100 py-0 my-0" :fluid="header.structure.rows.row_2.handy.fluid"
                        :max-width="header.structure.rows.row_2.handy.max_width">
                        <div class="h-100 d-flex flex-row align-center">

                            <!-- Spalte 1 - handy - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_2.handy.columns.col_1.justify">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_2.handy.columns.col_1.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_2.handy.columns.col_1.has_image">

                                    <img :height="header.structure.rows.row_2.handy.columns.col_1.image_height"
                                        :width="header.structure.rows.row_2.handy.columns.col_1.image_width"
                                        :src="header.structure.rows.row_2.handy.columns.col_1.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_2.handy.columns.col_1.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_2.handy.columns.col_1.text_variant">{{
                                        header.structure.rows.row_2.handy.columns.col_1.text }}</div>
                            </div>

                            <!-- Spalte 2 - handy - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_2.handy.columns.col_2.justify"
                                v-if="header.structure.rows.row_2.handy.columns.count >= 2">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_2.handy.columns.col_2.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_2.handy.columns.col_2.has_image">

                                    <img :height="header.structure.rows.row_2.handy.columns.col_2.image_height"
                                        :width="header.structure.rows.row_2.handy.columns.col_2.image_width"
                                        :src="header.structure.rows.row_2.handy.columns.col_2.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_2.handy.columns.col_2.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_2.handy.columns.col_2.text_variant">{{
                                        header.structure.rows.row_2.handy.columns.col_2.text }}</div>
                            </div>

                            <!-- Spalte 3 - handy - Zeile 1-->
                            <div class="h-100 equal-col d-flex flex-row align-center"
                                :class="header.structure.rows.row_2.handy.columns.col_3.justify"
                                v-if="header.structure.rows.row_2.handy.columns.count >= 3">
                                <!-- MENÜ -->
                                <v-btn icon="mdi-menu" flat
                                    v-if="header.structure.rows.row_2.handy.columns.col_3.has_menu" />
                                <!-- BILD -->
                                <div v-if="header.structure.rows.row_2.handy.columns.col_3.has_image">

                                    <img :height="header.structure.rows.row_2.handy.columns.col_3.image_height"
                                        :width="header.structure.rows.row_2.handy.columns.col_3.image_width"
                                        :src="header.structure.rows.row_2.handy.columns.col_3.image" />

                                </div>
                                <!-- TEXT -->
                                <div v-if="header.structure.rows.row_2.handy.columns.col_3.has_text"
                                    class="ml-2  text-pre"
                                    :class="header.structure.rows.row_2.handy.columns.col_3.text_variant">{{
                                        header.structure.rows.row_2.handy.columns.col_3.text }}</div>
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
import { de } from 'zod/locales';


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

        lineClass(header, device, line) {
            const rowKey = `row_${line}`;
            const color = header.structure.rows[rowKey][device].color

            if (color && color !== 'transparent') {
                return 'bg-' + color;
            }

        },
        headerStyle(header, line) {
            var my_style = '';

            if (header.structure.rows.count == 2) {

                if (header.structure.props.height && header.structure.props.height >= 24) {
                    if (header.structure.props.height_row_1 && header.structure.props.height_row_1 >= 24) {
                        if (line == 1) {
                            my_style += ' height:' + header.structure.props.height_row_1 + 'px;';
                        } else if (line == 2) {
                            my_style += ' height:' + (header.structure.props.height - header.structure.props.height_row_1) + 'px;';
                        }

                    } else {
                        my_style += ' height:50%;';
                    }



                } else {
                    my_style += ' height:50%;';
                }

            } else {
                my_style += ' height:100%;';
            }


            return my_style;
        }


    },
};
</script>
<style scoped>
.equal-col {
    flex: 1 1 0;
    /* equal widths */
    min-width: 0;
    /* allow shrinking next to large content */
}
</style>
