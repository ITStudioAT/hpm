<template>
    <v-card>
        <v-card-title class="d-flex flex-column">
            <div class="d-flex flex-row align-center justify-space-between">
                <div>Zeilen / Spalten</div>
                <v-btn flat size="small" color="warning" variant="tonal" @click="$emit('clickAction', '')">
                    <v-icon icon="mdi-close" />
                </v-btn>
            </div>
        </v-card-title>
        <v-card-subtitle>
            <div>Aufbau der Kopfzeile</div>
        </v-card-subtitle>
        <v-card-text>
            <!-- Rows (Hinweis: dein Schema erlaubt aktuell 1..2 statisch; count wirkt rein visuell) -->
            <v-number-input label="Zeilen (1-2)" v-model="header.structure.rows.count" :min="1" :max="2" />

            <!-- ZEILE 1-->
            <v-card>

                <div class="bg-primary pa-1">Zeile 1:</div>



                <v-tabs v-model="line_1_options" align-tabs="center">
                    <v-tab :value="1">Desktop</v-tab>
                    <v-tab :value="2">Tablet</v-tab>
                    <v-tab :value="3">Handy</v-tab>
                </v-tabs>
                <v-tabs-window v-model="line_1_options">
                    <!-- DESKTOP -->
                    <v-tabs-window-item :key="1" :value="1">
                        <v-container fluid>
                            <v-row>
                                <v-col>
                                    <!-- Farbe -->
                                    <div class="d-flex flex-row flex-wrap ga-2">
                                        <div class="color-box first d-flex align-center justify-center">A</div>
                                        <div class="color-box second d-flex align-center justify-center">B</div>
                                        <div class="color-box third d-flex align-center justify-center">C</div>
                                    </div>
                                    <v-select label="Farbe" v-model="header.structure.rows.row_1.desktop.color"
                                        :items="colorItems" item-title="label" item-value="value" />

                                    <!-- Fluid -->
                                    <v-checkbox hide-details label="Volle Breite"
                                        :color="header.structure.rows.row_1.desktop.fluid ? 'success' : ''"
                                        v-model="header.structure.rows.row_1.desktop.fluid" />
                                    <div class="text-caption font-weight-medium">oder</div>

                                    <!-- max-width -->
                                    <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                        v-model="header.structure.rows.row_1.desktop.max_width" :min="600"
                                        :max="1920" />


                                    <!-- columns.count -->
                                    <v-number-input label="Anzahl Spalten  (1-3)"
                                        v-model="header.structure.rows.row_1.desktop.columns.count" :min="1" :max="3" />


                                </v-col>
                            </v-row>
                        </v-container>
                    </v-tabs-window-item>

                    <!-- TABLET -->
                    <v-tabs-window-item :key="2" :value="2">
                        <v-container fluid>
                            <v-row>
                                <v-col>
                                    <!-- Farbe -->
                                    <div class="d-flex flex-row flex-wrap ga-2">
                                        <div class="color-box first d-flex align-center justify-center">A</div>
                                        <div class="color-box second d-flex align-center justify-center">B</div>
                                        <div class="color-box third d-flex align-center justify-center">C</div>
                                    </div>
                                    <v-select label="Farbe" v-model="header.structure.rows.row_1.tablet.color"
                                        :items="colorItems" item-title="label" item-value="value" />
                                    <!-- Fluid -->
                                    <v-checkbox hide-details label="Volle Breite"
                                        :color="header.structure.rows.row_1.tablet.fluid ? 'success' : ''"
                                        v-model="header.structure.rows.row_1.tablet.fluid" />
                                    <div class="text-caption font-weight-medium">oder</div>

                                    <!-- max-width -->
                                    <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                        v-model="header.structure.rows.row_1.tablet.max_width" :min="600" :max="1920" />

                                    <!-- columns.count -->
                                    <v-number-input label="Anzahl Spalten  (1-3)"
                                        v-model="header.structure.rows.row_1.tablet.columns.count" :min="1" :max="3" />


                                </v-col>
                            </v-row>
                        </v-container>
                    </v-tabs-window-item>

                    <!-- HANDY -->
                    <v-tabs-window-item :key="3" :value="3">
                        <v-container fluid>
                            <v-row>
                                <v-col>
                                    <!-- Farbe -->
                                    <div class="d-flex flex-row flex-wrap ga-2">
                                        <div class="color-box first d-flex align-center justify-center">A</div>
                                        <div class="color-box second d-flex align-center justify-center">B</div>
                                        <div class="color-box third d-flex align-center justify-center">C</div>
                                    </div>
                                    <v-select label="Farbe" v-model="header.structure.rows.row_1.handy.color"
                                        :items="colorItems" item-title="label" item-value="value" />
                                    <!-- Fluid -->
                                    <v-checkbox hide-details label="Volle Breite"
                                        :color="header.structure.rows.row_1.handy.fluid ? 'success' : ''"
                                        v-model="header.structure.rows.row_1.handy.fluid" />
                                    <div class="text-caption font-weight-medium">oder</div>

                                    <!-- max-width -->
                                    <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                        v-model="header.structure.rows.row_1.handy.max_width" :min="600" :max="1920" />

                                    <!-- columns.count -->
                                    <v-number-input label="Anzahl Spalten  (1-3)"
                                        v-model="header.structure.rows.row_1.handy.columns.count" :min="1" :max="3" />


                                </v-col>
                            </v-row>
                        </v-container>
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-card>


            <!-- ZEILE 2-->
            <v-card v-if="header.structure.rows.count > 1" class="mt-6">

                <div class="bg-primary pa-1">Zeile 2:</div>



                <v-tabs v-model="line_2_options" align-tabs="center">
                    <v-tab :value="1">Desktop</v-tab>
                    <v-tab :value="2">Tablet</v-tab>
                    <v-tab :value="3">Handy</v-tab>
                </v-tabs>
                <v-tabs-window v-model="line_2_options">
                    <!-- DESKTOP -->
                    <v-tabs-window-item :key="1" :value="1">
                        <v-container fluid>
                            <v-row>
                                <v-col>
                                    <!-- Farbe -->
                                    <div class="d-flex flex-row flex-wrap ga-2">
                                        <div class="color-box first d-flex align-center justify-center">A</div>
                                        <div class="color-box second d-flex align-center justify-center">B</div>
                                        <div class="color-box third d-flex align-center justify-center">C</div>
                                    </div>
                                    <v-select label="Farbe" v-model="header.structure.rows.row_2.desktop.color"
                                        :items="colorItems" item-title="label" item-value="value" />
                                    <!-- Fluid -->
                                    <v-checkbox hide-details label="Volle Breite"
                                        :color="header.structure.rows.row_2.desktop.fluid ? 'success' : ''"
                                        v-model="header.structure.rows.row_2.desktop.fluid" />
                                    <div class="text-caption font-weight-medium">oder</div>

                                    <!-- max-width -->
                                    <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                        v-model="header.structure.rows.row_2.desktop.max_width" :min="600"
                                        :max="1920" />

                                    <!-- columns.count -->
                                    <v-number-input label="Anzahl Spalten  (1-3)"
                                        v-model="header.structure.rows.row_2.desktop.columns.count" :min="1" :max="3" />


                                </v-col>
                            </v-row>
                        </v-container>
                    </v-tabs-window-item>

                    <!-- TABLET -->
                    <v-tabs-window-item :key="2" :value="2">
                        <v-container fluid>
                            <v-row>
                                <v-col>
                                    <!-- Farbe -->
                                    <div class="d-flex flex-row flex-wrap ga-2">
                                        <div class="color-box first d-flex align-center justify-center">A</div>
                                        <div class="color-box second d-flex align-center justify-center">B</div>
                                        <div class="color-box third d-flex align-center justify-center">C</div>
                                    </div>
                                    <v-select label="Farbe" v-model="header.structure.rows.row_2.tablet.color"
                                        :items="colorItems" item-title="label" item-value="value" />
                                    <!-- Fluid -->
                                    <v-checkbox hide-details label="Volle Breite"
                                        :color="header.structure.rows.row_2.tablet.fluid ? 'success' : ''"
                                        v-model="header.structure.rows.row_2.tablet.fluid" />
                                    <div class="text-caption font-weight-medium">oder</div>

                                    <!-- max-width -->
                                    <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                        v-model="header.structure.rows.row_2.tablet.max_width" :min="600" :max="1920" />

                                    <!-- columns.count -->
                                    <v-number-input label="Anzahl Spalten  (1-3)"
                                        v-model="header.structure.rows.row_2.tablet.columns.count" :min="1" :max="3" />


                                </v-col>
                            </v-row>
                        </v-container>
                    </v-tabs-window-item>

                    <!-- HANDY -->
                    <v-tabs-window-item :key="3" :value="3">
                        <v-container fluid>
                            <v-row>
                                <v-col>
                                    <!-- Farbe -->
                                    <div class="d-flex flex-row flex-wrap ga-2">
                                        <div class="color-box first d-flex align-center justify-center">A</div>
                                        <div class="color-box second d-flex align-center justify-center">B</div>
                                        <div class="color-box third d-flex align-center justify-center">C</div>
                                    </div>
                                    <v-select label="Farbe" v-model="header.structure.rows.row_2.handy.color"
                                        :items="colorItems" item-title="label" item-value="value" />
                                    <!-- Fluid -->
                                    <v-checkbox hide-details label="Volle Breite"
                                        :color="header.structure.rows.row_2.handy.fluid ? 'success' : ''"
                                        v-model="header.structure.rows.row_2.handy.fluid" />
                                    <div class="text-caption font-weight-medium">oder</div>

                                    <!-- max-width -->
                                    <v-number-input label="Maximale Breite  (600-1920px)" clearable
                                        v-model="header.structure.rows.row_2.handy.max_width" :min="600" :max="1920" />

                                    <!-- columns.count -->
                                    <v-number-input label="Anzahl Spalten  (1-3)"
                                        v-model="header.structure.rows.row_2.handy.columns.count" :min="1" :max="3" />


                                </v-col>
                            </v-row>
                        </v-container>
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-card>


            <div class="d-flex flex-row justify-end mt-6">
                <v-btn flat color="success" variant="tonal" prepend-icon="mdi-check"
                    @click="$emit('confirmAction', header)">
                    Bestätigen
                </v-btn>
            </div>

        </v-card-text>
    </v-card>
</template>

<script>
export default {
    props: ['header', 'colorItems'],
    emits: ['clickAction', 'confirmAction'],

    data() {
        return {
            line_1_options: 1,
            line_2_options: 1,
            line_1_col_options: 1,
        }
    }


}
</script>

<style scoped>
.color-box {
    width: 100px;
    height: 24px;
    border: 1px solid #666666;
}
</style>