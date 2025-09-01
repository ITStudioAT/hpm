<template>
    <v-card>
        <v-card-title class="d-flex flex-column">
            <div class="d-flex flex-row align-center justify-space-between">
                <div>Zeile 1</div>
                <v-btn flat size="small" color="warning" variant="tonal" @click="$emit('clickAction', '')">
                    <v-icon icon="mdi-close" />
                </v-btn>
            </div>
        </v-card-title>
        <v-card-subtitle>
            <div>Festlegen der Spalten</div>
        </v-card-subtitle>
        <v-card-text>
            <v-tabs v-model="line_1_col_options" align-tabs="center">
                <v-tab :value="1">Desktop</v-tab>
                <v-tab :value="2">Tablet</v-tab>
                <v-tab :value="3">Handy</v-tab>
            </v-tabs>
            <v-tabs-window v-model="line_1_col_options">
                <!-- DESKTOP -->
                <v-tabs-window-item :key="1" :value="1">
                    <v-container fluid>
                        <v-row>
                            <v-col>
                                <!-- SPALTE 1-->
                                <v-expansion-panels>
                                    <v-expansion-panel :key="1" title="Spalte 1" color="primary">
                                        <v-expansion-panel-text>
                                            <!-- Ausrichtung / Justify -->
                                            <v-select label="Ausrichtung"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_1.justify"
                                                :items="justifyItems" item-title="label" item-value="value" />

                                            <!-- has_menu -->
                                            <v-checkbox hide-details label="Menü"
                                                :color="header.structure.rows.row_1.desktop.columns.col_1.has_menu ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_1.has_menu" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.desktop.columns.col_1.has_menu">
                                                    <div class="bg-secondary pa-1">Menü:</div>
                                                    <div
                                                        v-if="header.structure.rows.row_1.desktop.columns.col_1.menu_name">
                                                        {{
                                                            header.structure.rows.row_1.desktop.columns.col_1.menu_name
                                                        }}</div>
                                                    <div class="text-warning" v-else>Kein Menü
                                                        ausgewählt</div>

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select" @click="">
                                                            Menü auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>



                                            <!-- has_image -->
                                            <v-divider color="primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Bild/Logo"
                                                :color="header.structure.rows.row_1.desktop.columns.col_1.has_image ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_1.has_image" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.desktop.columns.col_1.has_image">
                                                    <div class="bg-secondary pa-1">Bild: {{
                                                        header.structure.rows.row_1.desktop.columns.col_1.image
                                                    }}</div>
                                                    <div v-if="header.structure.rows.row_1.desktop.columns.col_1.image">
                                                        <v-btn flat size="small" icon="mdi-invert-colors"
                                                            @click="header.structure.rows.row_1.desktop.columns.col_1.invert_image = !header.structure.rows.row_1.desktop.columns.col_1.invert_image"></v-btn>
                                                    </div>

                                                    <v-img contain
                                                        :class="header.structure.rows.row_1.desktop.columns.col_1.invert_image ? 'bg-primary' : ''"
                                                        :height="header.structure.rows.row_1.desktop.columns.col_1.image_height"
                                                        :width="header.structure.rows.row_1.desktop.columns.col_1.image_width"
                                                        :src="header.structure.rows.row_1.desktop.columns.col_1.image" />

                                                    <div class="text-warning"
                                                        v-if="!header.structure.rows.row_1.desktop.columns.col_1.has_image">
                                                        Kein Bild ausgewählt</div>

                                                    <!-- Image Height -->
                                                    <v-number-input clearable label="Bildhöhe (24-128px)"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_1.image_height"
                                                        :min="24" :max="128" />

                                                    <!-- Image Width -->
                                                    <v-number-input clearable label="Bildbreite (24-128px)"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_1.image_width"
                                                        :min="24" :max="128" />

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select"
                                                            @click="is_select = 'line_1_col_1_desktop'">
                                                            Bild auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- has_text -->
                                            <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Text"
                                                :color="header.structure.rows.row_1.desktop.columns.col_1.has_text ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_1.has_text" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.desktop.columns.col_1.has_text">
                                                    <div class="bg-secondary pa-1">Text:
                                                    </div>
                                                    <v-text-field flat :rules="[maxLength(128)]" :counter="128"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_1.text" />
                                                    <!-- Textvariante -->
                                                    <v-select label="Textvariante"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_1.text_variant"
                                                        :items="textVariantItems" item-title="label"
                                                        item-value="value" />
                                                </div>
                                            </v-expand-transition>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>

                                <!-- SPALTE 2-->
                                <v-expansion-panels class="mt-4"
                                    v-if="header.structure.rows.row_1.desktop.columns.count >= 2">
                                    <v-expansion-panel :key="2" title="Spalte 2" color="primary">
                                        <v-expansion-panel-text>
                                            <!-- Ausrichtung / Justify -->
                                            <v-select label="Ausrichtung"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_2.justify"
                                                :items="justifyItems" item-title="label" item-value="value" />

                                            <!-- has_menu -->
                                            <v-checkbox hide-details label="Menü"
                                                :color="header.structure.rows.row_1.desktop.columns.col_2.has_menu ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_2.has_menu" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.desktop.columns.col_2.has_menu">
                                                    <div class="bg-secondary pa-1">Menü:</div>
                                                    <div
                                                        v-if="header.structure.rows.row_1.desktop.columns.col_2.menu_name">
                                                        {{
                                                            header.structure.rows.row_1.desktop.columns.col_2.menu_name
                                                        }}</div>
                                                    <div class="text-warning" v-else>Kein Menü
                                                        ausgewählt</div>

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select" @click="">
                                                            Menü auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>



                                            <!-- has_image -->
                                            <v-divider color="primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Bild/Logo"
                                                :color="header.structure.rows.row_1.desktop.columns.col_2.has_image ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_2.has_image" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.desktop.columns.col_2.has_image">
                                                    <div class="bg-secondary pa-1">Bild: {{
                                                        header.structure.rows.row_1.desktop.columns.col_2.image
                                                    }}</div>
                                                    <div v-if="header.structure.rows.row_1.desktop.columns.col_2.image">
                                                        <v-btn flat size="small" icon="mdi-invert-colors"
                                                            @click="header.structure.rows.row_1.desktop.columns.col_2.invert_image = !header.structure.rows.row_1.desktop.columns.col_2.invert_image"></v-btn>
                                                    </div>

                                                    <v-img contain
                                                        :class="header.structure.rows.row_1.desktop.columns.col_2.invert_image ? 'bg-primary' : ''"
                                                        :height="header.structure.rows.row_1.desktop.columns.col_2.image_height"
                                                        :width="header.structure.rows.row_1.desktop.columns.col_2.image_width"
                                                        :src="header.structure.rows.row_1.desktop.columns.col_2.image" />

                                                    <div class="text-warning"
                                                        v-if="!header.structure.rows.row_1.desktop.columns.col_2.has_image">
                                                        Kein Bild ausgewählt</div>

                                                    <!-- Image Height -->
                                                    <v-number-input clearable label="Bildhöhe (24-128px)"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_2.image_height"
                                                        :min="24" :max="128" />

                                                    <!-- Image Width -->
                                                    <v-number-input clearable label="Bildbreite (24-128px)"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_2.image_width"
                                                        :min="24" :max="128" />

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select"
                                                            @click="is_select = 'line_1_col_2_desktop'">
                                                            Bild auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- has_text -->
                                            <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Text"
                                                :color="header.structure.rows.row_1.desktop.columns.col_2.has_text ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_2.has_text" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.desktop.columns.col_2.has_text">
                                                    <div class="bg-secondary pa-1">Text:
                                                    </div>
                                                    <v-text-field flat :rules="[maxLength(128)]" :counter="128"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_2.text" />
                                                    <!-- Textvariante -->
                                                    <v-select label="Textvariante"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_2.text_variant"
                                                        :items="textVariantItems" item-title="label"
                                                        item-value="value" />
                                                </div>
                                            </v-expand-transition>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>

                                <!-- SPALTE 3-->
                                <v-expansion-panels class="mt-4"
                                    v-if="header.structure.rows.row_1.desktop.columns.count >= 3">
                                    <v-expansion-panel :key="3" title="Spalte 3" color="primary">
                                        <v-expansion-panel-text>
                                            <!-- Ausrichtung / Justify -->
                                            <v-select label="Ausrichtung"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_3.justify"
                                                :items="justifyItems" item-title="label" item-value="value" />

                                            <!-- has_menu -->
                                            <v-checkbox hide-details label="Menü"
                                                :color="header.structure.rows.row_1.desktop.columns.col_3.has_menu ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_3.has_menu" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.desktop.columns.col_3.has_menu">
                                                    <div class="bg-secondary pa-1">Menü:</div>
                                                    <div
                                                        v-if="header.structure.rows.row_1.desktop.columns.col_3.menu_name">
                                                        {{
                                                            header.structure.rows.row_1.desktop.columns.col_3.menu_name
                                                        }}</div>
                                                    <div class="text-warning" v-else>Kein Menü
                                                        ausgewählt</div>

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select" @click="">
                                                            Menü auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>



                                            <!-- has_image -->
                                            <v-divider color="primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Bild/Logo"
                                                :color="header.structure.rows.row_1.desktop.columns.col_3.has_image ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_3.has_image" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.desktop.columns.col_3.has_image">
                                                    <div class="bg-secondary pa-1">Bild: {{
                                                        header.structure.rows.row_1.desktop.columns.col_3.image
                                                    }}</div>
                                                    <div v-if="header.structure.rows.row_1.desktop.columns.col_3.image">
                                                        <v-btn flat size="small" icon="mdi-invert-colors"
                                                            @click="header.structure.rows.row_1.desktop.columns.col_3.invert_image = !header.structure.rows.row_1.desktop.columns.col_3.invert_image"></v-btn>
                                                    </div>

                                                    <v-img contain
                                                        :class="header.structure.rows.row_1.desktop.columns.col_3.invert_image ? 'bg-primary' : ''"
                                                        :height="header.structure.rows.row_1.desktop.columns.col_3.image_height"
                                                        :width="header.structure.rows.row_1.desktop.columns.col_3.image_width"
                                                        :src="header.structure.rows.row_1.desktop.columns.col_3.image" />

                                                    <div class="text-warning"
                                                        v-if="!header.structure.rows.row_1.desktop.columns.col_3.has_image">
                                                        Kein Bild ausgewählt</div>

                                                    <!-- Image Height -->
                                                    <v-number-input clearable label="Bildhöhe (24-128px)"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_3.image_height"
                                                        :min="24" :max="128" />

                                                    <!-- Image Width -->
                                                    <v-number-input clearable label="Bildbreite (24-128px)"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_3.image_width"
                                                        :min="24" :max="128" />

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select"
                                                            @click="is_select = 'line_1_col_3_desktop'">
                                                            Bild auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- has_text -->
                                            <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Text"
                                                :color="header.structure.rows.row_1.desktop.columns.col_3.has_text ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.desktop.columns.col_3.has_text" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.desktop.columns.col_3.has_text">
                                                    <div class="bg-secondary pa-1">Text:
                                                    </div>
                                                    <v-text-field flat :rules="[maxLength(128)]" :counter="128"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_3.text" />
                                                    <!-- Textvariante -->
                                                    <v-select label="Textvariante"
                                                        v-model="header.structure.rows.row_1.desktop.columns.col_3.text_variant"
                                                        :items="textVariantItems" item-title="label"
                                                        item-value="value" />
                                                </div>
                                            </v-expand-transition>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>

                            </v-col>
                        </v-row>
                    </v-container>
                </v-tabs-window-item>

                <!-- TABLET -->
                <v-tabs-window-item :key="2" :value="2">
                    <v-container fluid>
                        <v-row>
                            <v-col>
                                <!-- SPALTE 1-->
                                <v-expansion-panels>
                                    <v-expansion-panel :key="1" title="Spalte 1" color="primary">
                                        <v-expansion-panel-text>
                                            <!-- Ausrichtung / Justify -->
                                            <v-select label="Ausrichtung"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_1.justify"
                                                :items="justifyItems" item-title="label" item-value="value" />

                                            <!-- has_menu -->
                                            <v-checkbox hide-details label="Menü"
                                                :color="header.structure.rows.row_1.tablet.columns.col_1.has_menu ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_1.has_menu" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.tablet.columns.col_1.has_menu">
                                                    <div class="bg-secondary pa-1">Menü:</div>
                                                    <div
                                                        v-if="header.structure.rows.row_1.tablet.columns.col_1.menu_name">
                                                        {{
                                                            header.structure.rows.row_1.tablet.columns.col_1.menu_name
                                                        }}</div>
                                                    <div class="text-warning" v-else>Kein Menü
                                                        ausgewählt</div>

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select" @click="">
                                                            Menü auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>



                                            <!-- has_image -->
                                            <v-divider color="primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Bild/Logo"
                                                :color="header.structure.rows.row_1.tablet.columns.col_1.has_image ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_1.has_image" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.tablet.columns.col_1.has_image">
                                                    <div class="bg-secondary pa-1">Bild: {{
                                                        header.structure.rows.row_1.tablet.columns.col_1.image
                                                    }}</div>
                                                    <div v-if="header.structure.rows.row_1.tablet.columns.col_1.image">
                                                        <v-btn flat size="small" icon="mdi-invert-colors"
                                                            @click="header.structure.rows.row_1.tablet.columns.col_1.invert_image = !header.structure.rows.row_1.tablet.columns.col_1.invert_image"></v-btn>
                                                    </div>

                                                    <v-img contain
                                                        :class="header.structure.rows.row_1.tablet.columns.col_1.invert_image ? 'bg-primary' : ''"
                                                        :height="header.structure.rows.row_1.tablet.columns.col_1.image_height"
                                                        :width="header.structure.rows.row_1.tablet.columns.col_1.image_width"
                                                        :src="header.structure.rows.row_1.tablet.columns.col_1.image" />

                                                    <div class="text-warning"
                                                        v-if="!header.structure.rows.row_1.tablet.columns.col_1.has_image">
                                                        Kein Bild ausgewählt</div>

                                                    <!-- Image Height -->
                                                    <v-number-input clearable label="Bildhöhe (24-128px)"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_1.image_height"
                                                        :min="24" :max="128" />

                                                    <!-- Image Width -->
                                                    <v-number-input clearable label="Bildbreite (24-128px)"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_1.image_width"
                                                        :min="24" :max="128" />

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select"
                                                            @click="is_select = 'line_1_col_1_tablet'">
                                                            Bild auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- has_text -->
                                            <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Text"
                                                :color="header.structure.rows.row_1.tablet.columns.col_1.has_text ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_1.has_text" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.tablet.columns.col_1.has_text">
                                                    <div class="bg-secondary pa-1">Text:
                                                    </div>
                                                    <v-text-field flat :rules="[maxLength(128)]" :counter="128"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_1.text" />
                                                    <!-- Textvariante -->
                                                    <v-select label="Textvariante"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_1.text_variant"
                                                        :items="textVariantItems" item-title="label"
                                                        item-value="value" />
                                                </div>
                                            </v-expand-transition>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>

                                <!-- SPALTE 2-->
                                <v-expansion-panels class="mt-4"
                                    v-if="header.structure.rows.row_1.tablet.columns.count >= 2">
                                    <v-expansion-panel :key="2" title="Spalte 2" color="primary">
                                        <v-expansion-panel-text>
                                            <!-- Ausrichtung / Justify -->
                                            <v-select label="Ausrichtung"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_2.justify"
                                                :items="justifyItems" item-title="label" item-value="value" />

                                            <!-- has_menu -->
                                            <v-checkbox hide-details label="Menü"
                                                :color="header.structure.rows.row_1.tablet.columns.col_2.has_menu ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_2.has_menu" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.tablet.columns.col_2.has_menu">
                                                    <div class="bg-secondary pa-1">Menü:</div>
                                                    <div
                                                        v-if="header.structure.rows.row_1.tablet.columns.col_2.menu_name">
                                                        {{
                                                            header.structure.rows.row_1.tablet.columns.col_2.menu_name
                                                        }}</div>
                                                    <div class="text-warning" v-else>Kein Menü
                                                        ausgewählt</div>

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select" @click="">
                                                            Menü auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>



                                            <!-- has_image -->
                                            <v-divider color="primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Bild/Logo"
                                                :color="header.structure.rows.row_1.tablet.columns.col_2.has_image ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_2.has_image" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.tablet.columns.col_2.has_image">
                                                    <div class="bg-secondary pa-1">Bild: {{
                                                        header.structure.rows.row_1.tablet.columns.col_2.image
                                                    }}</div>
                                                    <div v-if="header.structure.rows.row_1.tablet.columns.col_2.image">
                                                        <v-btn flat size="small" icon="mdi-invert-colors"
                                                            @click="header.structure.rows.row_1.tablet.columns.col_2.invert_image = !header.structure.rows.row_1.tablet.columns.col_2.invert_image"></v-btn>
                                                    </div>

                                                    <v-img contain
                                                        :class="header.structure.rows.row_1.tablet.columns.col_2.invert_image ? 'bg-primary' : ''"
                                                        :height="header.structure.rows.row_1.tablet.columns.col_2.image_height"
                                                        :width="header.structure.rows.row_1.tablet.columns.col_2.image_width"
                                                        :src="header.structure.rows.row_1.tablet.columns.col_2.image" />

                                                    <div class="text-warning"
                                                        v-if="!header.structure.rows.row_1.tablet.columns.col_2.has_image">
                                                        Kein Bild ausgewählt</div>

                                                    <!-- Image Height -->
                                                    <v-number-input clearable label="Bildhöhe (24-128px)"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_2.image_height"
                                                        :min="24" :max="128" />

                                                    <!-- Image Width -->
                                                    <v-number-input clearable label="Bildbreite (24-128px)"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_2.image_width"
                                                        :min="24" :max="128" />

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select"
                                                            @click="is_select = 'line_1_col_2_tablet'">
                                                            Bild auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- has_text -->
                                            <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Text"
                                                :color="header.structure.rows.row_1.tablet.columns.col_2.has_text ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_2.has_text" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.tablet.columns.col_2.has_text">
                                                    <div class="bg-secondary pa-1">Text:
                                                    </div>
                                                    <v-text-field flat :rules="[maxLength(128)]" :counter="128"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_2.text" />
                                                    <!-- Textvariante -->
                                                    <v-select label="Textvariante"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_2.text_variant"
                                                        :items="textVariantItems" item-title="label"
                                                        item-value="value" />
                                                </div>
                                            </v-expand-transition>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>

                                <!-- SPALTE 3-->
                                <v-expansion-panels class="mt-4"
                                    v-if="header.structure.rows.row_1.tablet.columns.count >= 3">
                                    <v-expansion-panel :key="3" title="Spalte 3" color="primary">
                                        <v-expansion-panel-text>
                                            <!-- Ausrichtung / Justify -->
                                            <v-select label="Ausrichtung"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_3.justify"
                                                :items="justifyItems" item-title="label" item-value="value" />

                                            <!-- has_menu -->
                                            <v-checkbox hide-details label="Menü"
                                                :color="header.structure.rows.row_1.tablet.columns.col_3.has_menu ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_3.has_menu" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.tablet.columns.col_3.has_menu">
                                                    <div class="bg-secondary pa-1">Menü:</div>
                                                    <div
                                                        v-if="header.structure.rows.row_1.tablet.columns.col_3.menu_name">
                                                        {{
                                                            header.structure.rows.row_1.tablet.columns.col_3.menu_name
                                                        }}</div>
                                                    <div class="text-warning" v-else>Kein Menü
                                                        ausgewählt</div>

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select" @click="">
                                                            Menü auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>



                                            <!-- has_image -->
                                            <v-divider color="primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Bild/Logo"
                                                :color="header.structure.rows.row_1.tablet.columns.col_3.has_image ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_3.has_image" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.tablet.columns.col_3.has_image">
                                                    <div class="bg-secondary pa-1">Bild: {{
                                                        header.structure.rows.row_1.tablet.columns.col_3.image
                                                    }}</div>
                                                    <div v-if="header.structure.rows.row_1.tablet.columns.col_3.image">
                                                        <v-btn flat size="small" icon="mdi-invert-colors"
                                                            @click="header.structure.rows.row_1.tablet.columns.col_3.invert_image = !header.structure.rows.row_1.tablet.columns.col_3.invert_image"></v-btn>
                                                    </div>

                                                    <v-img contain
                                                        :class="header.structure.rows.row_1.tablet.columns.col_3.invert_image ? 'bg-primary' : ''"
                                                        :height="header.structure.rows.row_1.tablet.columns.col_3.image_height"
                                                        :width="header.structure.rows.row_1.tablet.columns.col_3.image_width"
                                                        :src="header.structure.rows.row_1.tablet.columns.col_3.image" />

                                                    <div class="text-warning"
                                                        v-if="!header.structure.rows.row_1.tablet.columns.col_3.has_image">
                                                        Kein Bild ausgewählt</div>

                                                    <!-- Image Height -->
                                                    <v-number-input clearable label="Bildhöhe (24-128px)"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_3.image_height"
                                                        :min="24" :max="128" />

                                                    <!-- Image Width -->
                                                    <v-number-input clearable label="Bildbreite (24-128px)"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_3.image_width"
                                                        :min="24" :max="128" />

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select"
                                                            @click="is_select = 'line_1_col_3_tablet'">
                                                            Bild auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- has_text -->
                                            <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Text"
                                                :color="header.structure.rows.row_1.tablet.columns.col_3.has_text ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.tablet.columns.col_3.has_text" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.tablet.columns.col_3.has_text">
                                                    <div class="bg-secondary pa-1">Text:
                                                    </div>
                                                    <v-text-field flat :rules="[maxLength(128)]" :counter="128"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_3.text" />
                                                    <!-- Textvariante -->
                                                    <v-select label="Textvariante"
                                                        v-model="header.structure.rows.row_1.tablet.columns.col_3.text_variant"
                                                        :items="textVariantItems" item-title="label"
                                                        item-value="value" />
                                                </div>
                                            </v-expand-transition>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>

                            </v-col>
                        </v-row>
                    </v-container>
                </v-tabs-window-item>

                <!-- HANDY -->
                <v-tabs-window-item :key="3" :value="3">
                    <v-container fluid>
                        <v-row>
                            <v-col>
                                <!-- SPALTE 1-->
                                <v-expansion-panels>
                                    <v-expansion-panel :key="1" title="Spalte 1" color="primary">
                                        <v-expansion-panel-text>
                                            <!-- Ausrichtung / Justify -->
                                            <v-select label="Ausrichtung"
                                                v-model="header.structure.rows.row_1.handy.columns.col_1.justify"
                                                :items="justifyItems" item-title="label" item-value="value" />

                                            <!-- has_menu -->
                                            <v-checkbox hide-details label="Menü"
                                                :color="header.structure.rows.row_1.handy.columns.col_1.has_menu ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.handy.columns.col_1.has_menu" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.handy.columns.col_1.has_menu">
                                                    <div class="bg-secondary pa-1">Menü:</div>
                                                    <div
                                                        v-if="header.structure.rows.row_1.handy.columns.col_1.menu_name">
                                                        {{
                                                            header.structure.rows.row_1.handy.columns.col_1.menu_name
                                                        }}</div>
                                                    <div class="text-warning" v-else>Kein Menü
                                                        ausgewählt</div>

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select" @click="">
                                                            Menü auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>



                                            <!-- has_image -->
                                            <v-divider color="primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Bild/Logo"
                                                :color="header.structure.rows.row_1.handy.columns.col_1.has_image ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.handy.columns.col_1.has_image" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.handy.columns.col_1.has_image">
                                                    <div class="bg-secondary pa-1">Bild: {{
                                                        header.structure.rows.row_1.handy.columns.col_1.image
                                                    }}</div>
                                                    <div v-if="header.structure.rows.row_1.handy.columns.col_1.image">
                                                        <v-btn flat size="small" icon="mdi-invert-colors"
                                                            @click="header.structure.rows.row_1.handy.columns.col_1.invert_image = !header.structure.rows.row_1.handy.columns.col_1.invert_image"></v-btn>
                                                    </div>

                                                    <v-img contain
                                                        :class="header.structure.rows.row_1.handy.columns.col_1.invert_image ? 'bg-primary' : ''"
                                                        :height="header.structure.rows.row_1.handy.columns.col_1.image_height"
                                                        :width="header.structure.rows.row_1.handy.columns.col_1.image_width"
                                                        :src="header.structure.rows.row_1.handy.columns.col_1.image" />

                                                    <div class="text-warning"
                                                        v-if="!header.structure.rows.row_1.handy.columns.col_1.has_image">
                                                        Kein Bild ausgewählt</div>

                                                    <!-- Image Height -->
                                                    <v-number-input clearable label="Bildhöhe (24-128px)"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_1.image_height"
                                                        :min="24" :max="128" />

                                                    <!-- Image Width -->
                                                    <v-number-input clearable label="Bildbreite (24-128px)"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_1.image_width"
                                                        :min="24" :max="128" />

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select"
                                                            @click="is_select = 'line_1_col_1_handy'">
                                                            Bild auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- has_text -->
                                            <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Text"
                                                :color="header.structure.rows.row_1.handy.columns.col_1.has_text ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.handy.columns.col_1.has_text" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.handy.columns.col_1.has_text">
                                                    <div class="bg-secondary pa-1">Text:
                                                    </div>
                                                    <v-text-field flat :rules="[maxLength(128)]" :counter="128"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_1.text" />
                                                    <!-- Textvariante -->
                                                    <v-select label="Textvariante"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_1.text_variant"
                                                        :items="textVariantItems" item-title="label"
                                                        item-value="value" />
                                                </div>
                                            </v-expand-transition>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>

                                <!-- SPALTE 2-->
                                <v-expansion-panels class="mt-4"
                                    v-if="header.structure.rows.row_1.handy.columns.count >= 2">
                                    <v-expansion-panel :key="2" title="Spalte 2" color="primary">
                                        <v-expansion-panel-text>
                                            <!-- Ausrichtung / Justify -->
                                            <v-select label="Ausrichtung"
                                                v-model="header.structure.rows.row_1.handy.columns.col_2.justify"
                                                :items="justifyItems" item-title="label" item-value="value" />

                                            <!-- has_menu -->
                                            <v-checkbox hide-details label="Menü"
                                                :color="header.structure.rows.row_1.handy.columns.col_2.has_menu ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.handy.columns.col_2.has_menu" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.handy.columns.col_2.has_menu">
                                                    <div class="bg-secondary pa-1">Menü:</div>
                                                    <div
                                                        v-if="header.structure.rows.row_1.handy.columns.col_2.menu_name">
                                                        {{
                                                            header.structure.rows.row_1.handy.columns.col_2.menu_name
                                                        }}</div>
                                                    <div class="text-warning" v-else>Kein Menü
                                                        ausgewählt</div>

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select" @click="">
                                                            Menü auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>



                                            <!-- has_image -->
                                            <v-divider color="primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Bild/Logo"
                                                :color="header.structure.rows.row_1.handy.columns.col_2.has_image ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.handy.columns.col_2.has_image" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.handy.columns.col_2.has_image">
                                                    <div class="bg-secondary pa-1">Bild: {{
                                                        header.structure.rows.row_1.handy.columns.col_2.image
                                                    }}</div>
                                                    <div v-if="header.structure.rows.row_1.handy.columns.col_2.image">
                                                        <v-btn flat size="small" icon="mdi-invert-colors"
                                                            @click="header.structure.rows.row_1.handy.columns.col_2.invert_image = !header.structure.rows.row_1.handy.columns.col_2.invert_image"></v-btn>
                                                    </div>

                                                    <v-img contain
                                                        :class="header.structure.rows.row_1.handy.columns.col_2.invert_image ? 'bg-primary' : ''"
                                                        :height="header.structure.rows.row_1.handy.columns.col_2.image_height"
                                                        :width="header.structure.rows.row_1.handy.columns.col_2.image_width"
                                                        :src="header.structure.rows.row_1.handy.columns.col_2.image" />

                                                    <div class="text-warning"
                                                        v-if="!header.structure.rows.row_1.handy.columns.col_2.has_image">
                                                        Kein Bild ausgewählt</div>

                                                    <!-- Image Height -->
                                                    <v-number-input clearable label="Bildhöhe (24-128px)"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_2.image_height"
                                                        :min="24" :max="128" />

                                                    <!-- Image Width -->
                                                    <v-number-input clearable label="Bildbreite (24-128px)"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_2.image_width"
                                                        :min="24" :max="128" />

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select"
                                                            @click="is_select = 'line_1_col_2_handy'">
                                                            Bild auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- has_text -->
                                            <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Text"
                                                :color="header.structure.rows.row_1.handy.columns.col_2.has_text ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.handy.columns.col_2.has_text" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.handy.columns.col_2.has_text">
                                                    <div class="bg-secondary pa-1">Text:
                                                    </div>
                                                    <v-text-field flat :rules="[maxLength(128)]" :counter="128"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_2.text" />
                                                    <!-- Textvariante -->
                                                    <v-select label="Textvariante"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_2.text_variant"
                                                        :items="textVariantItems" item-title="label"
                                                        item-value="value" />
                                                </div>
                                            </v-expand-transition>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>

                                <!-- SPALTE 3-->
                                <v-expansion-panels class="mt-4"
                                    v-if="header.structure.rows.row_1.handy.columns.count >= 3">
                                    <v-expansion-panel :key="3" title="Spalte 3" color="primary">
                                        <v-expansion-panel-text>
                                            <!-- Ausrichtung / Justify -->
                                            <v-select label="Ausrichtung"
                                                v-model="header.structure.rows.row_1.handy.columns.col_3.justify"
                                                :items="justifyItems" item-title="label" item-value="value" />

                                            <!-- has_menu -->
                                            <v-checkbox hide-details label="Menü"
                                                :color="header.structure.rows.row_1.handy.columns.col_3.has_menu ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.handy.columns.col_3.has_menu" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.handy.columns.col_3.has_menu">
                                                    <div class="bg-secondary pa-1">Menü:</div>
                                                    <div
                                                        v-if="header.structure.rows.row_1.handy.columns.col_3.menu_name">
                                                        {{
                                                            header.structure.rows.row_1.handy.columns.col_3.menu_name
                                                        }}</div>
                                                    <div class="text-warning" v-else>Kein Menü
                                                        ausgewählt</div>

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select" @click="">
                                                            Menü auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>



                                            <!-- has_image -->
                                            <v-divider color="primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Bild/Logo"
                                                :color="header.structure.rows.row_1.handy.columns.col_3.has_image ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.handy.columns.col_3.has_image" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.handy.columns.col_3.has_image">
                                                    <div class="bg-secondary pa-1">Bild: {{
                                                        header.structure.rows.row_1.handy.columns.col_3.image
                                                        }}</div>
                                                    <div v-if="header.structure.rows.row_1.handy.columns.col_3.image">
                                                        <v-btn flat size="small" icon="mdi-invert-colors"
                                                            @click="header.structure.rows.row_1.handy.columns.col_3.invert_image = !header.structure.rows.row_1.handy.columns.col_3.invert_image"></v-btn>
                                                    </div>

                                                    <v-img contain
                                                        :class="header.structure.rows.row_1.handy.columns.col_3.invert_image ? 'bg-primary' : ''"
                                                        :height="header.structure.rows.row_1.handy.columns.col_3.image_height"
                                                        :width="header.structure.rows.row_1.handy.columns.col_3.image_width"
                                                        :src="header.structure.rows.row_1.handy.columns.col_3.image" />

                                                    <div class="text-warning"
                                                        v-if="!header.structure.rows.row_1.handy.columns.col_3.has_image">
                                                        Kein Bild ausgewählt</div>

                                                    <!-- Image Height -->
                                                    <v-number-input clearable label="Bildhöhe (24-128px)"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_3.image_height"
                                                        :min="24" :max="128" />

                                                    <!-- Image Width -->
                                                    <v-number-input clearable label="Bildbreite (24-128px)"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_3.image_width"
                                                        :min="24" :max="128" />

                                                    <div class="d-flex flex-row justify-end">
                                                        <v-btn flat color="primary" variant="tonal"
                                                            prepend-icon="mdi-form-select"
                                                            @click="is_select = 'line_1_col_3_handy'">
                                                            Bild auswählen
                                                        </v-btn>
                                                    </div>
                                                </div>
                                            </v-expand-transition>

                                            <!-- has_text -->
                                            <v-divider color=" primary" opacity=0.5 class="mt-4" />
                                            <v-checkbox hide-details label="Text"
                                                :color="header.structure.rows.row_1.handy.columns.col_3.has_text ? 'success' : ''"
                                                v-model="header.structure.rows.row_1.handy.columns.col_3.has_text" />
                                            <v-expand-transition>
                                                <div v-if="header.structure.rows.row_1.handy.columns.col_3.has_text">
                                                    <div class="bg-secondary pa-1">Text:
                                                    </div>
                                                    <v-text-field flat :rules="[maxLength(128)]" :counter="128"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_3.text" />
                                                    <!-- Textvariante -->
                                                    <v-select label="Textvariante"
                                                        v-model="header.structure.rows.row_1.handy.columns.col_3.text_variant"
                                                        :items="textVariantItems" item-title="label"
                                                        item-value="value" />
                                                </div>
                                            </v-expand-transition>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                                </v-expansion-panels>

                            </v-col>
                        </v-row>
                    </v-container>
                </v-tabs-window-item>


            </v-tabs-window>
            <div class="d-flex flex-row justify-end">
                <v-btn flat color="success" variant="tonal" prepend-icon="mdi-check"
                    @click="$emit('confirmAction', header)">
                    Bestätigen
                </v-btn>
            </div>
        </v-card-text>
    </v-card>

    <!-- Auswahl eines Bildes -->
    <Select v-if="is_select != ''" @abort="is_select = false" @takeIt="selectTakeIt" />
</template>

<script>
import { useValidationRulesSetup } from "@/helpers/rules";
import Select from "@mediamanager/js/pages/admin/select/Select.vue";
export default {
    setup() { return useValidationRulesSetup(); },

    components: { Select },
    props: ['header', 'justifyItems', 'textVariantItems'],
    emits: ['clickAction', 'confirmAction'],

    data() {
        return {
            line_1_options: 1,
            line_2_options: 1,
            line_1_col_options: 1,
            is_select: '',
        }
    },

    methods: {
        selectTakeIt(data) {
            console.log(data); // shows path and filename
            switch (this.is_select) {
                case 'line_1_col_1_desktop':
                    this.header.structure.rows.row_1.desktop.columns.col_1.image = data.current_folder + '/' + data.file;;
                    break;
                case 'line_1_col_2_desktop':
                    this.header.structure.rows.row_1.desktop.columns.col_2.image = data.current_folder + '/' + data.file;;
                    break;
                case 'line_1_col_3_desktop':
                    this.header.structure.rows.row_1.desktop.columns.col_3.image = data.current_folder + '/' + data.file;;
                    break;
                // weitere Fälle hier hinzufügen
            }
            this.is_select = '';
        },
    }

}
</script>